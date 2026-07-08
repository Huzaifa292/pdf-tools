@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Encryption Password</label>
    <input type="password" name="password" placeholder="Enter a strong password" required
           class="w-full neu-input">
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Protect PDF',
    'desc' => 'Encrypt and password-protect your PDF files using RC4 encryption.',
    'icon' => '🔒',
    'action' => '/protect-pdf',
    'multiple' => true,
    'accept' => '.pdf',
    'btnText' => 'Protect PDF',
    'gradient' => 'from-slate-500 to-gray-500',
    'extraFields' => $extraFields,
    'toolType' => 'protect'
])