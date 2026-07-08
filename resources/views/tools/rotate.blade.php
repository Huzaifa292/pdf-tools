<x-app-layout>
<x-slot name="title">Rotate PDF - PDFTools</x-slot>
<div class="max-w-2xl mx-auto text-center py-10">
    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-500 to-violet-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-xl">🔄</div>
    <h1 class="text-4xl font-extrabold text-white">Rotate PDF</h1>
    <p class="text-slate-400 mt-3 text-lg">Rotate your PDF pages to any angle.</p>
    @if(session('error'))<div class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-xl text-red-400 text-sm">{{ session('error') }}</div>@endif
    <form method="POST" action="/rotate-pdf" enctype="multipart/form-data" class="mt-8">
        @csrf
        <label class="block border-2 border-dashed border-slate-600 hover:border-red-400 rounded-2xl p-10 cursor-pointer transition-colors">
            <input type="file" name="files[]" accept=".pdf" multiple class="hidden" onchange="document.getElementById('fn').textContent='✅ Selected '+this.files.length+' file(s)'" required>
            <div class="text-5xl mb-3">📁</div>
            <p class="text-slate-300 font-semibold">Click to select PDF</p>
            <div id="fn" class="mt-3 text-sm text-green-400"></div>
        </label>
        <div class="mt-4 flex justify-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="angle" value="90" checked class="accent-red-500"><span class="text-white font-semibold">↻ 90°</span></label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="angle" value="180" class="accent-red-500"><span class="text-white font-semibold">↕ 180°</span></label>
            <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="angle" value="270" class="accent-red-500"><span class="text-white font-semibold">↺ 270°</span></label>
        </div>
        <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 rounded-2xl font-bold text-white text-lg transition">Rotate PDF ↓</button>
    </form>
    <p class="text-slate-600 text-xs mt-4">🔒 Files are deleted automatically after processing</p>
</div>
</x-app-layout>