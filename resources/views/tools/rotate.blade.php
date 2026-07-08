@php
$extraFields = '
<div class="mt-4 text-left">
    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Rotation Angle</label>
    <div class="flex justify-around bg-slate-900 border border-white/5 p-3 rounded-2xl">
        <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-300">
            <input type="radio" name="angle" value="90" checked class="accent-amber-400" onchange="rotateAllPages(90)"> ↻ 90°
        </label>
        <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-300">
            <input type="radio" name="angle" value="180" class="accent-amber-400" onchange="rotateAllPages(180)"> ↕ 180°
        </label>
        <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-300">
            <input type="radio" name="angle" value="270" class="accent-amber-400" onchange="rotateAllPages(270)"> ↺ 270°
        </label>
    </div>
</div>
';
@endphp

@include('tools._upload_form', [
    'title' => 'Rotate PDF',
    'desc' => 'Rotate one or multiple PDF pages easily. See preview updates in real-time.',
    'icon' => '🔄',
    'action' => '/rotate-pdf',
    'multiple' => true,
    'accept' => '.pdf',
    'btnText' => 'Rotate PDF',
    'gradient' => 'from-purple-500 to-violet-500',
    'extraFields' => $extraFields,
    'toolType' => 'rotate'
])