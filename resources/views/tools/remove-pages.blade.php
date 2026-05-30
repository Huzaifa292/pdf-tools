<x-app-layout>
<x-slot name="title">Remove Pages - PDFTools</x-slot>
<div class="max-w-2xl mx-auto py-10">
    <div class="text-center mb-8">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-yellow-500 to-orange-400 flex items-center justify-center text-4xl mx-auto mb-5 shadow-lg">🗑️</div>
        <h1 class="text-3xl font-extrabold text-gray-900">Remove Pages</h1>
        <p class="text-gray-500 mt-2">Delete specific pages from your PDF file.</p>
    </div>
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">⚠️ {{ session('error') }}</div>
    @endif
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
        <form method="POST" action="/remove-pages" enctype="multipart/form-data" id="upload-form">
            @csrf
            <label for="file-input" class="block border-2 border-dashed border-gray-300 hover:border-red-400 bg-gray-50 hover:bg-red-50 rounded-2xl p-10 cursor-pointer transition-all text-center group">
                <input type="file" id="file-input" name="file" accept=".pdf" class="hidden" onchange="handleFiles(this)" required>
                <div class="text-5xl mb-3">📂</div>
                <p class="text-gray-700 font-semibold group-hover:text-red-600 transition">Click to select PDF</p>
                <p class="text-gray-400 text-sm mt-1">PDF files only</p>
                <div id="file-list" class="mt-3 text-sm text-green-600 font-medium"></div>
            </label>
            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pages to Remove <span class="text-gray-400 font-normal">(e.g. 2,4,6-8)</span></label>
                <input type="text" name="pages" placeholder="e.g. 2,4,6-8" required
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 placeholder-gray-400 focus:border-red-400 outline-none transition">
            </div>
            <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base transition shadow-sm">
                 Remove Pages
            </button>
        </form>
        <div class="mt-5 flex justify-center gap-6 text-xs text-gray-400">
            <span>🔒 Secure</span><span>🗑️ Auto deleted</span><span>✅ Free</span>
        </div>
    </div>
</div>
<script>
function handleFiles(i){ document.getElementById('file-list').textContent = '✅ ' + i.files[0].name; }
document.getElementById('upload-form').addEventListener('submit', function(){
    this.querySelector('button[type=submit]').textContent = '⏳ Processing...';
});
</script>
</x-app-layout>