@include('tools._upload_form', [
    'title' => 'Word to Text',
    'desc' => 'Extract raw text data from Microsoft Word documents instantly.',
    'icon' => '📝',
    'action' => '/word-to-text',
    'multiple' => false,
    'accept' => '.docx',
    'btnText' => 'Extract Text',
    'gradient' => 'from-indigo-500 to-purple-500',
    'toolType' => 'word-to-text'
])