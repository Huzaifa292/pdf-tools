@include('tools._upload_form', [
    'title' => 'Unlock PDF',
    'desc' => 'Remove security limits and password restrictions from your PDF document.',
    'icon' => '🔓',
    'action' => '/unlock-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Unlock PDF',
    'gradient' => 'from-pink-500 to-rose-400',
    'toolType' => 'unlock'
])