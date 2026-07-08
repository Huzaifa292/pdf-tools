@include('tools._upload_form', [
    'title' => 'PowerPoint to PDF',
    'desc' => 'Convert your Microsoft PowerPoint presentations (PPTX) into high-quality PDF files.',
    'icon' => '📊',
    'action' => '/ppt-to-pdf',
    'multiple' => false,
    'accept' => '.pptx,.ppt',
    'btnText' => 'Convert to PDF',
    'gradient' => 'from-orange-500 to-amber-500',
    'toolType' => 'ppt-to-pdf'
])