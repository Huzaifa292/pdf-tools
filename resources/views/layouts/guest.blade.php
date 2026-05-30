<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PDFTools') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col items-center justify-center">

    <!-- Top Logo -->
   <a href="/" class="mb-8 text-2xl font-extrabold flex items-center gap-2">
    <span class="text-gray-800">SMART</span>
    <span class="bg-red-500 text-white px-2 py-0.5 rounded-lg text-xl">PDF</span>
    <span class="text-gray-800">Tools</span>
</a>

    {{ $slot }}

    <p class="mt-8 text-xs text-gray-400">© {{ date('Y') }} PDFTools. All rights reserved.</p>
</body>
</html>
