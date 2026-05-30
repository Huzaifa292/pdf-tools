<x-app-layout>
<x-slot name="title">Edit PDF - PDFTools</x-slot>

<div class="max-w-6xl mx-auto py-8">

    <!-- Header -->
    <div class="text-center mb-8" data-aos="fade-down">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-yellow-500 to-amber-400 flex items-center justify-center text-4xl mx-auto mb-5 shadow-lg">✏️</div>
        <h1 class="text-3xl font-extrabold text-gray-900">Edit PDF</h1>
        <p class="text-gray-500 mt-2">Upload your PDF, add or edit text, then download.</p>
    </div>

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    <!-- Step 1: Upload -->
    <div id="upload-section" class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 mb-6" data-aos="fade-up">
        <h2 class="font-bold text-gray-800 text-lg mb-4">Step 1 — Upload PDF</h2>
        <label for="pdf-upload" class="drop-zone block border-2 border-dashed border-gray-300 hover:border-red-400 bg-gray-50 hover:bg-red-50 rounded-2xl p-10 cursor-pointer transition-all text-center group">
            <input type="file" id="pdf-upload" accept=".pdf" class="hidden" onchange="loadPdf(this)">
            <div class="text-5xl mb-3">📂</div>
            <p class="text-gray-700 font-semibold group-hover:text-red-600 transition">Click to select PDF</p>
            <p class="text-gray-400 text-sm mt-1">PDF files only</p>
            <div id="file-name" class="mt-3 text-sm text-green-600 font-medium"></div>
        </label>
    </div>

    <!-- Step 2: Editor (hidden until PDF loads) -->
    <div id="editor-section" class="hidden">

        <!-- Toolbar -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 mb-4 flex flex-wrap items-center gap-3" data-aos="fade-up">
            <span class="text-sm font-semibold text-gray-700">Add Text:</span>

            <input type="text" id="text-input" placeholder="Type text here..."
                class="flex-1 min-w-48 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-gray-800 text-sm focus:border-red-400 outline-none transition">

            <select id="font-size" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:border-red-400 outline-none">
                <option value="12">12px</option>
                <option value="16" selected>16px</option>
                <option value="20">20px</option>
                <option value="24">24px</option>
                <option value="32">32px</option>
                <option value="48">48px</option>
            </select>

            <input type="color" id="text-color" value="#000000" title="Text Color"
                class="w-10 h-10 rounded-xl border border-gray-200 cursor-pointer bg-gray-50 p-1">

            <select id="font-family" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:border-red-400 outline-none">
                <option value="Arial">Arial</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Courier New">Courier New</option>
                <option value="Georgia">Georgia</option>
                <option value="Verdana">Verdana</option>
            </select>

            <button onclick="addTextBox()" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition btn-animate ripple">
                ➕ Add Text
            </button>

            <div class="border-l border-gray-200 pl-3 flex gap-2">
                <button onclick="prevPage()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium transition">◀ Prev</button>
                <span class="px-3 py-2 text-sm text-gray-600 font-medium">
                    Page <span id="current-page">1</span> / <span id="total-pages">1</span>
                </span>
                <button onclick="nextPage()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium transition">Next ▶</button>
            </div>

            <button onclick="downloadPdf()" class="ml-auto px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold transition btn-animate ripple shadow-sm">
                ⬇️ Download PDF
            </button>
        </div>

    

        <!-- Canvas Area -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-auto" style="max-height: 80vh;">
            <div id="canvas-container" class="relative inline-block">
                <canvas id="pdf-canvas"></canvas>
                <!-- Text boxes will appear here -->
            </div>
        </div>
    </div>
</div>

<!-- PDF.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

let pdfDoc        = null;
let currentPage   = 1;
let totalPages    = 0;
let scale         = 1.5;
let pdfFile       = null;
let textBoxes     = {}; // { pageNum: [ {id, text, x, y, fontSize, color, font} ] }
let placingText   = false;

// ── Load PDF ──────────────────────────────────────────────
async function loadPdf(input) {
    pdfFile = input.files[0];
    if (!pdfFile) return;
    document.getElementById('file-name').textContent = '✅ ' + pdfFile.name;

    const arrayBuffer = await pdfFile.arrayBuffer();
    pdfDoc = await pdfjsLib.getDocument(arrayBuffer).promise;
    totalPages = pdfDoc.numPages;

    document.getElementById('total-pages').textContent = totalPages;
    document.getElementById('editor-section').classList.remove('hidden');
    document.getElementById('upload-section').classList.add('hidden');

    renderPage(currentPage);
}

// ── Render Page ───────────────────────────────────────────
async function renderPage(num) {
    const page     = await pdfDoc.getPage(num);
    const viewport = page.getViewport({ scale });
    const canvas   = document.getElementById('pdf-canvas');
    const ctx      = canvas.getContext('2d');

    canvas.width  = viewport.width;
    canvas.height = viewport.height;

    await page.render({ canvasContext: ctx, viewport }).promise;

    document.getElementById('current-page').textContent = num;

    // Remove old text boxes from DOM
    document.querySelectorAll('.text-box').forEach(el => el.remove());

    // Re-render text boxes for this page
    const boxes = textBoxes[num] || [];
    boxes.forEach(box => renderTextBox(box));
}

// ── Add Text Box ──────────────────────────────────────────
function addTextBox() {
    const text = document.getElementById('text-input').value.trim();
    if (!text) {
        alert('Please type some text first!');
        return;
    }

    placingText = true;
    document.getElementById('canvas-container').style.cursor = 'crosshair';

    // Show hint
    const hint = document.createElement('div');
    hint.id = 'place-hint';
    hint.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-4 py-2 rounded-xl text-sm z-50 shadow-lg';
    hint.textContent = '👆 Click on the PDF to place your text';
    document.body.appendChild(hint);
}

// ── Canvas Click — Place Text ─────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('canvas-container');

    container.addEventListener('click', function(e) {
        if (!placingText) return;

        const rect = document.getElementById('pdf-canvas').getBoundingClientRect();
        const x    = e.clientX - rect.left;
        const y    = e.clientY - rect.top;

        const box = {
            id:       Date.now(),
            text:     document.getElementById('text-input').value.trim(),
            x,
            y,
            fontSize: parseInt(document.getElementById('font-size').value),
            color:    document.getElementById('text-color').value,
            font:     document.getElementById('font-family').value,
            page:     currentPage,
        };

        if (!textBoxes[currentPage]) textBoxes[currentPage] = [];
        textBoxes[currentPage].push(box);
        renderTextBox(box);

        placingText = false;
        container.style.cursor = 'default';
        document.getElementById('place-hint')?.remove();
        document.getElementById('text-input').value = '';
    });
});

// ── Render Single Text Box ────────────────────────────────
function renderTextBox(box) {
    const container = document.getElementById('canvas-container');

    const div = document.createElement('div');
    div.className   = 'text-box absolute select-none';
    div.id          = 'box-' + box.id;
    div.style.left  = box.x + 'px';
    div.style.top   = box.y + 'px';
    div.style.color      = box.color;
    div.style.fontSize   = box.fontSize + 'px';
    div.style.fontFamily = box.font;
    div.style.cursor     = 'move';
    div.style.userSelect = 'none';
    div.style.padding    = '4px 6px';
    div.style.border     = '1px dashed #f87171';
    div.style.borderRadius = '4px';
    div.style.background = 'rgba(255,255,255,0.7)';
    div.style.whiteSpace = 'nowrap';
    div.style.zIndex     = '10';

    div.innerHTML = `
        <span class="text-content">${box.text}</span>
        <button onclick="deleteBox(${box.id})"
            style="margin-left:6px;color:#ef4444;font-weight:bold;cursor:pointer;background:none;border:none;font-size:14px;">✕</button>
    `;

    // Make draggable
    makeDraggable(div, box);
    container.appendChild(div);
}

// ── Drag ──────────────────────────────────────────────────
function makeDraggable(el, box) {
    let isDragging = false, startX, startY, origX, origY;

    el.addEventListener('mousedown', e => {
        if (e.target.tagName === 'BUTTON') return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        origX  = parseFloat(el.style.left);
        origY  = parseFloat(el.style.top);
        e.preventDefault();
    });

    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        el.style.left = (origX + dx) + 'px';
        el.style.top  = (origY + dy) + 'px';
        box.x = origX + dx;
        box.y = origY + dy;
    });

    document.addEventListener('mouseup', () => { isDragging = false; });
}

// ── Delete Box ────────────────────────────────────────────
function deleteBox(id) {
    document.getElementById('box-' + id)?.remove();
    if (textBoxes[currentPage]) {
        textBoxes[currentPage] = textBoxes[currentPage].filter(b => b.id !== id);
    }
}

// ── Page Navigation ───────────────────────────────────────
function prevPage() {
    if (currentPage <= 1) return;
    currentPage--;
    renderPage(currentPage);
}

function nextPage() {
    if (currentPage >= totalPages) return;
    currentPage++;
    renderPage(currentPage);
}

// ── Download PDF ──────────────────────────────────────────
async function downloadPdf() {
    const { jsPDF } = window.jspdf;

    // Get original PDF bytes
    const arrayBuffer = await pdfFile.arrayBuffer();
    const srcDoc      = await pdfjsLib.getDocument(arrayBuffer).promise;

    const firstPage    = await srcDoc.getPage(1);
    const firstVP      = firstPage.getViewport({ scale: 1 });
    const isLandscape  = firstVP.width > firstVP.height;

    const pdf = new jsPDF({
        orientation: isLandscape ? 'landscape' : 'portrait',
        unit: 'px',
        format: [firstVP.width, firstVP.height],
    });

    for (let p = 1; p <= totalPages; p++) {
        if (p > 1) pdf.addPage([firstVP.width, firstVP.height], isLandscape ? 'landscape' : 'portrait');

        // Render page to canvas
        const page     = await srcDoc.getPage(p);
        const viewport = page.getViewport({ scale: 1 });
        const canvas   = document.createElement('canvas');
        canvas.width   = viewport.width;
        canvas.height  = viewport.height;
        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

        // Add page image
        pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', 0, 0, viewport.width, viewport.height);

        // Add text boxes for this page
        const boxes = textBoxes[p] || [];
        boxes.forEach(box => {
            // Convert from scaled canvas coords to actual PDF coords
            const x = box.x / scale;
            const y = box.y / scale;

            pdf.setFont(box.font === 'Times New Roman' ? 'times' :
                        box.font === 'Courier New'     ? 'courier' : 'helvetica');
            pdf.setFontSize(box.fontSize * 0.75); // px to pt

            // Hex color to RGB
            const r = parseInt(box.color.slice(1,3), 16);
            const g = parseInt(box.color.slice(3,5), 16);
            const b = parseInt(box.color.slice(5,7), 16);
            pdf.setTextColor(r, g, b);

            pdf.text(box.text, x, y + box.fontSize * 0.75);
        });
    }

    pdf.save('edited.pdf');
}
</script>

</x-app-layout>