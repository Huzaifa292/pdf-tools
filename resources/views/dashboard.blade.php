<x-app-layout>
<x-slot name="title">Dashboard - PDFTools</x-slot>

<div class="mb-8 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Welcome back {{ auth()->user()->name }} </h1>
        <p class="text-gray-500 mt-1 text-sm">Select any tool below to get started</p>
    </div>
    <div class="hidden md:block text-5xl"></div>
</div>

@php
$tools = [
    ['Merge PDF','/merge-pdf','🗂️','from-red-500 to-pink-500'],
    ['Split PDF','/split-pdf','✂️','from-orange-500 to-red-500'],
    ['Compress PDF','/compress-pdf','🗜️','from-green-500 to-emerald-500'],
    ['Word to PDF','/word-to-pdf','📝','from-blue-500 to-cyan-500'],
    ['JPG to PDF','/jpg-to-pdf','🖼️','from-pink-500 to-rose-500'],
    ['HTML to PDF','/html-to-pdf','🌐','from-violet-500 to-purple-500'],
    ['Rotate PDF','/rotate-pdf','🔄','from-purple-500 to-violet-500'],
    ['Watermark PDF','/watermark-pdf','💧','from-teal-500 to-cyan-500'],
    ['Page Numbers','/page-numbers','🔢','from-indigo-500 to-blue-500'],
    ['Unlock PDF','/unlock-pdf','🔓','from-pink-500 to-rose-500'],
    ['Protect PDF','/protect-pdf','🔒','from-slate-500 to-gray-500'],
    ['PDF to JPG','/pdf-to-jpg','📸','from-rose-500 to-pink-500'],
    ['PDF to Word','/pdf-to-word','📄','from-blue-600 to-indigo-500'],
    ['Excel to PDF','/excel-to-pdf','📊','from-green-600 to-blue-500'],
    ['PPT to PDF','/ppt-to-pdf','📑','from-orange-500 to-red-500'],
    ['Organize PDF','/organize-pdf','📋','from-amber-500 to-yellow-500'],
    ['Edit PDF','/edit-pdf','✏️','from-yellow-500 to-amber-400'],
    ['Crop PDF','/crop-pdf','✂️','from-lime-500 to-green-400'],
    ['Sign PDF','/sign-pdf','✍️','from-sky-500 to-blue-400'],
    ['Redact PDF','/redact-pdf','⬛','from-gray-600 to-slate-500'],
];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
@foreach($tools as $i => $tool)
<a href="{{ $tool[1] }}"
   data-aos="zoom-in"
   data-aos-delay="{{ ($i % 5) * 60 }}"
   class="tool-card group bg-white border border-gray-200 rounded-2xl p-5 flex flex-col items-center text-center hover:border-red-300 transition-all duration-200">
    <div class="tool-icon w-14 h-14 rounded-2xl bg-gradient-to-br {{ $tool[3] }} flex items-center justify-center text-2xl mb-3 shadow-sm">
        {{ $tool[2] }}
    </div>
    <h3 class="font-semibold text-gray-800 text-sm">{{ $tool[0] }}</h3>
</a>
@endforeach
</div>
</x-app-layout>
