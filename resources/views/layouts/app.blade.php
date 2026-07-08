<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- JSON-LD Structured Data for Advanced Search Engine Visibility -->
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
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Navbar animation ── */
        nav { animation: slideDown 0.5s ease forwards; }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* ── Tool cards hover ── */
        .tool-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .tool-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.10);
        }
        .tool-card:hover .tool-icon {
            transform: scale(1.15) rotate(-5deg);
        }
        .tool-icon {
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* ── Buttons ── */
        .btn-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-animate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239,68,68,0.35);
        }
        .btn-animate:active {
            transform: translateY(0);
        }

        /* ── Navbar dropdown ── */
        .nav-dropdown { display: none; }
        .nav-group:hover .nav-dropdown {
            display: flex;
            animation: fadeInDown 0.2s ease forwards;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Page fade in ── */
        main {
            animation: fadeUp 0.5s ease forwards;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Upload drop zone pulse ── */
        .drop-zone:hover {
            animation: pulseBorder 1s ease infinite;
        }
        @keyframes pulseBorder {
            0%, 100% { border-color: #f87171; }
            50%       { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.15); }
        }

        /* ── Footer ── */
        footer {
            animation: fadeUp 0.8s ease forwards;
        }

        /* ── Ripple effect on buttons ── */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: scale(0);
            opacity: 1;
            top: 50%; left: 50%;
            margin: -50px 0 0 -50px;
            transition: transform 0.5s, opacity 0.5s;
        }
        .ripple:active::after {
            transform: scale(4);
            opacity: 0;
        }

        /* ── Tab buttons ── */
        .tab-btn {
            transition: all 0.2s ease;
        }
        .tab-btn:hover {
            transform: translateY(-2px);
        }

        /* ── Logo hover ── */
        .logo-animate:hover span:first-child {
            animation: logoBounce 0.4s ease;
        }
        @keyframes logoBounce {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.15); }
        }

        /* ── Spinner for form submit ── */
        .spinner {
            display: inline-block;
            width: 18px; height: 18px;
            border: 3px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ── Mobile Drawer ── */
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

        /* ── Mega Menu Dropdown ── */
        .mega-menu {
            display: none;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            width: 720px;
            left: 50%;
            transform: translateX(-50%);
        }
        .nav-group:hover .mega-menu {
            display: grid;
            animation: fadeInDown 0.2s ease forwards;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

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

<!-- NAVBAR -->
<nav class="w-full px-6 py-3 flex justify-between items-center bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="flex items-center gap-6">
        <a href="/" class="logo-animate text-2xl font-extrabold flex items-center gap-2">
            <img src="/freepdfdoceditorpic.png" alt="Logo" class="w-8 h-8 object-contain">
            <span class="text-gray-800">free</span>
            <span class="bg-red-500 text-white px-2 py-0.5 rounded-lg text-xl btn-animate inline-block">PDF</span>
            <span class="text-gray-800">DocEditor</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-1 text-sm font-medium">
            <div class="relative nav-group">
                <button class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg flex items-center gap-1 transition">
                    All Tools <span class="text-xs">▾</span>
                </button>
                <!-- Categorized Mega Menu Dropdown -->
                <div class="mega-menu absolute top-10 bg-white border border-gray-200 rounded-3xl shadow-2xl p-6 gap-6 z-50">
                    @foreach($toolCategories as $catName => $toolsList)
                    <div class="flex flex-col gap-2">
                        <h4 class="text-[10px] font-extrabold text-red-500 uppercase tracking-widest border-b border-red-50 pb-1 mb-1">{{ $catName }}</h4>
                        @foreach($toolsList as $t)
                        <a href="{{ $t[1] }}" class="flex items-center gap-1.5 text-gray-600 hover:text-red-500 hover:bg-red-50/50 rounded-lg px-2 py-1.5 text-xs font-semibold transition">
                            <span>{{ $t[2] }}</span>
                            <span>{{ $t[0] }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            <a href="/contact" class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">Contact Us</a>
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-3 text-sm">
        <span class="hidden lg:inline-block text-xs font-semibold px-3 py-1.5 bg-red-50 text-red-600 rounded-full border border-red-100">
            🔒 100% Secure • No Signup Required
        </span>
        <a href="/contact#donation"
           class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg text-xs font-bold transition shadow-sm btn-animate">
            ☕ Support Us
        </a>
        
        <!-- Mobile Menu Toggle Button -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" aria-label="Toggle Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
</nav>

<!-- Mobile Menu Drawer Overlay -->
<div id="mobile-menu-drawer" class="fixed inset-0 z-50 flex justify-end" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity" onclick="toggleMobileMenu()"></div>
    
    <!-- Drawer Side Panel -->
    <div id="mobile-drawer-content" class="relative w-80 max-w-full bg-white h-full shadow-2xl p-6 overflow-y-auto flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <a href="/" class="text-lg font-extrabold text-gray-800 flex items-center gap-1">
                    <span>free</span>
                    <span class="bg-red-500 text-white px-1.5 py-0.5 rounded text-sm">PDF</span>
                </a>
                <button onclick="toggleMobileMenu()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition" aria-label="Close Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Categorized Navigation -->
            <div class="space-y-6">
                @foreach($toolCategories as $catName => $toolsList)
                <div>
                    <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">{{ $catName }}</h5>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach($toolsList as $t)
                        <a href="{{ $t[1] }}" onclick="toggleMobileMenu()" class="flex items-center gap-1.5 text-gray-600 hover:text-red-500 px-2 py-1.5 rounded-lg hover:bg-red-50/50 text-[11px] font-medium transition">
                            <span>{{ $t[2] }}</span>
                            <span class="truncate">{{ $t[0] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Drawer Footer Contact & Actions -->
        <div class="mt-8 border-t border-gray-100 pt-6 space-y-3">
            <a href="/contact" onclick="toggleMobileMenu()" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl font-bold text-xs transition border border-gray-100">
                ✉️ Contact Us
            </a>
            <a href="/contact#donation" onclick="toggleMobileMenu()" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-xl font-bold text-xs shadow-sm transition">
                ☕ Support Our Work
            </a>
        </div>
    </div>
</div>

<!-- PAGE CONTENT -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
    {{ $slot }}
</main>

<!-- FOOTER -->
<footer class="bg-white border-t border-gray-200 mt-16 py-10 text-center text-gray-400 text-sm">
    <p class="font-medium text-gray-600" data-aos="fade-up">© {{ date('Y') }} freepdfdoceditor — Free Online PDF Editor</p>
    <div class="mt-2 flex justify-center gap-4 text-xs font-semibold text-red-500" data-aos="fade-up" data-aos-delay="50">
        <a href="/" class="hover:underline">Home</a>
        <span>•</span>
        <a href="/contact" class="hover:underline">Contact Us / Custom Development</a>
        <span>•</span>
        <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157/" target="_blank" rel="noopener" class="hover:underline text-blue-600">💼 LinkedIn</a>
        <span>•</span>
        <a href="/contact#donation" class="hover:underline text-yellow-600">☕ Support Us</a>
    </div>
    <p class="mt-2 text-xs" data-aos="fade-up" data-aos-delay="100">Your files are automatically deleted after processing. 100% secure & private.</p>
</footer>

<!-- AOS Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // AOS initialize
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
    });

    // Form submit pe spinner dikhao
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type=submit]');
            if (btn) {
                // Original text save karo taake baad mein restore kar sakein
                btn.dataset.originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner"></span> Processing...';
                btn.disabled = true;
            }
        });
    });

    // Page load hone pe sab buttons reset karo
    // (download complete hone ya error aane ke baad page wapas aata hai)
    window.addEventListener('pageshow', function(e) {
        // pageshow event tab bhi fire hota hai jab browser back/forward cache se page show kare
        document.querySelectorAll('button[type=submit]').forEach(btn => {
            if (btn.dataset.originalText) {
                btn.innerHTML = btn.dataset.originalText;
                btn.disabled  = false;
                delete btn.dataset.originalText;
            } else if (btn.disabled) {
                // Agar original text save nahi tha to sirf enable karo
                btn.disabled = false;
            }
        });
    });

    // Extra safety: agar page already load ho chuka hai aur button stuck ho
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('button[type=submit]').forEach(btn => {
            btn.disabled = false;
        });
    });

    // Global File Upload Size Limit Interceptor (200MB)
    document.addEventListener('change', function(event) {
        if (event.target && event.target.type === 'file') {
            const files = event.target.files;
            if (!files || files.length === 0) return;

            let totalSize = 0;
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }

            const limit = 200 * 1024 * 1024; // 200MB in bytes
            if (totalSize > limit) {
                // Clear the input value
                event.target.value = '';
                
                // Clear list indicators
                const fileListEl = document.getElementById('file-list');
                if (fileListEl) fileListEl.textContent = '';
                const fnEl = document.getElementById('fn');
                if (fnEl) fnEl.textContent = '';

                // Trigger the custom premium modal
                showUploadLimitModal(totalSize);
            }
        }
    });

    function showUploadLimitModal(bytes) {
        const mb = (bytes / (1024 * 1024)).toFixed(1) + 'MB';
        document.getElementById('upload-limit-size').textContent = mb;
        
        const modal = document.getElementById('upload-limit-modal');
        const card = document.getElementById('upload-limit-card');
        
        modal.classList.remove('hidden');
        // Force reflow
        void modal.offsetWidth;
        card.classList.remove('translate-y-4', 'opacity-0');
    }
    window.closeUploadLimitModal = function() {
        const modal = document.getElementById('upload-limit-modal');
        const card = document.getElementById('upload-limit-card');
        
        card.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Toggle Mobile Navigation Drawer
    window.toggleMobileMenu = function() {
        const drawer = document.getElementById('mobile-menu-drawer');
        if (!drawer) return;
        if (drawer.classList.contains('active')) {
            drawer.classList.remove('active');
        } else {
            drawer.classList.add('active');
        }
    }
</script>

<!-- PREMIUM UPLOAD LIMIT MODAL -->
<div id="upload-limit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="window.closeUploadLimitModal()"></div>
    <!-- Content Card -->
    <div class="relative bg-white rounded-3xl p-8 max-w-lg w-full mx-4 shadow-2xl border border-gray-100 transform translate-y-4 opacity-0 transition-all duration-300 ease-out" id="upload-limit-card">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 border border-red-100">
                ⚡
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Increase Upload Limit</h3>
            <p class="text-sm text-gray-500 mt-2">
                Your selected files total <span id="upload-limit-size" class="font-bold text-red-500"></span>, which exceeds our free tier limit of <span class="font-bold text-gray-800">200MB</span>.
            </p>
        </div>

        <hr class="my-6 border-gray-100">

        <!-- Services & Promo Details -->
        <div class="space-y-4">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Contact Developer for Business API & Limits</h4>
            <p class="text-sm text-gray-600 leading-relaxed">
                Need to process large files, lift limits, or integrate secure PDF tooling directly? Contact Dawood Rehman for high-performance API licensing and custom services:
            </p>
            
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-4 border border-red-100 space-y-2 text-xs font-semibold text-gray-700">
                <div class="flex items-center gap-2">🤖 Custom Instagram & WhatsApp Automations</div>
                <div class="flex items-center gap-2">☁️ Secure Cloud Systems & DevOps Solutions</div>
                <div class="flex items-center gap-2">💻 Custom Software & System Automations</div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-3">
            <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-sm transition btn-animate">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                LinkedIn / Automation
            </a>
            <a href="/contact#donation" onclick="window.closeUploadLimitModal()"
               class="flex items-center justify-center gap-1.5 py-3 px-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-xl font-bold text-sm shadow-sm transition btn-animate">
                ☕ Meezan Bank Transfer
            </a>
        </div>
        
        <button onclick="window.closeUploadLimitModal()" class="w-full mt-3 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-500 rounded-xl font-medium text-xs border border-gray-100 transition">
            Close Panel
        </button>
    </div>
</div>

</body>
</html>