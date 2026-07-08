@include('tools._upload_form', [
    'title' => 'Word to PDF',
    'desc' => 'Convert your Microsoft Word documents (DOCX) into high-quality PDF files.',
    'icon' => '📝',
    'action' => '/word-to-pdf',
    'multiple' => false,
    'accept' => '.docx',
    'btnText' => 'Convert to PDF',
    'gradient' => 'from-blue-500 to-cyan-500',
    'toolType' => 'word-to-pdf'
])