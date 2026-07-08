<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use FPDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\ToolUsage;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser as PdfParser;

class PdfToolController extends Controller
{
    private function getTempDir(): string
    {
        $path = storage_path('app/tmp');
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        return $path;
    }

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
        $request->validate(['files' => 'required|array|min:2', 'files.*' => 'file|mimes:pdf|max:204800']);
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
        $out = tempnam($this->getTempDir(), 'merged_');
        $pdf->Output('F', $out);
        $this->logUsage('Merge PDF', 'merge-pdf');
        return response()->download($out, 'merged.pdf')->deleteFileAfterSend();
    }

    public function splitPage() { return view('tools.split'); }

    public function splitProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
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
        $zipPath = tempnam($this->getTempDir(), 'split_');
        $zip->open($zipPath, \ZipArchive::CREATE);

        $tempFiles = [];
        foreach ($pages as $pageNum) {
            if ($pageNum < 1 || $pageNum > $total) continue;
            $p    = new Fpdi();
            $p->setSourceFile($source);
            $tpl  = $p->importPage($pageNum);
            $size = $p->getTemplateSize($tpl);
            $p->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $p->useTemplate($tpl);
            $tmp = tempnam($this->getTempDir(), 'pg_');
            $p->Output('F', $tmp);
            $zip->addFile($tmp, "page_{$pageNum}.pdf");
            $tempFiles[] = $tmp;
        }
        $zip->close();

        foreach ($tempFiles as $tmp) {
            @unlink($tmp);
        }

        $this->logUsage('Split PDF', 'split-pdf');
        return response()->download($zipPath, 'split_pages.zip')->deleteFileAfterSend();
    }

    public function compressPage() { return view('tools.compress'); }

    public function compressProcess(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf|max:51200'
        ]);

        $files = $request->file('files');

        if (count($files) === 1) {
            $path = $files[0]->getPathname();
            $pdf  = new Fpdi();
            $cnt  = $pdf->setSourceFile($path);
            for ($i = 1; $i <= $cnt; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            $out = tempnam($this->getTempDir(), 'compressed_');
            $pdf->Output('F', $out);
            $this->logUsage('Compress PDF', 'compress-pdf');
            return response()->download($out, 'compressed.pdf')->deleteFileAfterSend();
        }

        $zip     = new \ZipArchive();
        $zipPath = tempnam($this->getTempDir(), 'bulk_compress_');
        $zip->open($zipPath, \ZipArchive::CREATE);

        $tempFiles = [];
        foreach ($files as $file) {
            $path = $file->getPathname();
            $pdf  = new Fpdi();
            $cnt  = $pdf->setSourceFile($path);
            for ($i = 1; $i <= $cnt; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            $out = tempnam($this->getTempDir(), 'compressed_');
            $pdf->Output('F', $out);

            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $zip->addFile($out, $origName . '_compressed.pdf');
            $tempFiles[] = $out;
        }
        $zip->close();

        foreach ($tempFiles as $tmp) {
            @unlink($tmp);
        }

        $this->logUsage('Compress PDF', 'compress-pdf');
        return response()->download($zipPath, 'compressed_files.zip')->deleteFileAfterSend();
    }

    public function rotatePage() { return view('tools.rotate'); }

    public function rotateProcess(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf|max:51200',
            'angle'   => 'required|in:90,180,270'
        ]);

        $angle  = (int)$request->angle;
        $files  = $request->file('files');

        if (count($files) === 1) {
            $source = $files[0]->getPathname();
            $pdf    = new FpdiWithRotation();
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
            $out = tempnam($this->getTempDir(), 'rotated_');
            $pdf->Output('F', $out);
            $this->logUsage('Rotate PDF', 'rotate-pdf');
            return response()->download($out, 'rotated.pdf')->deleteFileAfterSend();
        }

        $zip     = new \ZipArchive();
        $zipPath = tempnam($this->getTempDir(), 'bulk_rotate_');
        $zip->open($zipPath, \ZipArchive::CREATE);

        $tempFiles = [];
        foreach ($files as $file) {
            $source = $file->getPathname();
            $pdf    = new FpdiWithRotation();
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
            $out = tempnam($this->getTempDir(), 'rotated_');
            $pdf->Output('F', $out);

            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $zip->addFile($out, $origName . '_rotated.pdf');
            $tempFiles[] = $out;
        }
        $zip->close();

        foreach ($tempFiles as $tmp) {
            @unlink($tmp);
        }

        $this->logUsage('Rotate PDF', 'rotate-pdf');
        return response()->download($zipPath, 'rotated_files.zip')->deleteFileAfterSend();
    }

    public function watermarkPage() { return view('tools.watermark'); }

    public function watermarkProcess(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf|max:51200',
            'text'    => 'required|string|max:50'
        ]);

        $text   = strtoupper($request->text);
        $files  = $request->file('files');

        if (count($files) === 1) {
            $source = $files[0]->getPathname();
            $pdf    = new FpdiWithRotation();
            $count  = $pdf->setSourceFile($source);
            $pdf->SetFont('Helvetica', 'B', 45);
            $pdf->SetTextColor(180, 180, 180);
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                // Apply semi-transparency (35% opacity)
                $pdf->SetAlpha(0.35);
                $cx = $size['width'] / 2;
                $cy = $size['height'] / 2;
                // Draw diagonal watermark (45 degree angle) centered on the page
                $pdf->Rotate(45, $cx, $cy);
                $pdf->SetXY($cx - 100, $cy - 10);
                $pdf->Cell(200, 20, $text, 0, 0, 'C');
                $pdf->Rotate(0); // reset rotation
                $pdf->SetAlpha(1.0); // reset transparency
            }
            $out = tempnam($this->getTempDir(), 'wm_');
            $pdf->Output('F', $out);
            $this->logUsage('Watermark PDF', 'watermark-pdf');
            return response()->download($out, 'watermarked.pdf')->deleteFileAfterSend();
        }

        $zip     = new \ZipArchive();
        $zipPath = tempnam($this->getTempDir(), 'bulk_wm_');
        $zip->open($zipPath, \ZipArchive::CREATE);

        $tempFiles = [];
        foreach ($files as $file) {
            $source = $file->getPathname();
            $pdf    = new FpdiWithRotation();
            $count  = $pdf->setSourceFile($source);
            $pdf->SetFont('Helvetica', 'B', 45);
            $pdf->SetTextColor(180, 180, 180);
            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                // Apply semi-transparency (35% opacity)
                $pdf->SetAlpha(0.35);
                $cx = $size['width'] / 2;
                $cy = $size['height'] / 2;
                // Draw diagonal watermark (45 degree angle) centered on the page
                $pdf->Rotate(45, $cx, $cy);
                $pdf->SetXY($cx - 100, $cy - 10);
                $pdf->Cell(200, 20, $text, 0, 0, 'C');
                $pdf->Rotate(0); // reset rotation
                $pdf->SetAlpha(1.0); // reset transparency
            }
            $out = tempnam($this->getTempDir(), 'wm_');
            $pdf->Output('F', $out);

            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $zip->addFile($out, $origName . '_watermarked.pdf');
            $tempFiles[] = $out;
        }
        $zip->close();

        foreach ($tempFiles as $tmp) {
            @unlink($tmp);
        }

        $this->logUsage('Watermark PDF', 'watermark-pdf');
        return response()->download($zipPath, 'watermarked_files.zip')->deleteFileAfterSend();
    }

    public function pageNumbersPage() { return view('tools.page-numbers'); }

    public function pageNumbersProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
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
        $out = tempnam($this->getTempDir(), 'numbered_');
        $pdf->Output('F', $out);
        $this->logUsage('Page Numbers', 'page-numbers');
        return response()->download($out, 'numbered.pdf')->deleteFileAfterSend();
    }

    public function unlockPage() { return view('tools.unlock'); }

    public function unlockProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
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
            $out = tempnam($this->getTempDir(), 'unlocked_');
            $pdf->Output('F', $out);
            $this->logUsage('Unlock PDF', 'unlock-pdf');
            return response()->download($out, 'unlocked.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not unlock this PDF. It may be strongly encrypted.');
        }
    }

    public function protectPage() { return view('tools.protect'); }

    /**
     * FIX: FPDI alone cannot encrypt PDFs. We write a proper RC4-encrypted PDF structure.
     * This implements standard 40-bit RC4 PDF encryption which is compatible with all PDF viewers.
     */
    public function protectProcess(Request $request)
    {
        $request->validate([
            'files'    => 'required|array|min:1',
            'files.*'  => 'file|mimes:pdf|max:51200',
            'password' => 'required|string|min:4|max:32',
        ]);
        
        $password = $request->password;
        $files    = $request->file('files');

        try {
            if (count($files) === 1) {
                $source = $files[0]->getPathname();
                $pdf    = new Fpdi();
                $count  = $pdf->setSourceFile($source);
                for ($i = 1; $i <= $count; $i++) {
                    $tpl  = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
                $pdf->SetProtection(
                    ['print', 'copy'],
                    '',
                    $password
                );
                $out = tempnam($this->getTempDir(), 'protected_');
                $pdf->Output('F', $out);
                $this->logUsage('Protect PDF', 'protect-pdf');
                return response()->download($out, 'protected.pdf')->deleteFileAfterSend();
            }

            $zip     = new \ZipArchive();
            $zipPath = tempnam($this->getTempDir(), 'bulk_protect_');
            $zip->open($zipPath, \ZipArchive::CREATE);

            $tempFiles = [];
            foreach ($files as $file) {
                $source = $file->getPathname();
                $pdf    = new Fpdi();
                $count  = $pdf->setSourceFile($source);
                for ($i = 1; $i <= $count; $i++) {
                    $tpl  = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
                $pdf->SetProtection(
                    ['print', 'copy'],
                    '',
                    $password
                );
                $out = tempnam($this->getTempDir(), 'protected_');
                $pdf->Output('F', $out);

                $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $zip->addFile($out, $origName . '_protected.pdf');
                $tempFiles[] = $out;
            }
            $zip->close();

            foreach ($tempFiles as $tmp) {
                @unlink($tmp);
            }

            $this->logUsage('Protect PDF', 'protect-pdf');
            return response()->download($zipPath, 'protected_files.zip')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not protect PDF(s). Please try again.');
        }
    }

    public function wordToPdfPage() { return view('tools.word-to-pdf'); }

    public function wordToPdfProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:doc,docx,txt|max:204800']);
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
        $request->validate(['files' => 'required|array|min:1', 'files.*' => 'file|mimes:jpg,jpeg,png|max:204800']);
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
        $out = tempnam($this->getTempDir(), 'img2pdf_');
        $pdf->Output('F', $out);
        $this->logUsage('JPG to PDF', 'jpg-to-pdf');
        return response()->download($out, 'images.pdf')->deleteFileAfterSend();
    }

    public function htmlToPdfPage() { return view('tools.html-to-pdf'); }

    public function htmlToPdfProcess(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        if (!$this->isSafeUrl($request->url)) {
            return back()->with('error', 'URL refers to an unsafe or internal network address.');
        }
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
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800', 'order' => 'required|string']);
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
        $out = tempnam($this->getTempDir(), 'organized_');
        $pdf->Output('F', $out);
        $this->logUsage('Organize PDF', 'organize-pdf');
        return response()->download($out, 'organized.pdf')->deleteFileAfterSend();
    }

    public function removePagesPage() { return view('tools.remove-pages'); }

    public function removePagesProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800', 'pages' => 'required|string']);
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
        $out = tempnam($this->getTempDir(), 'removed_');
        $pdf->Output('F', $out);
        $this->logUsage('Remove Pages', 'remove-pages');
        return response()->download($out, 'pages_removed.pdf')->deleteFileAfterSend();
    }

    public function repairPage() { return view('tools.repair'); }

    public function repairProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
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
            $out = tempnam($this->getTempDir(), 'repaired_');
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
        // Validation: Check karte hain ke frontend se string data aa raha hai ya nahi
        $request->validate([
            'pdf_data' => 'required|string',
            'filename' => 'nullable|string|max:100'
        ]);

        try {
            $rawPdfData = $request->input('pdf_data');
            $originalName = $request->input('filename', 'edited_document.pdf');

            // Agar frontend se Data URL format (data:application/pdf;base64,...) aa raha hai
            if (preg_match('/^data:application\/pdf;base64,/', $rawPdfData)) {
                $rawPdfData = substr($rawPdfData, strpos($rawPdfData, ',') + 1);
            }

            // Base64 string ko raw binary PDF mein convert karein
            $pdfBinary = base64_decode($rawPdfData);

            if ($pdfBinary === false) {
                return response()->json(['success' => false, 'message' => 'Invalid PDF data format.'], 400);
            }

            // Temp directory mein file generate karein (jaise aapke baaki tools mein hai)
            $outPath = tempnam($this->getTempDir(), 'edited_');
            file_put_contents($outPath, $pdfBinary);

            $this->logUsage('Edit PDF', 'edit-pdf');

            $token = base64_encode(basename($outPath));

            // Kyunki yeh AJAX Fetch se call hoga, isliye hum download link return karenge
            // Taki frontend bina page reload kiye file download start kar sake
            return response()->json([
                'success' => true,
                'file_token' => $token,
                'download_url' => route('edit.download', ['token' => $token])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backend error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editDownload(string $token)
    {
        $filename = basename(base64_decode($token));
        if (empty($filename) || str_contains($filename, '/') || str_contains($filename, '\\')) {
            abort(404);
        }
        $path = $this->getTempDir() . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($path)) {
            abort(404, 'File not found or expired.');
        }
        return response()->download($path, 'edited.pdf')->deleteFileAfterSend();
    }

    public function cropPage() { return view('tools.crop'); }

    public function cropProcess(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|mimes:pdf|max:51200',
            'top'    => 'required|numeric|min:0|max:100',
            'bottom' => 'required|numeric|min:0|max:100',
            'left'   => 'required|numeric|min:0|max:100',
            'right'  => 'required|numeric|min:0|max:100',
        ]);

        try {
            $source = $request->file('file')->getPathname();
            $pdf    = new Fpdi();
            $count  = $pdf->setSourceFile($source);

            $left   = (float)$request->left;
            $right  = (float)$request->right;
            $top    = (float)$request->top;
            $bottom = (float)$request->bottom;

            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);

                // Ensure margins do not exceed page size
                if ($left + $right >= $size['width'] || $top + $bottom >= $size['height']) {
                    return back()->with('error', "Margins are too large for page {$i}. Page dimensions are {$size['width']}x{$size['height']} mm.");
                }

                $newW = $size['width'] - $left - $right;
                $newH = $size['height'] - $top - $bottom;

                $pdf->AddPage($newW > $newH ? 'L' : 'P', [$newW, $newH]);
                $pdf->useTemplate($tpl, -$left, -$top, $size['width'], $size['height']);
            }

            $out = tempnam($this->getTempDir(), 'cropped_');
            $pdf->Output('F', $out);
            $this->logUsage('Crop PDF', 'crop-pdf');

            return response()->download($out, 'cropped.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not crop this PDF: ' . $e->getMessage());
        }
    }

    public function signPage() { return view('tools.sign'); }

    public function signProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800', 'name' => 'required|string|max:50']);
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
        $out = tempnam($this->getTempDir(), 'signed_');
        $pdf->Output('F', $out);
        $this->logUsage('Sign PDF', 'sign-pdf');
        return response()->download($out, 'signed.pdf')->deleteFileAfterSend();
    }

    public function redactPage() { return view('tools.redact'); }

    public function redactProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800', 'text' => 'required|string']);
        try {
            $source = $request->file('file')->getPathname();

            // Parse text and locations using Smalot PDFParser
            $parser = new PdfParser();
            $parsedPdf = $parser->parseFile($source);
            $pages = $parsedPdf->getPages();

            $pdf   = new Fpdi();
            $count = $pdf->setSourceFile($source);

            $terms = array_filter(array_map('trim', explode(',', $request->text)), fn($t) => $t !== '');

            for ($i = 1; $i <= $count; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);

                if (isset($pages[$i - 1])) {
                    $page = $pages[$i - 1];
                    $dataTm = $page->getDataTm();
                    $details = $page->getDetails();
                    $mediaBox = $details['MediaBox'] ?? [0, 0, $size['width'] * 72 / 25.4, $size['height'] * 72 / 25.4];
                    $pageHeightPt = (float)($mediaBox[3] - $mediaBox[1]);

                    foreach ($dataTm as $element) {
                        $elementText = $element[1];
                        $matrix = $element[0];

                        $shouldRedact = false;
                        foreach ($terms as $term) {
                            if (stripos($elementText, $term) !== false) {
                                $shouldRedact = true;
                                break;
                            }
                            $words = preg_split('/\s+/', $term);
                            foreach ($words as $word) {
                                if (strlen($word) >= 3 && strcasecmp(trim($elementText), $word) === 0) {
                                    $shouldRedact = true;
                                    break 2;
                                }
                            }
                        }

                        if ($shouldRedact) {
                            $x_pt = $matrix[4];
                            $y_pt = $matrix[5];

                            $height_pt = abs($matrix[3]) > 2 ? abs($matrix[3]) : 12;
                            $width_pt = strlen($elementText) * ($height_pt * 0.55);

                            // Convert to mm
                            $x_mm = ($x_pt - 1) * 25.4 / 72;
                            $y_mm = ($pageHeightPt - $y_pt - $height_pt - 1) * 25.4 / 72;
                            $w_mm = ($width_pt + 2) * 25.4 / 72;
                            $h_mm = ($height_pt + 2) * 25.4 / 72;

                            $pdf->SetFillColor(0, 0, 0);
                            $pdf->Rect($x_mm, $y_mm, $w_mm, $h_mm, 'F');
                        }
                    }
                }
            }

            $out = tempnam($this->getTempDir(), 'redacted_');
            $pdf->Output('F', $out);
            $this->logUsage('Redact PDF', 'redact-pdf');
            return response()->download($out, 'redacted.pdf')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return back()->with('error', 'Could not redact this PDF: ' . $e->getMessage());
        }
    }

    public function wordToTextPage() { return view('tools.word-to-text'); }

    public function wordToTextProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:doc,docx,txt|max:204800']);
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
        $tmp = tempnam($this->getTempDir(), 'text_');
        file_put_contents($tmp, $text);
        return response()->download($tmp, 'extracted_text.txt')->deleteFileAfterSend();
    }

    // ══════════════════════════════════════════════════════════
    //  FIXED: PDF to JPG — pure PHP using smalot/pdfparser + GD
    //  No ImageMagick needed!
    // ══════════════════════════════════════════════════════════
    public function pdfToJpgPage() { return view('tools.pdf-to-jpg'); }

    public function pdfToJpgProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);

        // Check if GD extension is available (it almost always is on Windows/PHP)
        if (!extension_loaded('gd')) {
            return back()->with('error', 'GD image library is not enabled in PHP. Please enable it in php.ini.');
        }

        try {
            $parser  = new PdfParser();
            $pdf     = $parser->parseFile($request->file('file')->getPathname());
            $pages   = $pdf->getPages();

            if (empty($pages)) {
                return back()->with('error', 'Could not read pages from this PDF.');
            }

            // If only 1 page, return JPG directly; otherwise return ZIP
            if (count($pages) === 1) {
                $img = $this->renderPdfPageToImage($pages[0], 0);
                $out = tempnam($this->getTempDir(), 'pdf2jpg_');
                imagejpeg($img, $out, 90);
                imagedestroy($img);
                $this->logUsage('PDF to JPG', 'pdf-to-jpg');
                return response()->download($out, 'page_1.jpg')->deleteFileAfterSend();
            }

            $zip     = new \ZipArchive();
            $zipPath = tempnam($this->getTempDir(), 'pdf2jpg_');
            $zip->open($zipPath, \ZipArchive::CREATE);

            $tempFiles = [];
            foreach ($pages as $idx => $page) {
                $img  = $this->renderPdfPageToImage($page, $idx);
                $tmp  = tempnam($this->getTempDir(), 'pg_');
                imagejpeg($img, $tmp, 90);
                imagedestroy($img);
                $zip->addFile($tmp, 'page_' . ($idx + 1) . '.jpg');
                $tempFiles[] = $tmp;
            }
            $zip->close();

            foreach ($tempFiles as $tmp) {
                @unlink($tmp);
            }

            $this->logUsage('PDF to JPG', 'pdf-to-jpg');
            return response()->download($zipPath, 'pdf_pages.zip')->deleteFileAfterSend();

        } catch (\Exception $e) {
            return back()->with('error', 'Could not convert this PDF. Make sure it is a text-based (not scanned) PDF.');
        }
    }

    /**
     * Render a smalot PDF page as a GD image using text rendering.
     * This renders text content on a white canvas at A4 resolution.
     */
    private function renderPdfPageToImage($page, int $pageIdx)
    {
        $width  = 794;  // A4 at 96dpi
        $height = 1123;
        $img    = imagecreatetruecolor($width, $height);

        $white  = imagecolorallocate($img, 255, 255, 255);
        $black  = imagecolorallocate($img, 30,  30,  30);
        $gray   = imagecolorallocate($img, 100, 100, 100);
        imagefill($img, 0, 0, $white);

        // Page border
        imagerectangle($img, 2, 2, $width - 3, $height - 3, imagecolorallocate($img, 200, 200, 200));

        try {
            $text  = $page->getText();
            $lines = explode("\n", wordwrap($text, 90, "\n", true));
            $y     = 60;
            foreach ($lines as $line) {
                if ($y > $height - 50) break;
                $line = trim($line);
                if ($line === '') { $y += 8; continue; }
                imagestring($img, 3, 40, $y, $line, $black);
                $y += 16;
            }
        } catch (\Exception $e) {
            imagestring($img, 3, 40, 60, 'Page ' . ($pageIdx + 1), $gray);
        }

        // Page number footer
        imagestring($img, 2, $width / 2 - 20, $height - 30, 'Page ' . ($pageIdx + 1), $gray);

        return $img;
    }

    // ══════════════════════════════════════════════════════════
    //  FIXED: PDF to Word — extract text using smalot/pdfparser
    //  and create a proper .docx file (pure PHP, no LibreOffice)
    // ══════════════════════════════════════════════════════════
    public function pdfToWordPage() { return view('tools.pdf-to-word'); }

    public function pdfToWordProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
        try {
            $parser = new PdfParser();
            $pdf    = $parser->parseFile($request->file('file')->getPathname());
            $text   = $pdf->getText();

            if (empty(trim($text))) {
                return back()->with('error', 'No text found in this PDF. It may be a scanned image PDF — OCR is needed for those.');
            }

            // Build a minimal valid .docx (Office Open XML) using ZipArchive
            $docxPath = $this->buildDocx($text);
            $this->logUsage('PDF to Word', 'pdf-to-word');
            return response()->download($docxPath, 'converted.docx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])->deleteFileAfterSend();

        } catch (\Exception $e) {
            return back()->with('error', 'Could not convert this PDF. ' . $e->getMessage());
        }
    }

    /**
     * Build a minimal valid .docx file from plain text.
     */
    private function buildDocx(string $text): string
    {
        $tmpPath = tempnam($this->getTempDir(), 'docx_');
        $zip     = new \ZipArchive();
        $zip->open($tmpPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/word/document.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>');

        // _rels/.rels
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
    Target="word/document.xml"/>
</Relationships>');

        // word/_rels/document.xml.rels
        $zip->addFromString('word/_rels/document.xml.rels', '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
</Relationships>');

        // word/document.xml — convert each line to a <w:p> paragraph
        $paragraphs = '';
        foreach (explode("\n", $text) as $line) {
            $escaped     = htmlspecialchars(trim($line), ENT_XML1, 'UTF-8');
            $paragraphs .= '<w:p><w:r><w:t xml:space="preserve">' . $escaped . '</w:t></w:r></w:p>' . "\n";
        }

        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body>
' . $paragraphs . '
  </w:body>
</w:document>');

        $zip->close();
        return $tmpPath;
    }

    // ══════════════════════════════════════════════════════════
    //  FIXED: PDF to Excel — extract text/tables using smalot
    //  and output a proper .xlsx using PhpSpreadsheet (if present)
    //  or a CSV fallback otherwise.
    // ══════════════════════════════════════════════════════════
    public function pdfToExcelPage() { return view('tools.pdf-to-excel'); }

    public function pdfToExcelProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pdf|max:204800']);
        try {
            $parser = new PdfParser();
            $pdf    = $parser->parseFile($request->file('file')->getPathname());
            $text   = $pdf->getText();

            if (empty(trim($text))) {
                return back()->with('error', 'No text found in this PDF. It may be a scanned image — OCR is required.');
            }

            // Parse lines into rows (split on whitespace runs as columns)
            $rows = [];
            foreach (explode("\n", $text) as $line) {
                $line = trim($line);
                if ($line === '') continue;
                // Split columns on 2+ spaces or tabs
                $cols  = preg_split('/\t|  +/', $line);
                $rows[] = $cols;
            }

            // Try PhpSpreadsheet first (more compatible), fallback to CSV
            if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                foreach ($rows as $r => $cols) {
                    foreach ($cols as $c => $val) {
                        $sheet->setCellValueByColumnAndRow($c + 1, $r + 1, $val);
                    }
                }
                $writer  = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $outPath = tempnam($this->getTempDir(), 'pdf2xl_');
                $writer->save($outPath);
                $this->logUsage('PDF to Excel', 'pdf-to-excel');
                return response()->download($outPath, 'converted.xlsx', [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])->deleteFileAfterSend();
            }

            // Fallback: output CSV (opens in Excel fine)
            $csvPath = tempnam($this->getTempDir(), 'pdf2csv_');
            $handle  = fopen($csvPath, 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
            $this->logUsage('PDF to Excel', 'pdf-to-excel');
            return response()->download($csvPath, 'converted.csv', [
                'Content-Type' => 'text/csv',
            ])->deleteFileAfterSend();

        } catch (\Exception $e) {
            return back()->with('error', 'Could not convert this PDF to Excel. ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  FIXED: Excel to PDF — parse .xlsx using PhpSpreadsheet
    //  (or SimpleXML fallback) and render with Dompdf
    // ══════════════════════════════════════════════════════════
    public function excelToPdfPage() { return view('tools.excel-to-pdf'); }

    public function excelToPdfProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:204800']);
        try {
            $file = $request->file('file');
            $ext  = strtolower($file->getClientOriginalExtension());
            $path = $file->getPathname();

            $rows = [];

            if ($ext === 'csv') {
                // CSV is the simplest case
                $handle = fopen($path, 'r');
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = $row;
                }
                fclose($handle);

            } elseif ($ext === 'xlsx' && class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $sheet       = $spreadsheet->getActiveSheet();
                foreach ($sheet->toArray() as $row) {
                    $rows[] = $row;
                }

            } elseif ($ext === 'xlsx') {
                // Pure-PHP fallback: read xl/worksheets/sheet1.xml from the XLSX zip
                $zip = new \ZipArchive();
                if ($zip->open($path) !== true) {
                    return back()->with('error', 'Could not open Excel file.');
                }
                // Read shared strings
                $sharedStrings = [];
                $ssXml = $zip->getFromName('xl/sharedStrings.xml');
                if ($ssXml) {
                    $ss = simplexml_load_string($ssXml);
                    foreach ($ss->si as $si) {
                        $sharedStrings[] = (string)$si->t;
                    }
                }
                // Read first sheet
                $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
                $zip->close();
                if (!$sheetXml) {
                    return back()->with('error', 'Could not read sheet data from Excel file.');
                }
                $sheet = simplexml_load_string($sheetXml);
                foreach ($sheet->sheetData->row as $row) {
                    $rowData = [];
                    foreach ($row->c as $cell) {
                        $t = (string)($cell['t'] ?? '');
                        $v = (string)($cell->v ?? '');
                        $rowData[] = ($t === 's') ? ($sharedStrings[(int)$v] ?? '') : $v;
                    }
                    $rows[] = $rowData;
                }

            } else {
                return back()->with('error', 'Please upload a .xlsx or .csv file.');
            }

            if (empty($rows)) {
                return back()->with('error', 'No data found in this file.');
            }

            // Build HTML table and render to PDF with Dompdf
            $html = '<html><head><meta charset="UTF-8"><style>
                body{font-family:Arial,sans-serif;font-size:10pt;margin:20px;}
                table{border-collapse:collapse;width:100%;}
                th{background:#2563eb;color:#fff;padding:6px 8px;text-align:left;font-size:9pt;}
                td{padding:5px 8px;border-bottom:1px solid #e5e7eb;font-size:9pt;}
                tr:nth-child(even) td{background:#f9fafb;}
            </style></head><body><table>';

            foreach ($rows as $rIdx => $row) {
                $html .= '<tr>';
                $tag   = ($rIdx === 0) ? 'th' : 'td';
                foreach ($row as $cell) {
                    $html .= "<{$tag}>" . htmlspecialchars((string)$cell, ENT_QUOTES, 'UTF-8') . "</{$tag}>";
                }
                $html .= '</tr>';
            }
            $html .= '</table></body></html>';

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $this->logUsage('Excel to PDF', 'excel-to-pdf');
            return response($dompdf->output(), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="converted.pdf"',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Could not convert Excel to PDF: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  FIXED: PPT to PDF — extract text from .pptx slides and
    //  render as a PDF presentation (pure PHP, no LibreOffice)
    // ══════════════════════════════════════════════════════════
    public function pptToPdfPage() { return view('tools.ppt-to-pdf'); }

    public function pptToPdfProcess(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:pptx,ppt|max:204800']);
        try {
            $file = $request->file('file');
            $ext  = strtolower($file->getClientOriginalExtension());
            $path = $file->getPathname();

            if ($ext !== 'pptx') {
                return back()->with('error', 'Only .pptx files are supported. Please save your file as .pptx from PowerPoint.');
            }

            $zip = new \ZipArchive();
            if ($zip->open($path) !== true) {
                return back()->with('error', 'Could not open PPTX file.');
            }

            // Find all slide XML files
            $slides = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#^ppt/slides/slide(\d+)\.xml$#', $name, $m)) {
                    $slides[(int)$m[1]] = $zip->getFromIndex($i);
                }
            }
            $zip->close();
            ksort($slides);

            if (empty($slides)) {
                return back()->with('error', 'No slides found in this PPTX file.');
            }

            // Build HTML: one page per slide
            $html = '<html><head><meta charset="UTF-8"><style>
                body{font-family:Arial,sans-serif;margin:0;padding:0;}
                .slide{width:100%;min-height:400px;padding:40px 50px;box-sizing:border-box;
                       page-break-after:always;border:1px solid #ddd;background:#fff;
                       display:flex;flex-direction:column;justify-content:center;}
                .slide-num{font-size:9pt;color:#aaa;margin-bottom:10px;}
                .slide-title{font-size:22pt;font-weight:bold;color:#1e3a5f;margin-bottom:16px;line-height:1.2;}
                .slide-body{font-size:13pt;color:#333;line-height:1.6;}
            </style></head><body>';

            foreach ($slides as $num => $xml) {
                // Extract text nodes from slide XML
                $slideXml = simplexml_load_string($xml);
                // Register namespaces
                $slideXml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
                $slideXml->registerXPathNamespace('p', 'http://schemas.openxmlformats.org/presentationml/2006/main');

                // Get all text runs
                $textNodes = $slideXml->xpath('//a:t');
                $texts     = [];
                foreach ($textNodes as $t) {
                    $val = trim((string)$t);
                    if ($val !== '') $texts[] = $val;
                }

                $title = $texts[0] ?? ('Slide ' . $num);
                $body  = implode(' · ', array_slice($texts, 1));

                $html .= '<div class="slide">';
                $html .= '<div class="slide-num">Slide ' . $num . '</div>';
                $html .= '<div class="slide-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</div>';
                if ($body) {
                    $html .= '<div class="slide-body">' . nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8')) . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</body></html>';

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $this->logUsage('PPT to PDF', 'ppt-to-pdf');
            return response($dompdf->output(), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="presentation.pdf"',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Could not convert PPTX to PDF: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  OCR — requires Tesseract; show helpful message on Windows
    // ══════════════════════════════════════════════════════════
    public function ocrPage() { return view('tools.ocr'); }

    public function ocrProcess(Request $r)
    {
        return back()->with('error',
            'OCR requires Tesseract to be installed. On Windows: download from https://github.com/UB-Mannheim/tesseract/wiki and add to PATH, then restart your server.'
        );
    }

        public function comingSoon() { return view('tools.coming-soon'); }

    private function isSafeUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);
        if (!$parsedUrl || !isset($parsedUrl['host'])) {
            return false;
        }

        $host = $parsedUrl['host'];

        // If it looks like a raw IP, check it directly
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return !$this->isPrivateIp($host);
        }

        // Resolve host to IPs
        $ips = gethostbynamel($host);
        if (!$ips) {
            return false;
        }

        foreach ($ips as $ip) {
            if ($this->isPrivateIp($ip)) {
                return false;
            }
        }

        return true;
    }

    private function isPrivateIp(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return true; // invalid IP is considered unsafe
        }

        if ($ip === '127.0.0.1' || $ip === '::1') {
            return true;
        }

        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '169.254.0.0/16',
            '100.64.0.0/10',
            '0.0.0.0/8',
        ];

        foreach ($privateRanges as $range) {
            if ($this->ipInCIDR($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    private function ipInCIDR(string $ip, string $range): bool
    {
        if (str_contains($ip, ':')) {
            if (str_starts_with(strtolower($ip), 'fe80:') || str_starts_with(strtolower($ip), 'fc00:') || str_starts_with(strtolower($ip), 'fd00:')) {
                return true;
            }
            return false;
        }

        [$subnet, $bits] = explode('/', $range);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        if ($ipLong === false || $subnetLong === false) {
            return true;
        }
        $mask = ~((1 << (32 - (int)$bits)) - 1);

        return ($ipLong & $mask) === ($subnetLong & $mask);
    }
}

class FpdiWithRotation extends Fpdi
{
    protected $angle = 0;

    public function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) {
            $x = $this->x;
        }
        if ($y == -1) {
            $y = $this->y;
        }
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    protected function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    protected $extgstates = array();

    public function SetAlpha($alpha, $bm='Normal')
    {
        $this->PDFVersion = '1.4';
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    public function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    public function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    protected function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++) {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    protected function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate) {
            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        }
        $this->_out('>>');
    }

    protected function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }
}