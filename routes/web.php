<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfToolController;

// require __DIR__.'/auth.php'; // Authentication disabled

// Home
Route::get('/', fn() => view('home'));

// ── PDF Tools - Sab bina login ke bhi kaam karte hain ────
Route::get('/merge-pdf',      [PdfToolController::class, 'mergePage']);
Route::post('/merge-pdf',     [PdfToolController::class, 'mergeProcess']);

Route::get('/split-pdf',      [PdfToolController::class, 'splitPage']);
Route::post('/split-pdf',     [PdfToolController::class, 'splitProcess']);

Route::get('/compress-pdf',   [PdfToolController::class, 'compressPage']);
Route::post('/compress-pdf',  [PdfToolController::class, 'compressProcess']);

Route::get('/rotate-pdf',     [PdfToolController::class, 'rotatePage']);
Route::post('/rotate-pdf',    [PdfToolController::class, 'rotateProcess']);

Route::get('/watermark-pdf',  [PdfToolController::class, 'watermarkPage']);
Route::post('/watermark-pdf', [PdfToolController::class, 'watermarkProcess']);

Route::get('/page-numbers',   [PdfToolController::class, 'pageNumbersPage']);
Route::post('/page-numbers',  [PdfToolController::class, 'pageNumbersProcess']);

Route::get('/unlock-pdf',     [PdfToolController::class, 'unlockPage']);
Route::post('/unlock-pdf',    [PdfToolController::class, 'unlockProcess']);

Route::get('/protect-pdf',    [PdfToolController::class, 'protectPage']);
Route::post('/protect-pdf',   [PdfToolController::class, 'protectProcess']);

Route::get('/word-to-pdf',    [PdfToolController::class, 'wordToPdfPage']);
Route::post('/word-to-pdf',   [PdfToolController::class, 'wordToPdfProcess']);

Route::get('/jpg-to-pdf',     [PdfToolController::class, 'jpgToPdfPage']);
Route::post('/jpg-to-pdf',    [PdfToolController::class, 'jpgToPdfProcess']);

Route::get('/html-to-pdf',    [PdfToolController::class, 'htmlToPdfPage']);
Route::post('/html-to-pdf',   [PdfToolController::class, 'htmlToPdfProcess']);

Route::get('/pdf-to-jpg',     [PdfToolController::class, 'pdfToJpgPage']);
Route::post('/pdf-to-jpg',    [PdfToolController::class, 'pdfToJpgProcess']);

Route::get('/pdf-to-word',    [PdfToolController::class, 'pdfToWordPage']);
Route::post('/pdf-to-word',   [PdfToolController::class, 'pdfToWordProcess']);

Route::get('/pdf-to-excel',   [PdfToolController::class, 'pdfToExcelPage']);
Route::post('/pdf-to-excel',  [PdfToolController::class, 'pdfToExcelProcess']);

Route::get('/excel-to-pdf',   [PdfToolController::class, 'excelToPdfPage']);
Route::post('/excel-to-pdf',  [PdfToolController::class, 'excelToPdfProcess']);

Route::get('/ppt-to-pdf',     [PdfToolController::class, 'pptToPdfPage']);
Route::post('/ppt-to-pdf',    [PdfToolController::class, 'pptToPdfProcess']);

Route::get('/organize-pdf',   [PdfToolController::class, 'organizePage']);
Route::post('/organize-pdf',  [PdfToolController::class, 'organizeProcess']);

Route::get('/edit-pdf',       [PdfToolController::class, 'editPage']);
Route::post('/edit-pdf',      [PdfToolController::class, 'editProcess']);
Route::get('/edit-pdf/download/{token}', [PdfToolController::class, 'editDownload'])->name('edit.download');

Route::get('/crop-pdf',       [PdfToolController::class, 'cropPage']);
Route::post('/crop-pdf',      [PdfToolController::class, 'cropProcess']);

Route::get('/sign-pdf',       [PdfToolController::class, 'signPage']);
Route::post('/sign-pdf',      [PdfToolController::class, 'signProcess']);

Route::get('/ocr-pdf',        [PdfToolController::class, 'ocrPage']);
Route::post('/ocr-pdf',       [PdfToolController::class, 'ocrProcess']);

Route::get('/repair-pdf',     [PdfToolController::class, 'repairPage']);
Route::post('/repair-pdf',    [PdfToolController::class, 'repairProcess']);

Route::get('/redact-pdf',     [PdfToolController::class, 'redactPage']);
Route::post('/redact-pdf',    [PdfToolController::class, 'redactProcess']);

Route::get('/remove-pages',   [PdfToolController::class, 'removePagesPage']);
Route::post('/remove-pages',  [PdfToolController::class, 'removePagesProcess']);

// ── Word to Text ─────────────────────────────────────────
Route::get('/word-to-text',          [PdfToolController::class, 'wordToTextPage']);
Route::post('/word-to-text',         [PdfToolController::class, 'wordToTextProcess']);
Route::get('/word-to-text/download', [PdfToolController::class, 'wordToTextDownload'])->name('word.text.download');

// Contact Page
Route::get('/contact', fn() => view('contact'))->name('contact');