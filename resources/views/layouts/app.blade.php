<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $currentUrl = request()->path();
        $cleanPath = trim($currentUrl, '/');
        $toolName = $cleanPath ? ucwords(str_replace(['-', 'pdf', 'tool'], [' ', 'PDF', 'Tool'], $cleanPath)) : '';

        // Default SEO values
        $defaultTitle = 'freepdfdoceditor - Free Online PDF Editor (No Limits & 100% Free)';
        $defaultDesc = 'Looking for the best free PDF editor online? freepdfdoceditor is the ultimate free online tool suite to edit, merge, split, compress, and convert PDF documents easily with no registration or signup required!';
        $defaultKeywords = 'freepdfdoceditor, free pdf editor, edit pdf online, merge pdf free, split pdf, compress pdf, convert pdf to word, best pdf editor, edit pdf documents online, ai pdf editor, free pdf editor no login, edit pdf without signup, convert pdf to jpg free, compress pdf size online, combine pdf files free, lock pdf online, unlock pdf files, write on pdf online, best free pdf reader, fill and sign pdf free, how to edit pdf files free';

        if (!empty($title)) {
            $titleOnly = str_replace([' - PDFTools', ' - Smart PDF Tools', ' - isfreepdfdoceditor', ' - freepdfdoceditor'], '', $title);
            $seoTitle = $titleOnly . ' - freepdfdoceditor (Free PDF Editor & AI Alternative)';
        } else {
            $seoTitle = $toolName ? $toolName . ' - freepdfdoceditor (Free PDF Editor & AI Alternative)' : $defaultTitle;
        }

        $seoDesc = isset($metaDescription) ? $metaDescription : ($toolName ? "Use freepdfdoceditor's free online {$toolName} tool. The easiest, fastest, and most secure free online PDF tool. 100% free with no registration or signup required!" : $defaultDesc);
        $seoKeywords = isset($metaKeywords) ? $metaKeywords : ($toolName ? "freepdfdoceditor, {$toolName}, free {$toolName}, online {$toolName}, best {$toolName}, ai pdf tools, edit pdf free, how to {$cleanPath} online, free tool to {$cleanPath}, {$cleanPath} without email, {$cleanPath} no limits, how to edit pdf files free" : $defaultKeywords);
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:site_name" content="freepdfdoceditor">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoTitle }}">
    <meta property="twitter:description" content="{{ $seoDesc }}">

    <!-- Canonical Link -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/freepdfdoceditorpic.png">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "freepdfdoceditor",
      "operatingSystem": "All",
      "applicationCategory": "BusinessApplication",
      "offers": {
        "@@type": "Offer",
        "price": "0.00",
        "priceCurrency": "USD"
      },
      "description": "{{ $seoDesc }}",
      "browserRequirements": "Requires HTML5 compatible browser",
      "featureList": "Merge PDF, Split PDF, Compress PDF, Convert PDF to Word, Rotate PDF, Protect PDF, Unlock PDF, Watermark PDF, Edit PDF Online",
      "url": "{{ url('/') }}"
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "name": "freepdfdoceditor",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FAQPage",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Is freepdfdoceditor really free?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Yes, freepdfdoceditor is 100% free. You can edit, merge, split, compress, and convert PDF files without paying anything or registering for an account."
          }
        },
        {
          "@@type": "Question",
          "name": "Is freepdfdoceditor really free and secure?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Yes! freepdfdoceditor is completely free, secure, and automatically deletes all processed files from our servers within an hour. No paid subscriptions, no signups, and no credit cards are needed."
          }
        },
        {
          "@@type": "Question",
          "name": "Can I edit PDFs on mobile devices?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Yes, freepdfdoceditor is fully responsive and optimized for mobile, tablet, and desktop devices."
          }
        }
      ]
    }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- PDF.js & PDF-Lib Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

    <style>
        /* Navbar animations */
        nav { animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* Mega menu setup */
        .mega-menu {
            display: none;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            width: 760px;
            left: 50%;
            transform: translateX(-50%);
        }
        .nav-group:hover .mega-menu {
            display: grid;
            animation: fadeInDown 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate(-50%, -10px); }
            to   { opacity: 1; transform: translate(-50%, 0); }
        }

        /* Mobile drawer */
        #mobile-menu-drawer {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #mobile-menu-drawer.active {
            opacity: 1;
            pointer-events: auto;
        }
        #mobile-drawer-content {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #mobile-menu-drawer.active #mobile-drawer-content {
            transform: translateX(0);
        }

        /* Spinner ring animation */
        .spin-ring {
            width: 50px; height: 50px;
            border: 4px solid rgba(251, 191, 36, 0.1);
            border-top-color: var(--royal-gold);
            border-radius: 50%;
            animation: spin 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2.5px solid rgba(15, 23, 42, 0.2);
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }
    </style>
</head>
<body class="neu-bg text-slate-100 min-h-screen flex flex-col antialiased selection:bg-amber-400 selection:text-slate-900">

@php
$toolCategories = [
    'Organize' => [
        ['Merge PDF','/merge-pdf','🗂️'],
        ['Split PDF','/split-pdf','✂️'],
        ['Organize PDF','/organize-pdf','📋'],
        ['Remove Pages','/remove-pages','🗑️'],
    ],
    'Optimize' => [
        ['Compress PDF','/compress-pdf','🗜️'],
        ['Repair PDF','/repair-pdf','🔧'],
        ['OCR PDF','/ocr-pdf','🔍'],
    ],
    'Convert' => [
        ['Word to PDF','/word-to-pdf','📝'],
        ['JPG to PDF','/jpg-to-pdf','🖼️'],
        ['HTML to PDF','/html-to-pdf','🌐'],
        ['PDF to Word','/pdf-to-word','📄'],
        ['PDF to JPG','/pdf-to-jpg','📸'],
        ['PDF to Excel','/pdf-to-excel','📈'],
    ],
    'Security & Edit' => [
        ['Rotate PDF','/rotate-pdf','🔄'],
        ['Watermark PDF','/watermark-pdf','💧'],
        ['Page Numbers','/page-numbers','🔢'],
        ['Sign PDF','/sign-pdf','✍️'],
        ['Unlock PDF','/unlock-pdf','🔓'],
        ['Protect PDF','/protect-pdf','🔒'],
    ]
];
@endphp

<!-- NAVIGATION -->
<nav class="w-full px-6 py-4 flex justify-between items-center bg-[#12172b]/90 backdrop-blur-md border-b border-white/5 sticky top-0 z-50 shadow-lg">
    <div class="flex items-center gap-8">
        <a href="/" class="text-2xl font-extrabold flex items-center gap-2 group">
            <img src="/freepdfdoceditorpic.png" alt="Logo" class="w-8 h-8 object-contain transition-transform duration-300 group-hover:scale-110">
            <span class="text-slate-100 tracking-tight">free<span class="text-amber-400">Doc</span>Editor</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-2 text-sm font-semibold">
            <div class="relative nav-group">
                <button class="px-4 py-2 text-slate-300 hover:text-white rounded-xl hover:bg-white/5 transition flex items-center gap-1">
                    All PDF Tools <span class="text-xs">▾</span>
                </button>
                
                <!-- Megamenu Dropdown -->
                <div class="mega-menu absolute top-12 bg-[#12172b] border border-white/10 rounded-3xl shadow-2xl p-6 gap-6 z-50">
                    @foreach($toolCategories as $catName => $toolsList)
                    <div class="flex flex-col gap-2">
                        <h4 class="text-[10px] font-extrabold text-amber-400 uppercase tracking-widest border-b border-white/5 pb-2 mb-1">{{ $catName }}</h4>
                        @foreach($toolsList as $t)
                        <a href="{{ $t[1] }}" class="flex items-center gap-2 text-slate-300 hover:text-white hover:bg-white/5 rounded-xl px-3 py-2 text-xs transition">
                            <span>{{ $t[2] }}</span>
                            <span class="font-medium">{{ $t[0] }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            <a href="/contact" class="px-4 py-2 text-slate-300 hover:text-white rounded-xl hover:bg-white/5 transition">Contact Development</a>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-4 text-sm font-semibold">
        <span class="hidden lg:inline-flex items-center gap-1.5 px-4 py-2 bg-amber-400/10 border border-amber-400/20 text-amber-400 rounded-full text-xs">
            🔒 100% Private &amp; Secure
        </span>
        <a href="/contact#donation"
           class="hidden sm:flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 rounded-xl text-xs font-bold transition shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">
            ☕ Support Development
        </a>
        
        <!-- Hamburger Toggle -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-slate-300 hover:text-white rounded-xl hover:bg-white/5 transition" aria-label="Toggle Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
</nav>

<!-- MOBILE DRAWER -->
<div id="mobile-menu-drawer" class="fixed inset-0 z-50 flex justify-end" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="toggleMobileMenu()"></div>
    <div id="mobile-drawer-content" class="relative w-80 max-w-full bg-[#12172b] h-full shadow-2xl p-6 overflow-y-auto flex flex-col justify-between border-l border-white/5">
        <div>
            <div class="flex justify-between items-center border-b border-white/5 pb-4 mb-6">
                <a href="/" class="text-xl font-bold flex items-center gap-2">
                    <img src="/freepdfdoceditorpic.png" alt="Logo" class="w-6 h-6">
                    <span class="text-white">free<span class="text-amber-400">PDF</span></span>
                </a>
                <button onclick="toggleMobileMenu()" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition" aria-label="Close Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Items -->
            <div class="space-y-6">
                @foreach($toolCategories as $catName => $toolsList)
                <div>
                    <h5 class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-3">{{ $catName }}</h5>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($toolsList as $t)
                        <a href="{{ $t[1] }}" onclick="toggleMobileMenu()" class="flex items-center gap-1.5 text-slate-300 hover:text-white px-2 py-2 rounded-xl hover:bg-white/5 text-[11px] font-semibold transition truncate">
                            <span>{{ $t[2] }}</span>
                            <span class="truncate">{{ $t[0] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 border-t border-white/5 pt-6 space-y-3">
            <a href="/contact" onclick="toggleMobileMenu()" class="flex items-center justify-center gap-2 py-3 px-4 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold text-xs transition border border-white/5">
                ✉️ Send Feedback
            </a>
            <a href="/contact#donation" onclick="toggleMobileMenu()" class="flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 rounded-xl font-bold text-xs shadow-md transition">
                ☕ Support Us
            </a>
        </div>
    </div>
</div>

<!-- MAIN VIEWPORT -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8 relative">
    {{ $slot }}
</main>

<!-- FOOTER -->
<footer class="bg-[#0b0f19] border-t border-white/5 py-12 text-center text-slate-400 text-sm mt-auto">
    <p class="font-bold text-slate-200" data-aos="fade-up">© {{ date('Y') }} freepdfdoceditor — Free Premium PDF Suite</p>
    <div class="mt-4 flex flex-wrap justify-center gap-4 text-xs font-semibold text-amber-400" data-aos="fade-up" data-aos-delay="50">
        <a href="/" class="hover:underline hover:text-amber-300">Home</a>
        <span>•</span>
        <a href="/contact" class="hover:underline hover:text-amber-300">Custom Development Services</a>
        <span>•</span>
        <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157/" target="_blank" rel="noopener" class="hover:underline text-blue-400 hover:text-blue-300">💼 LinkedIn Professional profile</a>
        <span>•</span>
        <a href="/contact#donation" class="hover:underline text-amber-400 hover:text-amber-300">☕ Meezan Bank donation details</a>
    </div>
    <p class="mt-3 text-xs text-slate-500" data-aos="fade-up" data-aos-delay="100">Files uploaded are securely purged automatically within 60 minutes. Absolutely 100% secure.</p>
</footer>

<!-- SYSTEM SPINNER MODAL -->
<div id="spinner" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[9999] hidden items-center justify-center flex-col gap-4">
    <div class="spin-ring"></div>
    <div id="spinner-text" class="text-amber-400 font-bold text-lg tracking-wider neu-glow-gold">Uploading Documents...</div>
</div>

<!-- UPLOAD LIMIT MODAL -->
<div id="upload-limit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" onclick="window.closeUploadLimitModal()"></div>
    <div class="relative bg-[#12172b] rounded-3xl p-8 max-w-lg w-full mx-4 shadow-2xl border border-white/10 transform translate-y-4 opacity-0 transition-all duration-300 ease-out" id="upload-limit-card">
        <div class="text-center">
            <div class="w-16 h-16 bg-amber-400/10 text-amber-400 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 border border-amber-400/20">
                ⚡
            </div>
            <h3 class="text-2xl font-bold text-slate-100">Upload Limit Reached</h3>
            <p class="text-sm text-slate-400 mt-2">
                Your selected files size totals <span id="upload-limit-size" class="font-bold text-amber-400"></span>, which exceeds our free tier limit of <span class="font-bold text-slate-200">200MB</span>.
            </p>
        </div>

        <hr class="my-6 border-white/5">

        <div class="space-y-4">
            <h4 class="text-xs font-bold text-amber-400 uppercase tracking-wider text-center">Need API Access or Custom Solutions?</h4>
            <p class="text-sm text-slate-300 leading-relaxed">
                If you require large-volume file limits, dedicated server capacity, or would like customized PDF engines hosted under your business domain, contact Dawood Rehman for high-efficiency enterprise software licensing:
            </p>
            
            <div class="bg-slate-900 border border-white/5 rounded-2xl p-4 space-y-2 text-xs font-semibold text-slate-300">
                <div class="flex items-center gap-2">🤖 Custom Business &amp; API Automations</div>
                <div class="flex items-center gap-2">☁️ High-Availability Cloud Server Pipelines (AWS/GCP)</div>
                <div class="flex items-center gap-2">🛡️ Full-Stack Enterprise System Integrations</div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-3">
            <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-sm transition">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                LinkedIn Contact
            </a>
            <a href="/contact#donation" onclick="window.closeUploadLimitModal()"
               class="flex items-center justify-center gap-2 py-3 px-4 bg-amber-400 hover:bg-amber-500 text-slate-950 rounded-xl font-bold text-sm transition">
                ☕ Meezan Donation
            </a>
        </div>
        
        <button onclick="window.closeUploadLimitModal()" class="w-full mt-3 py-3 bg-white/5 hover:bg-white/10 text-slate-300 rounded-xl font-semibold text-xs border border-white/5 transition">
            Dismiss Card
        </button>
    </div>
</div>

<!-- AOS Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true, offset: 60 });

    // Drawer management
    window.toggleMobileMenu = function() {
        const drawer = document.getElementById('mobile-menu-drawer');
        if (!drawer) return;
        drawer.classList.toggle('active');
    };

    // Modal helpers
    window.closeUploadLimitModal = function() {
        const modal = document.getElementById('upload-limit-modal');
        const card = document.getElementById('upload-limit-card');
        card.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    };

    window.showUploadLimitModal = function(bytes) {
        const mb = (bytes / (1024 * 1024)).toFixed(1) + 'MB';
        document.getElementById('upload-limit-size').textContent = mb;
        const modal = document.getElementById('upload-limit-modal');
        const card = document.getElementById('upload-limit-card');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        card.classList.remove('translate-y-4', 'opacity-0');
    };

    // Global Upload Size Interceptor
    document.addEventListener('change', function(event) {
        if (event.target && event.target.type === 'file') {
            const files = event.target.files;
            if (!files || files.length === 0) return;
            let size = 0;
            for (let i = 0; i < files.length; i++) size += files[i].size;
            if (size > 200 * 1024 * 1024) {
                event.target.value = '';
                const fl = document.getElementById('file-list');
                if (fl) fl.textContent = '';
                const fn = document.getElementById('fn');
                if (fn) fn.textContent = '';
                showUploadLimitModal(size);
            }
        }
    });

    // Global AJAX form submit controller
    window.submitFormAjax = async function(formElement) {
        const spinner = document.getElementById('spinner');
        const spinnerText = document.getElementById('spinner-text');
        
        spinner.classList.remove('hidden');
        spinner.classList.add('flex');
        
        spinnerText.textContent = "Uploading Document(s)...";

        try {
            const formData = new FormData(formElement);
            
            // Track upload progress if needed
            spinnerText.textContent = "Processing PDF on Server...";

            const response = await fetch(formElement.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                const errText = await response.text();
                throw new Error("Server failed to process files. Please double check that you uploaded non-corrupt PDF files.");
            }

            spinnerText.textContent = "Generating Download...";
            const blob = await response.blob();
            
            // Extract filename from headers if possible
            const disposition = response.headers.get('content-disposition');
            let filename = 'processed.pdf';
            if (disposition && disposition.indexOf('attachment') !== -1) {
                const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                const matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) { 
                    filename = matches[1].replace(/['"]/g, '');
                }
            } else {
                // Infer from action url slug
                const pathParts = window.location.pathname.split('/');
                const slug = pathParts[pathParts.length - 1] || 'result';
                filename = `${slug}_completed.pdf`;
            }

            const blobUrl = URL.createObjectURL(blob);
            
            // Hide spinner
            spinner.classList.remove('flex');
            spinner.classList.add('hidden');

            // Render download stage inside the tool page
            renderDownloadPanel(blobUrl, filename, blob.size);

        } catch (error) {
            spinner.classList.remove('flex');
            spinner.classList.add('hidden');
            alert("Error: " + error.message);
        }
    };

    function renderDownloadPanel(blobUrl, filename, fileSize) {
        const mainContainer = document.querySelector('main > div');
        if (!mainContainer) return;

        const sizeMB = (fileSize / (1024 * 1024)).toFixed(2) + ' MB';

        // Keep page header but replace form container with neumorphic download page
        const header = mainContainer.querySelector('.text-center');
        const formContainer = mainContainer.querySelector('.bg-white, .neu-card');

        const successHTML = `
            <div class="neu-card p-10 max-w-xl mx-auto text-center" data-aos="zoom-in">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-500 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg neu-glow-gold text-slate-950">✓</div>
                <h2 class="text-3xl font-extrabold text-white mb-2">PDF Task Completed!</h2>
                <p class="text-slate-400 text-sm mb-6">Your file is ready. You can safely download it below.</p>
                
                <div class="neu-card-inset p-4 mb-6 flex flex-col gap-2 text-left">
                    <div class="flex justify-between text-xs font-semibold border-b border-white/5 pb-2">
                        <span class="text-slate-500">File Name</span>
                        <span class="text-white truncate max-w-[240px]">${filename}</span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold pt-1">
                        <span class="text-slate-500">Output Size</span>
                        <span class="text-amber-400">${sizeMB}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button id="main-dl-btn" onclick="triggerDownloadBlob('${blobUrl}', '${filename}')" 
                        class="neu-btn-gold w-full py-4 text-lg font-bold">
                        ⬇️ Download File
                    </button>
                    <a href="${window.location.href}" 
                        class="neu-btn w-full py-4 text-sm font-semibold">
                        🔄 Process Another File
                    </a>
                </div>
            </div>
        `;

        if (formContainer) {
            formContainer.outerHTML = successHTML;
        } else {
            mainContainer.innerHTML = successHTML;
        }
        
        AOS.refresh();
        
        // Auto trigger download
        triggerDownloadBlob(blobUrl, filename);
    }

    window.triggerDownloadBlob = function(url, filename) {
        const btn = document.getElementById('main-dl-btn');
        if (btn) {
            btn.innerHTML = '<span class="spinner"></span> Saving File...';
            btn.disabled = true;
        }

        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        setTimeout(() => {
            if (btn) {
                btn.innerHTML = '✅ Saved Successfully';
                setTimeout(() => {
                    btn.innerHTML = '⬇️ Download File';
                    btn.disabled = false;
                }, 2000);
            }
        }, 1200);
    };
</script>

</body>
</html>