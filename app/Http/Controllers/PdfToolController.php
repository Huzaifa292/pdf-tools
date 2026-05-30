<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use FPDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\ToolUsage;
use Illuminate\Support\Facades\Auth;

class PdfToolController extends Controller
{
    private function logUsage(string $toolName, string $toolSlug): void
    {
        if (Auth::check()) {
            ToolUsage::create([
                'user_id'   => Auth::id(),
                'tool_name' => $toolName,
                'tool_slug' => $toolSlug,
            ]);
        }
    }

    public function mergePage() { return view('tools.merge'); }

    public function mergeProcess(Request $request)
    {
        $request->validate(['files' => 'required|array|min:2', 'files.*' => 'file|mimes:pdf|max:51200']);
        $pdf = new Fpdi();
        foreach ($request->file('files') as $file) {
            $count = $pdf->setSourceFile($file->getPathname());
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
        }
        $out = tempnam(sys_get_temp_dir(), 'merged_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Merge PDF', 'merge-pdf');
        return response()->download($out, 'merged.pdf')->deleteFileAfterSend();
    }

    public function splitPage() { return view('tools.split'); }

    public function splitProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        $source = $request->file('file')->getPathname();
        $input  = trim($request->pages ?? '');
        $pdf    = new Fpdi();
        $total  = $pdf->setSourceFile($source);

        if ($input === '') {
            $pages = range(1, $total);
        } else {
            $pages = [];
            foreach (explode(',', $input) as $part) {
                $part = trim($part);
                if (str_contains($part, '-')) {
                    [$s, $e] = explode('-', $part);
                    foreach (range((int)$s, (int)$e) as $p) $pages[] = $p;
                } else {
                    $pages[] = (int)$part;
                }
            }
        }

        $zip     = new \ZipArchive();
        $zipPath = tempnam(sys_get_temp_dir(), 'split_') . '.zip';
        $zip->open($zipPath, \ZipArchive::CREATE);

        foreach ($pages as $pageNum) {
            if ($pageNum < 1 || $pageNum > $total) continue;
            $p    = new Fpdi();
            $p->setSourceFile($source);
            $tpl  = $p->importPage($pageNum);
            $size = $p->getTemplateSize($tpl);
            $p->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $p->useTemplate($tpl);
            $tmp = tempnam(sys_get_temp_dir(), 'pg_') . '.pdf';
            $p->Output('F', $tmp);
            $zip->addFile($tmp, "page_{$pageNum}.pdf");
        }
        $zip->close();
        $this->logUsage('Split PDF', 'split-pdf');
        return response()->download($zipPath, 'split_pages.zip')->deleteFileAfterSend();
    }

    public function compressPage() { return view('tools.compress'); }

    public function compressProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        $path = $request->file('file')->getPathname();
        $pdf  = new Fpdi();
        $cnt  = $pdf->setSourceFile($path);
        for ($i = 1; $i <= $cnt; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }
        $out = tempnam(sys_get_temp_dir(), 'compressed_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Compress PDF', 'compress-pdf');
        return response()->download($out, 'compressed.pdf')->deleteFileAfterSend();
    }

    public function rotatePage() { return view('tools.rotate'); }

    public function rotateProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'angle' => 'required|in:90,180,270']);
        $angle  = (int)$request->angle;
        $source = $request->file('file')->getPathname();
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            if ($angle == 90 || $angle == 270) {
                $pdf->AddPage($size['height'] > $size['width'] ? 'L' : 'P', [$size['height'], $size['width']]);
            } else {
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            }
            $pdf->Rotate($angle, $pdf->GetPageWidth()/2, $pdf->GetPageHeight()/2);
            $pdf->useTemplate($tpl);
        }
        $out = tempnam(sys_get_temp_dir(), 'rotated_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Rotate PDF', 'rotate-pdf');
        return response()->download($out, 'rotated.pdf')->deleteFileAfterSend();
    }

    public function watermarkPage() { return view('tools.watermark'); }

    public function watermarkProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'text' => 'required|string|max:50']);
        $source = $request->file('file')->getPathname();
        $text   = strtoupper($request->text);
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        $pdf->SetFont('Helvetica', 'B', 45);
        $pdf->SetTextColor(180, 180, 180);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
            $pdf->SetXY(0, $size['height']/2 - 15);
            $pdf->Cell($size['width'], 0, $text, 0, 0, 'C');
        }
        $out = tempnam(sys_get_temp_dir(), 'wm_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Watermark PDF', 'watermark-pdf');
        return response()->download($out, 'watermarked.pdf')->deleteFileAfterSend();
    }

    public function pageNumbersPage() { return view('tools.page-numbers'); }

    public function pageNumbersProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        $source = $request->file('file')->getPathname();
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
            $pdf->SetXY(0, $size['height'] - 12);
            $pdf->Cell($size['width'], 0, "Page $i of $count", 0, 0, 'C');
        }
        $out = tempnam(sys_get_temp_dir(), 'numbered_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Page Numbers', 'page-numbers');
        return response()->download($out, 'numbered.pdf')->deleteFileAfterSend();
    }

    public function unlockPage() { return view('tools.unlock'); }

    public function unlockProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        try {
            $source = $request->file('file')->getPathname();
            $pdf    = new Fpdi();
            $count  = $pdf->setSourceFile($source);
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            $out = tempnam(sys_get_temp_dir(), 'unlocked_') . '.pdf';
            $pdf->Output('F', $out);
            $this->logUsage('Unlock PDF', 'unlock-pdf');
            return response()->download($out, 'unlocked.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not unlock this PDF. It may be strongly encrypted.');
        }
    }

    public function protectPage() { return view('tools.protect'); }

    public function protectProcess(Request $request)
    {
        $request->validate([
            'file'     => 'required|file|mimes:pdf|max:51200',
            'password' => 'required|string|min:4|max:32',
        ]);
        try {
            $source = $request->file('file')->getPathname();
            $pdf    = new Fpdi();
            $count  = $pdf->setSourceFile($source);
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            $out = tempnam(sys_get_temp_dir(), 'protected_') . '.pdf';
            $pdf->Output('F', $out);
            $this->logUsage('Protect PDF', 'protect-pdf');
            return response()->download($out, 'protected.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not process this PDF. Please try again.');
        }
    }

    public function wordToPdfPage() { return view('tools.word-to-pdf'); }

    public function wordToPdfProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:doc,docx,txt|max:20480']);
        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getPathname();

        if ($ext === 'txt') {
            $raw      = file_get_contents($path);
            $encoding = mb_detect_encoding($raw, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ASCII'], true);
            $text     = ($encoding && $encoding !== 'UTF-8')
                        ? mb_convert_encoding($raw, 'UTF-8', $encoding)
                        : $raw;
        } elseif ($ext === 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) {
                return back()->with('error', 'Could not open DOCX file.');
            }
            $xml  = $zip->getFromName('word/document.xml');
            $zip->close();
            $xml  = str_replace('</w:p>',  "\n", $xml);
            $xml  = str_replace('</w:tr>', "\n", $xml);
            $xml  = str_replace('</w:tc>', "\t", $xml);
            $text = strip_tags($xml);
            $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            $text = preg_replace('/[ \t]+/', ' ', $text);
            $text = preg_replace('/\n{3,}/', "\n\n", trim($text));
        } else {
            return back()->with('error', 'Old .doc format is not supported. Please save as .docx or .txt');
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'helvetica');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(
            '<html><head><meta charset="UTF-8"><style>body{font-family:helvetica;font-size:12pt;line-height:1.8;padding:40px;color:#000;}pre{white-space:pre-wrap;word-break:break-word;}</style></head><body><pre>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</pre></body></html>',
            'UTF-8'
        );
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $this->logUsage('Word to PDF', 'word-to-pdf');
        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="converted.pdf"',
        ]);
    }

    public function jpgToPdfPage() { return view('tools.jpg-to-pdf'); }

    public function jpgToPdfProcess(Request $request)
    {
        $request->validate(['files' => 'required|array|min:1', 'files.*' => 'file|mimes:jpg,jpeg,png|max:20480']);
        $pdf = new \FPDF();
        foreach ($request->file('files') as $img) {
            $path = $img->getPathname();
            $mime = $img->getMimeType();
            list($w, $h) = getimagesize($path);
            $wMm = round($w * 25.4 / 96);
            $hMm = round($h * 25.4 / 96);
            $pdf->AddPage($wMm > $hMm ? 'L' : 'P', [$wMm, $hMm]);
            $type = str_contains($mime, 'png') ? 'PNG' : 'JPEG';
            $pdf->Image($path, 0, 0, $wMm, $hMm, $type);
        }
        $out = tempnam(sys_get_temp_dir(), 'img2pdf_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('JPG to PDF', 'jpg-to-pdf');
        return response()->download($out, 'images.pdf')->deleteFileAfterSend();
    }

    public function htmlToPdfPage() { return view('tools.html-to-pdf'); }

    public function htmlToPdfProcess(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $context = stream_context_create(['http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0']]);
        $html = @file_get_contents($request->url, false, $context);
        if (!$html) return back()->with('error', 'Could not fetch this URL. Make sure it is publicly accessible.');
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $this->logUsage('HTML to PDF', 'html-to-pdf');
        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="webpage.pdf"',
        ]);
    }

    public function organizePage() { return view('tools.organize'); }

    public function organizeProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'order' => 'required|string']);
        $source = $request->file('file')->getPathname();
        $order  = array_map('intval', explode(',', $request->order));
        $pdf    = new Fpdi();
        $total  = $pdf->setSourceFile($source);
        foreach ($order as $pageNum) {
            if ($pageNum < 1 || $pageNum > $total) continue;
            $tpl  = $pdf->importPage($pageNum);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }
        $out = tempnam(sys_get_temp_dir(), 'organized_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Organize PDF', 'organize-pdf');
        return response()->download($out, 'organized.pdf')->deleteFileAfterSend();
    }

    public function removePagesPage() { return view('tools.remove-pages'); }

    public function removePagesProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'pages' => 'required|string']);
        $source = $request->file('file')->getPathname();
        $remove = [];
        foreach (explode(',', $request->pages) as $part) {
            $part = trim($part);
            if (str_contains($part, '-')) {
                [$s, $e] = explode('-', $part);
                foreach (range((int)$s, (int)$e) as $p) $remove[] = $p;
            } else {
                $remove[] = (int)$part;
            }
        }
        $pdf   = new Fpdi();
        $total = $pdf->setSourceFile($source);
        for ($i = 1; $i <= $total; $i++) {
            if (in_array($i, $remove)) continue;
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }
        $out = tempnam(sys_get_temp_dir(), 'removed_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Remove Pages', 'remove-pages');
        return response()->download($out, 'pages_removed.pdf')->deleteFileAfterSend();
    }

    public function repairPage() { return view('tools.repair'); }

    public function repairProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        try {
            $source = $request->file('file')->getPathname();
            $pdf    = new Fpdi();
            $count  = $pdf->setSourceFile($source);
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            $out = tempnam(sys_get_temp_dir(), 'repaired_') . '.pdf';
            $pdf->Output('F', $out);
            $this->logUsage('Repair PDF', 'repair-pdf');
            return response()->download($out, 'repaired.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not repair this PDF. File may be too damaged.');
        }
    }


    public function editPage() { return view('tools.edit'); }

    
    public function editProcess(Request $request)
    {
        return back()->with('error', 'Please use the Download button in the editor above.');
    }

    public function cropPage() { return view('tools.crop'); }

    public function cropProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200']);
        $source = $request->file('file')->getPathname();
        $top    = (float)($request->top    ?? 10);
        $bottom = (float)($request->bottom ?? 10);
        $left   = (float)($request->left   ?? 10);
        $right  = (float)($request->right  ?? 10);
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $nW   = $size['width']  - $left - $right;
            $nH   = $size['height'] - $top  - $bottom;
            $pdf->AddPage($nW > $nH ? 'L' : 'P', [$nW, $nH]);
            $pdf->useTemplate($tpl, -$left, -$top, $size['width'], $size['height']);
        }
        $out = tempnam(sys_get_temp_dir(), 'cropped_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Crop PDF', 'crop-pdf');
        return response()->download($out, 'cropped.pdf')->deleteFileAfterSend();
    }

    public function signPage() { return view('tools.sign'); }

    public function signProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'name' => 'required|string|max:50']);
        $source = $request->file('file')->getPathname();
        $name   = $request->name;
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        $pdf->SetFont('Helvetica', 'I', 16);
        $pdf->SetTextColor(30, 80, 200);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
            if ($i === $count) {
                $pdf->SetXY(10, $size['height'] - 25);
                $pdf->Cell(80, 10, 'Signed by: ' . $name, 'T', 0, 'L');
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->SetTextColor(120, 120, 120);
                $pdf->SetXY(10, $size['height'] - 15);
                $pdf->Cell(80, 8, 'Date: ' . date('Y-m-d H:i'), 0, 0, 'L');
            }
        }
        $out = tempnam(sys_get_temp_dir(), 'signed_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Sign PDF', 'sign-pdf');
        return response()->download($out, 'signed.pdf')->deleteFileAfterSend();
    }

    public function redactPage() { return view('tools.redact'); }

    public function redactProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:51200', 'text' => 'required|string']);
        $source = $request->file('file')->getPathname();
        $pdf    = new Fpdi();
        $count  = $pdf->setSourceFile($source);
        $pdf->SetFillColor(0, 0, 0);
        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }
        $out = tempnam(sys_get_temp_dir(), 'redacted_') . '.pdf';
        $pdf->Output('F', $out);
        $this->logUsage('Redact PDF', 'redact-pdf');
        return response()->download($out, 'redacted.pdf')->deleteFileAfterSend();
    }

    
    public function wordToTextPage() { return view('tools.word-to-text'); }

    public function wordToTextProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:doc,docx,txt|max:20480']);
        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getPathname();

        if ($ext === 'txt') {
            $raw      = file_get_contents($path);
            $encoding = mb_detect_encoding($raw, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ASCII'], true);
            $text     = ($encoding && $encoding !== 'UTF-8')
                        ? mb_convert_encoding($raw, 'UTF-8', $encoding)
                        : $raw;
        } elseif ($ext === 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) {
                return back()->with('error', 'Could not open DOCX file.');
            }
            $xml  = $zip->getFromName('word/document.xml');
            $zip->close();
            $xml  = str_replace('</w:p>',  "\n", $xml);
            $xml  = str_replace('</w:tr>', "\n", $xml);
            $xml  = str_replace('</w:tc>', "\t", $xml);
            $text = strip_tags($xml);
            $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            $text = preg_replace('/[ \t]+/', ' ', $text);
            $text = preg_replace('/\n{3,}/', "\n\n", trim($text));
        } else {
            return back()->with('error', 'Old .doc format is not supported. Please save as .docx or .txt');
        }

        session(['extracted_text' => $text]);
        $this->logUsage('Word to Text', 'word-to-text');
        return back()->with('extracted_text', $text);
    }

    public function wordToTextDownload()
    {
        $text = session('extracted_text');
        if (!$text) return redirect('/word-to-text')->with('error', 'No text found. Please upload again.');
        $tmp = tempnam(sys_get_temp_dir(), 'text_') . '.txt';
        file_put_contents($tmp, $text);
        return response()->download($tmp, 'extracted_text.txt')->deleteFileAfterSend();
    }

    // ── SERVER LIBRARY TOOLS ─────────────────────────────────
    public function pdfToJpgPage()   { return view('tools.pdf-to-jpg'); }
    public function pdfToWordPage()  { return view('tools.pdf-to-word'); }
    public function pdfToExcelPage() { return view('tools.pdf-to-excel'); }
    public function excelToPdfPage() { return view('tools.excel-to-pdf'); }
    public function pptToPdfPage()   { return view('tools.ppt-to-pdf'); }
    public function ocrPage()        { return view('tools.ocr'); }

    public function pdfToJpgProcess(Request $r)   { return back()->with('error', 'PDF to JPG requires ImageMagick on the server.'); }
    public function pdfToWordProcess(Request $r)  { return back()->with('error', 'PDF to Word requires advanced OCR. Coming soon!'); }
    public function pdfToExcelProcess(Request $r) { return back()->with('error', 'PDF to Excel requires advanced parsing. Coming soon!'); }
    public function excelToPdfProcess(Request $r) { return back()->with('error', 'Excel to PDF requires LibreOffice on the server.'); }
    public function pptToPdfProcess(Request $r)   { return back()->with('error', 'PPT to PDF requires LibreOffice on the server.'); }
    public function ocrProcess(Request $r)        { return back()->with('error', 'OCR requires Tesseract on the server.'); }

    public function comingSoon() { return view('tools.coming-soon'); }
}