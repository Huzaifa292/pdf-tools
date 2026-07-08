@php
$extraFields = '
<div class="mt-4 grid grid-cols-2 gap-3 text-left">
    <div>
        <label class="block text-xxs font-bold text-slate-400 mb-1.5 uppercase">Margin Top (mm)</label>
        <input type="number" name="top" value="10" min="0" max="100" class="w-full neu-input text-sm font-bold">
    </div>
    <div>
        <label class="block text-xxs font-bold text-slate-400 mb-1.5 uppercase">Margin Bottom (mm)</label>
        <input type="number" name="bottom" value="10" min="0" max="100" class="w-full neu-input text-sm font-bold">
    </div>
    <div>
        <label class="block text-xxs font-bold text-slate-400 mb-1.5 uppercase">Margin Left (mm)</label>
        <input type="number" name="left" value="10" min="0" max="100" class="w-full neu-input text-sm font-bold">
    </div>
    <div>
        <label class="block text-xxs font-bold text-slate-400 mb-1.5 uppercase">Margin Right (mm)</label>
        <input type="number" name="right" value="10" min="0" max="100" class="w-full neu-input text-sm font-bold">
    </div>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Crop PDF',
    'desc' => 'Crop margins and resize pages of your PDF documents easily.',
    'icon' => '✂️',
    'action' => '/crop-pdf',
    'multiple' => false,
    'accept' => '.pdf',
    'btnText' => 'Crop PDF',
    'gradient' => 'from-lime-500 to-green-400',
    'extraFields' => $extraFields,
    'toolType' => 'crop'
])