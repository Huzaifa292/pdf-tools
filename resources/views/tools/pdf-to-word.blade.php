@include('tools._upload_form', [
    'title' => 'PDF to Word',
    'desc' => 'Convert and extract editable text from your PDF document into DOCX format.',
    'icon' => '📄',
    'action' => '/pdf-to-word',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Convert to Word',
    'gradient' => 'from-blue-600 to-indigo-500',
    'toolType' => 'pdf-to-word'
])