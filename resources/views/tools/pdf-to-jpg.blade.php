@include('tools._upload_form', [
    'title' => 'PDF to JPG',
    'desc' => 'Convert your PDF pages into high-quality JPG images.',
    'icon' => '🖼️',
    'action' => '/pdf-to-jpg',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Convert to JPG',
    'gradient' => 'from-yellow-500 to-amber-500',
    'toolType' => 'pdf-to-jpg'
])