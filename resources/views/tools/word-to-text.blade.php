<x-app-layout>
<x-slot name="title">Word to Text - PDFTools</x-slot>
<div class="max-w-2xl mx-auto py-10">
    <div class="text-center mb-8" data-aos="fade-down">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-sky-500 to-blue-500 flex items-center justify-center text-4xl mx-auto mb-5 shadow-lg">📃</div>
        <h1 class="text-3xl font-extrabold text-gray-900">Word to Text</h1>
        <p class="text-gray-500 mt-2">Extract plain text from Word DOC/DOCX files.</p>
    </div>

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm" data-aos="fade-up">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    @if(session('extracted_text'))
    <!-- Text nikal aaya — dikhao aur download do -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-6" data-aos="fade-up">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold text-gray-800 text-lg">Extracted Text</h2>
            <div class="flex gap-2">
                <button onclick="copyText()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    📋 Copy
                </button>
                <a href="{{ route('word.text.download') }}"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-medium transition">
                    ⬇️ Download .txt
                </a>
            </div>
        </div>
        <textarea id="extracted-text" rows="15"
            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700 text-sm font-mono resize-none focus:outline-none"
            readonly>{{ session('extracted_text') }}</textarea>
        <p class="text-xs text-gray-400 mt-2">
            Total characters: {{ strlen(session('extracted_text')) }} |
            Words: {{ str_word_count(session('extracted_text')) }}
        </p>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8" data-aos="fade-up" data-aos-delay="100">
        <form method="POST" action="/word-to-text" enctype="multipart/form-data" id="upload-form">
            @csrf
            <label for="file-input" class="drop-zone block border-2 border-dashed border-gray-300 hover:border-red-400 bg-gray-50 hover:bg-red-50 rounded-2xl p-10 cursor-pointer transition-all text-center group">
                <input type="file" id="file-input" name="file" accept=".doc,.docx,.txt" class="hidden" onchange="handleFiles(this)" required>
                <div class="text-5xl mb-3">📂</div>
                <p class="text-gray-700 font-semibold group-hover:text-red-600 transition">Click to select Word file</p>
                <p class="text-gray-400 text-sm mt-1">DOC / DOCX / TXT supported</p>
                <div id="file-list" class="mt-3 text-sm text-green-600 font-medium"></div>
            </label>
            <button type="submit" class="mt-6 w-full py-4 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-base transition shadow-sm ripple btn-animate">
                📃 Extract Text
            </button>
        </form>
        <div class="mt-5 flex justify-center gap-6 text-xs text-gray-400">
            <span>🔒 Secure</span>
            <span>🗑️ Auto deleted</span>
            <span>✅ Free</span>
        </div>
    </div>
</div>

<script>
function handleFiles(i) {
    document.getElementById('file-list').textContent = '✅ ' + i.files[0].name;
}
function copyText() {
    const text = document.getElementById('extracted-text');
    text.select();
    document.execCommand('copy');
    event.target.textContent = '✅ Copied!';
    setTimeout(() => event.target.textContent = '📋 Copy', 2000);
}
</script>
</x-app-layout>