<x-app-layout>
<x-slot name="title">Contact Us - freepdfdoceditor</x-slot>

<section class="max-w-4xl mx-auto py-8" data-aos="fade-up">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-white leading-tight">
            Contact <span class="bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent neu-glow-gold">Our Developer</span>
        </h1>
        <p class="text-slate-400 mt-2 text-base">
            Have feature requests, commercial inquiries, or need custom system integration services? Let's connect.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 items-start">
        
        <!-- Contact Form -->
        <div class="neu-card p-8 border border-white/5">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                ✉️ Send a Message
            </h2>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide" for="name">Your Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full neu-input">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide" for="email">Your Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full neu-input">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide" for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full neu-input">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide" for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full neu-input resize-none"></textarea>
                </div>
                <button type="submit"
                        class="w-full mt-2 py-3.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 rounded-xl font-bold text-sm shadow-md transition transform hover:-translate-y-0.5 active:translate-y-0">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Information & Details -->
        <div class="space-y-6">
            
            <!-- Custom Services Card -->
            <div class="neu-card p-8 border border-white/5 relative overflow-hidden">
                <div class="absolute right-0 top-0 text-7xl translate-x-4 -translate-y-4 opacity-5 select-none pointer-events-none">💻</div>
                <h3 class="text-lg font-bold text-amber-400 mb-3 neu-glow-gold">Custom Dev &amp; Server Setup</h3>
                <p class="text-slate-300 text-sm leading-relaxed mb-4">
                    Looking for customized business automation platforms, enterprise systems, or secure APIs? We deliver premium results:
                </p>
                <ul class="text-xs text-slate-400 space-y-2 mb-6">
                    <li class="flex items-center gap-2">🚀 Custom ERP &amp; Enterprise Dashboards</li>
                    <li class="flex items-center gap-2">☁️ Serverless Scaling &amp; AWS DevOps Management</li>
                    <li class="flex items-center gap-2">🛡️ Strict Security Isolation &amp; File Engine Services</li>
                </ul>
                <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-5 py-3 bg-[#1d4ed8] hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    Connect on LinkedIn
                </a>
            </div>

            <!-- Support & Donation Card -->
            <div id="donation" class="neu-card p-8 border border-white/5 scroll-mt-24">
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    ☕ Support Our Work
                </h3>
                <p class="text-xs text-slate-400 mb-5 leading-relaxed">
                    If you appreciate our free, ad-free PDF toolkit, consider contributing directly to keep our high-capacity servers running securely.
                </p>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between border-b border-white/5 pb-2">
                        <span class="font-bold text-slate-500">Bank Name</span>
                        <span class="font-bold text-slate-200">Meezan Bank Limited</span>
                    </div>
                    <div class="flex justify-between border-b border-white/5 pb-2">
                        <span class="font-bold text-slate-500">Account Title</span>
                        <span class="font-bold text-slate-200">Daud Rehman</span>
                    </div>
                    <div class="flex flex-col gap-1 pt-1">
                        <span class="font-bold text-slate-500">IBAN</span>
                        <span class="font-semibold text-amber-400 text-xxs select-all bg-[#090d1a] border border-white/5 px-3 py-2 rounded-xl font-mono tracking-wider break-all shadow-[inset_2px_2px_4px_#000]">PK66 MEZN 0000 3001 1411 1467</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
</x-app-layout>
