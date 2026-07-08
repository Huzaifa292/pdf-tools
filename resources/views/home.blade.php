<x-app-layout>
<x-slot name="title">freepdfdoceditor - Free Online PDF Document Editor</x-slot>

<!-- HERO -->
<section class="text-center py-14" data-aos="fade-down">
    <div class="inline-block bg-red-50 text-red-600 text-sm font-semibold px-4 py-1.5 rounded-full mb-4">
        100% Free • No Signup Required
    </div>
    <h1 class="text-5xl font-extrabold text-gray-900 leading-tight">
        Free PDF Document Editor<br>
        <span class="text-red-500">No Signup Required</span>
    </h1>
    <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto">
        The ultimate free online PDF editor. Merge, split, compress, convert, rotate, and watermark PDFs fast, secure, and 100% free.
    </p>
    <div class="mt-8 flex justify-center gap-3">
        <a href="#tools-grid" class="btn-animate ripple px-8 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base shadow-md transition">
            Explore All PDF Tools ↓
        </a>
    </div>
</section>

<!-- FILTER TABS -->
<div class="flex flex-wrap justify-center gap-2 mb-8" data-aos="fade-up" data-aos-delay="100">
    @foreach(['All', 'Organize PDF', 'Optimize PDF', 'Convert PDF', 'Edit PDF', 'PDF Security'] as $tab)
    <button onclick="filterTools('{{ $tab }}')" id="tab-{{ Str::slug($tab) }}"
        class="tab-btn px-5 py-2 rounded-full text-sm font-semibold border transition
        {{ $tab == 'All' ? 'bg-red-500 text-white border-red-500 shadow-sm' : 'bg-white text-gray-500 border-gray-200 hover:border-red-300 hover:text-red-500' }}">
        {{ $tab }}
    </button>
    @endforeach
</div>

<!-- TOOLS GRID -->
@php
$tools = [
    ['Merge PDF',     '/merge-pdf',     '🗂️', 'Combine PDFs into one file',           'from-red-500 to-pink-500',      'Organize PDF'],
    ['Split PDF',     '/split-pdf',     '✂️', 'Extract pages from your PDF',          'from-orange-500 to-red-500',    'Organize PDF'],
    ['Remove Pages',  '/remove-pages',  '🗑️', 'Delete specific pages from PDF',       'from-yellow-500 to-orange-400', 'Organize PDF'],
    ['Organize PDF',  '/organize-pdf',  '📋', 'Reorder and sort PDF pages',           'from-amber-500 to-yellow-500',  'Organize PDF'],
    ['Compress PDF',  '/compress-pdf',  '🗜️', 'Reduce PDF size, keep quality',        'from-green-500 to-emerald-500', 'Optimize PDF'],
    ['Repair PDF',    '/repair-pdf',    '🔧', 'Fix damaged PDF files',                'from-teal-500 to-green-500',    'Optimize PDF'],
    ['OCR PDF',       '/ocr-pdf',       '🔍', 'Make scanned PDF searchable',          'from-cyan-500 to-teal-500',     'Optimize PDF'],
    ['Word to PDF',   '/word-to-pdf',   '📝', 'Convert Word DOC/DOCX to PDF',        'from-blue-500 to-cyan-500',     'Convert PDF'],
    ['Word to Text',  '/word-to-text',  '📃', 'Extract text from Word file',          'from-sky-500 to-blue-500',      'Convert PDF'],
    ['Excel to PDF',  '/excel-to-pdf',  '📊', 'Convert Excel spreadsheet to PDF',    'from-green-600 to-blue-500',    'Convert PDF'],
    ['PPT to PDF',    '/ppt-to-pdf',    '📑', 'Convert PowerPoint to PDF',           'from-orange-500 to-red-400',    'Convert PDF'],
    ['JPG to PDF',    '/jpg-to-pdf',    '🖼️', 'Convert JPG/PNG images to PDF',      'from-pink-500 to-rose-500',     'Convert PDF'],
    ['HTML to PDF',   '/html-to-pdf',   '🌐', 'Convert webpage URL to PDF',          'from-violet-500 to-purple-500', 'Convert PDF'],
    ['PDF to Word',   '/pdf-to-word',   '📄', 'Convert PDF to editable Word',        'from-blue-600 to-indigo-500',   'Convert PDF'],
    ['PDF to JPG',    '/pdf-to-jpg',    '📸', 'Convert PDF pages to JPG',            'from-rose-500 to-pink-500',     'Convert PDF'],
    ['PDF to Excel',  '/pdf-to-excel',  '📈', 'Extract PDF data to Excel',           'from-emerald-500 to-green-500', 'Convert PDF'],
    ['Rotate PDF',    '/rotate-pdf',    '🔄', 'Rotate PDF pages any angle',          'from-purple-500 to-violet-500', 'Edit PDF'],
    ['Page Numbers',  '/page-numbers',  '🔢', 'Add page numbers to PDF',             'from-indigo-500 to-blue-500',   'Edit PDF'],
    ['Watermark PDF', '/watermark-pdf', '💧', 'Stamp text watermark on PDF',         'from-teal-500 to-cyan-500',     'Edit PDF'],
    ['Edit PDF',      '/edit-pdf',      '✏️', 'Add text and shapes to PDF',         'from-yellow-500 to-amber-400',  'Edit PDF'],
    ['Crop PDF',      '/crop-pdf',      '✂️', 'Crop margins of PDF pages',          'from-lime-500 to-green-400',    'Edit PDF'],
    ['Sign PDF',      '/sign-pdf',      '✍️', 'Electronically sign your PDF',       'from-sky-500 to-blue-400',      'Edit PDF'],
    ['Unlock PDF',    '/unlock-pdf',    '🔓', 'Remove PDF password protection',      'from-pink-500 to-rose-400',     'PDF Security'],
    ['Protect PDF',   '/protect-pdf',   '🔒', 'Encrypt PDF with a password',         'from-slate-500 to-gray-500',    'PDF Security'],
    ['Redact PDF',    '/redact-pdf',    '⬛', 'Remove sensitive text from PDF',      'from-gray-600 to-slate-500',    'PDF Security'],
];
@endphp

<div id="tools-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 pb-16">
    @foreach($tools as $i => $tool)
    <a href="{{ $tool[1] }}"
       data-category="{{ $tool[5] }}"
       data-aos="fade-up"
       data-aos-delay="{{ ($i % 5) * 80 }}"
       class="tool-card group bg-white border border-gray-200 rounded-2xl p-5 flex flex-col items-center text-center hover:border-red-300 transition-all duration-200 cursor-pointer">
        <div class="tool-icon w-14 h-14 rounded-2xl bg-gradient-to-br {{ $tool[4] }} flex items-center justify-center text-2xl mb-3 shadow-sm">
            {{ $tool[2] }}
        </div>
        <h3 class="font-semibold text-gray-800 text-sm leading-tight">{{ $tool[0] }}</h3>
        <p class="text-gray-400 text-xs mt-1 leading-tight">{{ $tool[3] }}</p>
    </a>
    @endforeach
</div>

<!-- FEATURES SECTION -->
<section class="grid md:grid-cols-3 gap-6 py-12 border-t border-gray-200">
    <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="0">
        <div class="text-4xl mb-3"></div>
        <h3 class="text-lg font-bold text-gray-800">Lightning Fast</h3>
        <p class="text-gray-500 mt-2 text-sm">Process your PDFs in seconds. No installation needed.</p>
    </div>
    <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="text-4xl mb-3"></div>
        <h3 class="text-lg font-bold text-gray-800">100% Secure</h3>
        <p class="text-gray-500 mt-2 text-sm">Files are deleted automatically after processing.</p>
    </div>
    <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="200">
        <div class="text-4xl mb-3"></div>
        <h3 class="text-lg font-bold text-gray-800">Always Free</h3>
        <p class="text-gray-500 mt-2 text-sm">All tools are completely free. No hidden charges.</p>
    </div>
</section>

<!-- SCRIPT -->
<script>
function filterTools(category) {
    document.querySelectorAll('#tools-grid a').forEach(card => {
        card.style.display = (category === 'All' || card.dataset.category === category) ? 'flex' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-red-500', 'text-white', 'border-red-500', 'shadow-sm');
        btn.classList.add('bg-white', 'text-gray-500', 'border-gray-200');
    });
    const activeId = 'tab-' + category.toLowerCase().replace(/ /g, '-');
    const el = document.getElementById(activeId);
    if (el) {
        el.classList.remove('bg-white', 'text-gray-500', 'border-gray-200');
        el.classList.add('bg-red-500', 'text-white', 'border-red-500', 'shadow-sm');
    }
}
</script>

</x-app-layout>