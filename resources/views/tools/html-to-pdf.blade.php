@include('tools._upload_form', [
    'title' => 'HTML to PDF',
    'desc' => 'Convert HTML files or zip archives with HTML/CSS into a high-quality PDF document.',
    'icon' => '🌐',
    'action' => '/html-to-pdf',
    'multiple' => false,
    'accept' => '.html,.zip',
    'btnText' => 'Convert to PDF',
    'gradient' => 'from-rose-500 to-red-600',
    'toolType' => 'html-to-pdf'
])