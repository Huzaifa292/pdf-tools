@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Pages to extract (e.g. 1,3,5-8)</label>
    <input type="text" name="pages" placeholder="Leave blank for all, or click previews"
           class="w-full neu-input font-mono text-sm">
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Split PDF',
    'desc' => 'Separate page ranges or extract specific pages into individual documents.',
    'icon' => '✂️',
    'action' => '/split-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Split PDF',
    'gradient' => 'from-orange-500 to-red-500',
    'extraFields' => $extraFields,
    'toolType' => 'split'
])