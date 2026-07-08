@include('tools._upload_form', [
    'title' => 'PDF to Excel',
    'desc' => 'Extract tabular data and text from your PDF into a CSV/Excel spreadsheet.',
    'icon' => '📊',
    'action' => '/pdf-to-excel',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Convert to Excel',
    'gradient' => 'from-green-600 to-emerald-500',
    'toolType' => 'pdf-to-excel'
])