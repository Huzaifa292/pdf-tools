@include('tools._upload_form', [
    'title' => 'JPG to PDF',
    'desc' => 'Convert your JPG or PNG image files into a single PDF document.',
    'icon' => '🖼️',
    'action' => '/jpg-to-pdf',
    'multiple' => true,
    'accept' => '.jpg,.jpeg,.png',
    'btnText' => 'Convert to PDF',
    'gradient' => 'from-pink-500 to-rose-500',
    'toolType' => 'jpg-to-pdf'
])