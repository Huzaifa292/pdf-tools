@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Text to Redact</label>
    <input type="text" name="text" placeholder="e.g. John Doe, 123-456-7890" required
           class="w-full neu-input text-slate-200 font-bold">
    <p class="text-xxs text-slate-500 mt-1">Separate multiple words/phrases to search & redact with a comma.</p>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Redact PDF',
    'desc' => 'Permanently search and remove sensitive keywords or text segments from your PDF.',
    'icon' => '⬛',
    'action' => '/redact-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Redact PDF',
    'gradient' => 'from-gray-600 to-slate-500',
    'extraFields' => $extraFields,
    'toolType' => 'redact'
])