@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Watermark Text</label>
    <input type="text" name="text" id="watermark-text-input" placeholder="e.g. CONFIDENTIAL" maxlength="35" required
           class="w-full neu-input font-bold text-slate-200">
    <p class="text-xxs text-slate-500 mt-1">Type watermark text. Move the watermark visually on the preview panel.</p>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Watermark PDF',
    'desc' => 'Stamp a text watermark dynamically across PDF pages.',
    'icon' => '💧',
    'action' => '/watermark-pdf',
    'multiple' => true,
    'accept' => '.pdf',
    'btnText' => 'Add Watermark',
    'gradient' => 'from-teal-500 to-cyan-500',
    'extraFields' => $extraFields,
    'toolType' => 'watermark'
])