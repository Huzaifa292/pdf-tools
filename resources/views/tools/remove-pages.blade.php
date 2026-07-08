@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Pages to Keep</label>
    <input type="text" name="pages" placeholder="e.g. 1,2,4" required
           class="w-full neu-input font-mono text-sm">
    <p class="text-xxs text-slate-500 mt-1">Click a thumbnail preview to remove that page from output.</p>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Remove Pages',
    'desc' => 'Select and delete specific pages from your PDF documents visually.',
    'icon' => '🗑️',
    'action' => '/remove-pages',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Remove Selected Pages',
    'gradient' => 'from-yellow-500 to-orange-400',
    'extraFields' => $extraFields,
    'toolType' => 'remove-pages'
])