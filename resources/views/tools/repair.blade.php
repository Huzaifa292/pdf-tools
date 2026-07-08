@include('tools._upload_form', [
    'title' => 'Repair PDF',
    'desc' => 'Fix structure bugs, index issues, or rebuild minor corruptions in PDF documents.',
    'icon' => '🔧',
    'action' => '/repair-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Repair PDF',
    'gradient' => 'from-teal-500 to-green-500',
    'toolType' => 'repair'
])