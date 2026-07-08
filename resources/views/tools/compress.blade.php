@include('tools._upload_form', [
    'title' => 'Compress PDF',
    'desc' => 'Reduce the file size of your PDF documents while keeping maximum quality.',
    'icon' => '🗜️',
    'action' => '/compress-pdf',
    'multiple' => true,
    'accept' => '.pdf',
    'btnText' => 'Compress PDF',
    'gradient' => 'from-green-500 to-emerald-500',
    'toolType' => 'compress'
])