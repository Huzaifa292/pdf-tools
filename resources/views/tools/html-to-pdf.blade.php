<x-app-layout>
<x-slot name="title">HTML to PDF - PDFTools</x-slot>
<div class="max-w-2xl mx-auto text-center py-10">
    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-xl">🌐</div>
    <h1 class="text-4xl font-extrabold text-white">HTML to PDF</h1>
    <p class="text-slate-400 mt-3 text-lg">Convert any webpage URL to a PDF file.</p>
    @if(session('error'))<div class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-xl text-red-400 text-sm">{{ session('error') }}</div>@endif
    <form method="POST" action="/html-to-pdf" class="mt-8">
        @csrf
        <div class="text-left">
            <label class="text-slate-300 text-sm font-medium">Enter Webpage URL</label>
            <input type="url" name="url" placeholder="https://example.com" required
                   class="mt-2 w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-4 text-white placeholder-slate-500 focus:border-red-400 outline-none text-lg">
        </div>
        <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 rounded-2xl font-bold text-white text-lg transition">Convert to PDF ↓</button>
    </form>
    <p class="text-slate-600 text-xs mt-4">🔒 Files are deleted automatically after processing</p>
</div>
</x-app-layout>