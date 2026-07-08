@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Signature Name</label>
    <input type="text" name="name" placeholder="e.g. John Doe" required
           class="w-full neu-input text-slate-200 font-bold">
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Sign PDF',
    'desc' => 'Add your cryptographic name signature automatically onto a new PDF document page.',
    'icon' => '✍️',
    'action' => '/sign-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Sign PDF',
    'gradient' => 'from-sky-500 to-blue-400',
    'extraFields' => $extraFields,
    'toolType' => 'sign'
])