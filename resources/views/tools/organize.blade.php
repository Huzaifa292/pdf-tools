@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">New Page Order</label>
    <input type="text" name="order" placeholder="e.g. 3,1,2,4" required
           class="w-full neu-input font-mono text-sm">
    <p class="text-xxs text-slate-500 mt-1">Drag and drop preview thumbnails to visually reorder pages.</p>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Organize PDF',
    'desc' => 'Drag and drop pages to visually sort, reorder, or arrange PDF documents.',
    'icon' => '📋',
    'action' => '/organize-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Organize PDF',
    'gradient' => 'from-amber-500 to-yellow-500',
    'extraFields' => $extraFields,
    'toolType' => 'organize'
])