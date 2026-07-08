@include('tools._upload_form', [
    'title' => 'Excel to PDF',
    'desc' => 'Convert your Microsoft Excel spreadsheets into high-quality PDF files.',
    'icon' => '📈',
    'action' => '/excel-to-pdf',
    'multiple' => false,
    'accept' => '.xlsx,.csv,.xls',
    'btnText' => 'Convert to PDF',
    'gradient' => 'from-emerald-500 to-green-600',
    'toolType' => 'excel-to-pdf'
])