<x-app-layout>
<x-slot name="title">Word to PDF - PDFTools</x-slot>
<div class="max-w-2xl mx-auto text-center py-10">
    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-xl">📝</div>
    <h1 class="text-4xl font-extrabold text-white">Word to PDF</h1>
    <p class="text-slate-400 mt-3 text-lg">Convert Word DOC/DOCX/TXT files to PDF.</p>
    @if(session('error'))<div class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-xl text-red-400 text-sm">{{ session('error') }}</div>@endif
    <form method="POST" action="/word-to-pdf" enctype="multipart/form-data" class="mt-8">
        @csrf
        <label class="block border-2 border-dashed border-slate-600 hover:border-red-400 rounded-2xl p-10 cursor-pointer transition-colors">
            <input type="file" name="file" accept=".doc,.docx,.txt" class="hidden" onchange="document.getElementById('fn').textContent='✅ '+this.files[0].name" required>
            <div class="text-5xl mb-3">📁</div>
            <p class="text-slate-300 font-semibold">Click to select DOC / DOCX / TXT</p>
            <div id="fn" class="mt-3 text-sm text-green-400"></div>
        </label>
        <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 rounded-2xl font-bold text-white text-lg transition">Convert to PDF ↓</button>
    </form>
    <p class="text-slate-600 text-xs mt-4">🔒 Files are deleted automatically after processing</p>
</div>
</x-app-layout>