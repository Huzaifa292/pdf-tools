<x-app-layout>
<x-slot name="title">freepdfdoceditor - Free Online Premium PDF Document Editor</x-slot>

<!-- HERO SECTION -->
<section class="text-center py-16" data-aos="fade-down">
    <div class="inline-block bg-amber-400/10 border border-amber-400/20 text-amber-400 text-sm font-bold px-5 py-2 rounded-full mb-6">
        ✨ 100% Free • Unlimited PDF Operations
    </div>
    <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight">
        Free Online Premium<br>
        <span class="bg-gradient-to-r from-amber-300 via-amber-400 to-amber-500 bg-clip-text text-transparent neu-glow-gold">PDF Document Tools</span>
    </h1>
    <p class="text-slate-400 mt-6 text-lg max-w-2xl mx-auto leading-relaxed">
        Edit, merge, split, compress, protect, rotate, and convert PDF documents in your browser. Fast, private, secure, and completely free.
    </p>
    <div class="mt-8">
        <a href="#tools-grid" class="neu-btn-gold px-8 py-4 text-base shadow-xl">
            Explore All PDF Tools ↓
        </a>
    </div>
</section>

<!-- FILTER TABS -->
<div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up" data-aos-delay="100">
    @foreach(['All', 'Organize PDF', 'Optimize PDF', 'Convert PDF', 'Edit PDF', 'PDF Security'] as $tab)
    <button onclick="filterTools('{{ $tab }}')" id="tab-{{ Str::slug($tab) }}"
        class="tab-btn px-6 py-3 rounded-full text-xs font-bold border border-white/5 transition-all duration-300
        {{ $tab == 'All' ? 'bg-[#090d1a] text-amber-400 shadow-[inset_4px_4px_8px_#04050a,inset_-4px_-4px_8px_#151c35]' : 'bg-[#12172b] text-slate-300 hover:text-white shadow-[5px_5px_10px_#05070d,-5px_-5px_10px_#1f2749]' }}">
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
];
@endphp

<div id="tools-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 pb-20">
    @foreach($tools as $i => $tool)
    <a href="{{ $tool[1] }}"
       data-category="{{ $tool[5] }}"
       data-aos="fade-up"
       data-aos-delay="{{ ($i % 6) * 60 }}"
       class="tool-card group neu-card neu-card-hover p-6 flex flex-col items-center text-center cursor-pointer relative overflow-hidden">
        
        <!-- Hover Gradient Background glow -->
        <div class="absolute inset-0 bg-gradient-to-br {{ $tool[4] }} opacity-[0.01] group-hover:opacity-[0.04] transition duration-300"></div>
        
        <div class="w-14 h-14 rounded-2xl bg-[#090d1a] border border-white/5 flex items-center justify-center text-2xl mb-4 transition-transform duration-300 group-hover:scale-110 shadow-[inset_3px_3px_6px_#04050a,inset_-3px_-3px_6px_#151c35]">
            {{ $tool[2] }}
        </div>
        <h3 class="font-bold text-white text-sm tracking-tight leading-tight mb-2">{{ $tool[0] }}</h3>
        <p class="text-slate-400 text-xxs leading-snug">{{ $tool[3] }}</p>
    </a>
    @endforeach
</div>

<!-- FEATURES SECTION -->
<section class="grid md:grid-cols-3 gap-8 py-16 border-t border-white/5">
    <div class="neu-card p-8 text-center" data-aos="fade-up" data-aos-delay="0">
        <div class="text-4xl mb-4">🚀</div>
        <h3 class="text-lg font-bold text-white mb-2">Instant Operations</h3>
        <p class="text-slate-400 text-sm leading-relaxed">No wait times. Upload, modify, and export your files in secondary pipelines locally or server-side.</p>
    </div>
    <div class="neu-card p-8 text-center" data-aos="fade-up" data-aos-delay="100">
        <div class="text-4xl mb-4">🛡️</div>
        <h3 class="text-lg font-bold text-white mb-2">Cryptographic Safety</h3>
        <p class="text-slate-400 text-sm leading-relaxed">All operations are end-to-end sandbox isolated. We erase all file traces dynamically from cache every hour.</p>
    </div>
    <div class="neu-card p-8 text-center" data-aos="fade-up" data-aos-delay="200">
        <div class="text-4xl mb-4">💎</div>
        <h3 class="text-lg font-bold text-white mb-2">Always Zero Limits</h3>
        <p class="text-slate-400 text-sm leading-relaxed">No logins, no daily operation ceilings, no subscriptions. Standard tools are permanently unlocked.</p>
    </div>
</section>

<!-- SCRIPTS -->
<script>
function filterTools(category) {
    document.querySelectorAll('#tools-grid a').forEach(card => {
        card.style.display = (category === 'All' || card.dataset.category === category) ? 'flex' : 'none';
    });
    
    // Reset tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.className = "tab-btn px-6 py-3 rounded-full text-xs font-bold border border-white/5 transition-all duration-300 bg-[#12172b] text-slate-300 hover:text-white shadow-[5px_5px_10px_#05070d,-5px_-5px_10px_#1f2749]";
    });
    
    // Activate clicked tab button with inset neumorphism
    const activeId = 'tab-' + category.toLowerCase().replace(/ /g, '-');
    const el = document.getElementById(activeId);
    if (el) {
        el.className = "tab-btn px-6 py-3 rounded-full text-xs font-bold border border-white/5 transition-all duration-300 bg-[#090d1a] text-amber-400 shadow-[inset_4px_4px_8px_#04050a,inset_-4px_-4px_8px_#151c35]";
    }
}
</script>
</x-app-layout>