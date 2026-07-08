@include('tools._upload_form', [
    'title' => 'OCR PDF',
    'desc' => 'Perform Optical Character Recognition on scanned PDF documents to extract text.',
    'icon' => '🔍',
    'action' => '/ocr-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Perform OCR',
    'gradient' => 'from-cyan-500 to-teal-500',
    'toolType' => 'ocr'
])