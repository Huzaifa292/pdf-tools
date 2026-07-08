@include('tools._upload_form', [
    'title' => 'Page Numbers',
    'desc' => 'Add sequential page numbers to the bottom center of all PDF pages automatically.',
    'icon' => '🔢',
    'action' => '/page-numbers',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Add Page Numbers',
    'gradient' => 'from-indigo-500 to-blue-500',
    'toolType' => 'page-numbers'
])