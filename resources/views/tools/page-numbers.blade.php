<x-app-layout>
<x-slot name="title">Page Numbers - PDFTools</x-slot>
<div class="max-w-2xl mx-auto text-center py-10">
    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-xl">🔢</div>
    <h1 class="text-4xl font-extrabold text-white">Add Page Numbers</h1>
    <p class="text-slate-400 mt-3 text-lg">Add page numbers to your PDF document.</p>
    @if(session('error'))<div class="mt-4 p-3 bg-red-500/20 border border-red-500 rounded-xl text-red-400 text-sm">{{ session('error') }}</div>@endif
    <form method="POST" action="/page-numbers" enctype="multipart/form-data" class="mt-8">
        @csrf
        <label class="block border-2 border-dashed border-slate-600 hover:border-red-400 rounded-2xl p-10 cursor-pointer transition-colors">
            <input type="file" name="file" accept=".pdf" class="hidden" onchange="document.getElementById('fn').textContent='✅ '+this.files[0].name" required>
            <div class="text-5xl mb-3">📁</div>
            <p class="text-slate-300 font-semibold">Click to select PDF</p>
            <div id="fn" class="mt-3 text-sm text-green-400"></div>
        </label>
        <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 rounded-2xl font-bold text-white text-lg transition">Add Page Numbers ↓</button>
    </form>
    <p class="text-slate-600 text-xs mt-4">🔒 Files are deleted automatically after processing</p>
</div>
</x-app-layout>