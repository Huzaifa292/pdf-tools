@include('tools._upload_form', [
    'title' => 'Merge PDF',
    'desc' => 'Combine multiple PDF documents into a single file.',
    'icon' => '🗂️',
    'action' => '/merge-pdf',
    'multiple' => true,
    'accept' => '.pdf',
    'btnText' => 'Merge PDF',
    'gradient' => 'from-red-500 to-pink-500',
    'toolType' => 'merge'
])