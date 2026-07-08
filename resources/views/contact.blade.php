<x-app-layout>
<x-slot name="title">Contact Us - freepdfdoceditor</x-slot>

<section class="max-w-4xl mx-auto py-8" data-aos="fade-up">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
            Contact <span class="text-red-500">Us</span>
        </h1>
        <p class="text-gray-500 mt-2 text-lg">
            Have questions, feedback, or need custom solutions? Reach out to us.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                ✉️ Send Message
            </h2>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1" for="name">Your Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1" for="email">Your Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1" for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1" for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition"></textarea>
                </div>
                <button type="submit"
                        class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base shadow-md transition btn-animate ripple">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Information & Details -->
        <div class="space-y-6">
            <!-- Custom Services Card -->
            <div class="bg-gradient-to-br from-red-50 to-pink-50 p-8 rounded-3xl border border-red-100 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 text-7xl translate-x-4 -translate-y-4 opacity-10 select-none">💻</div>
                <h3 class="text-xl font-bold text-red-600 mb-3">Custom Software & Cloud Infrastructure</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    Beyond free PDF tools, we specialize in building highly scalable, modern digital solutions.
                </p>
                <ul class="text-xs text-gray-500 space-y-2 mb-4">
                    <li class="flex items-center gap-2">🚀 Custom Enterprise Web Applications</li>
                    <li class="flex items-center gap-2">☁️ High-Availability Cloud Infrastructure Setup</li>
                    <li class="flex items-center gap-2">🛡️ Cyber Security & Architecture Audits</li>
                </ul>
                <a href="https://www.linkedin.com/in/dawood-rehman-15ab12157/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm btn-animate">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    Connect on LinkedIn
                </a>
            </div>



            <!-- Support & Donation Card -->
            <div id="donation" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl scroll-mt-24">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    ☕ Support Our Work
                </h3>
                <p class="text-xs text-gray-400 mb-4">
                    If you love using freepdfdoceditor, support us with a direct bank transfer contribution.
                </p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="font-medium text-gray-500">Bank Name</span>
                        <span class="font-semibold text-gray-800">Meezan Bank Limited</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="font-medium text-gray-500">Account Title</span>
                        <span class="font-semibold text-gray-800">Daud Rehman</span>
                    </div>
                    <div class="flex flex-col border-b border-gray-100 pb-2">
                        <span class="font-medium text-gray-500 mb-1">IBAN</span>
                        <span class="font-semibold text-gray-800 text-xs select-all bg-gray-50 px-2.5 py-1.5 rounded-lg border border-gray-100 font-mono tracking-wider break-all">PK66 MEZN 0000 3001 1411 1467</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</x-app-layout>
