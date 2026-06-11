<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PDFTools - Free Online PDF Editor' }}</title>
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

<!-- NAVBAR -->
<nav class="w-full px-6 py-3 flex justify-between items-center bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <a href="/" class="logo-animate text-2xl font-extrabold flex items-center gap-2">
        <span class="text-gray-800">SMART</span>
        <span class="bg-red-500 text-white px-2 py-0.5 rounded-lg text-xl btn-animate inline-block">PDF</span>
        <span class="text-gray-800">Tools</span>
    </a>

    <div class="hidden md:flex items-center gap-1 text-sm font-medium">
        <div class="relative nav-group">
            <button class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg flex items-center gap-1 transition">
                All Tools <span class="text-xs">▾</span>
            </button>
            <div class="nav-dropdown flex-wrap absolute top-10 left-0 bg-white border border-gray-200 rounded-2xl shadow-xl p-4 w-[480px] gap-1 z-50">
                @php
                $navTools = [
                    ['Merge PDF','/merge-pdf','🗂️'],
                    ['Split PDF','/split-pdf','✂️'],
                    ['Compress PDF','/compress-pdf','🗜️'],
                    ['Word to PDF','/word-to-pdf','📝'],
                    ['JPG to PDF','/jpg-to-pdf','🖼️'],
                    ['Rotate PDF','/rotate-pdf','🔄'],
                    ['Watermark','/watermark-pdf','💧'],
                    ['Page Numbers','/page-numbers','🔢'],
                    ['Unlock PDF','/unlock-pdf','🔓'],
                    ['Protect PDF','/protect-pdf','🔒'],
                    ['HTML to PDF','/html-to-pdf','🌐'],
                    ['Sign PDF','/sign-pdf','✍️'],
                ];
                @endphp
                @foreach($navTools as $t)
                <a href="{{ auth()->check() ? $t[1] : route('register') }}"
                   class="flex items-center gap-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl px-3 py-2 text-xs w-[calc(50%-4px)] transition tool-card">
                    <span class="text-base">{{ $t[2] }}</span>
                    <span class="font-medium">{{ $t[0] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 text-sm">
        @auth
            <span class="text-gray-500 hidden md:block font-medium">{{ auth()->user()->name }}</span>
            <a href="/dashboard" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition btn-animate">Dashboard</a>
            <form method="POST" action="/logout">@csrf
                <button class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition btn-animate ripple">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition btn-animate">Login</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition btn-animate ripple shadow-sm">Sign Up Free</a>
        @endauth
    </div>
</nav>

<!-- PAGE CONTENT -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
    {{ $slot }}
</main>

<!-- FOOTER -->
<footer class="bg-white border-t border-gray-200 mt-16 py-10 text-center text-gray-400 text-sm">
    <p class="font-medium text-gray-600" data-aos="fade-up">© {{ date('Y') }} PDFTools — Free Online PDF Editor</p>
    <p class="mt-1 text-xs" data-aos="fade-up" data-aos-delay="100">Your files are automatically deleted after processing. 100% secure & private.</p>
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
</script>

</body>
</html>