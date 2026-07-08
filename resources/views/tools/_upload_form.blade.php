<x-app-layout>
<x-slot name="title">{{ $title }} - freepdfdoceditor</x-slot>

<div class="max-w-6xl mx-auto py-8">
    <!-- Header -->
    <div class="text-center mb-8" data-aos="fade-down">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br {{ $gradient ?? 'from-amber-400 to-amber-500' }} flex items-center justify-center text-4xl mx-auto mb-4 shadow-lg neu-glow-gold">
            {{ $icon }}
        </div>
        <h1 class="text-4xl font-extrabold text-white leading-tight">{{ $title }}</h1>
        <p class="text-slate-400 mt-2 text-base max-w-xl mx-auto">{{ $desc }}</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm max-w-xl mx-auto" data-aos="fade-up">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-8 items-start">
        
        <!-- Form and Upload Controls (Left Column) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="neu-card p-6" data-aos="fade-up" data-aos-delay="50">
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="upload-form" onsubmit="event.preventDefault(); submitFormAjax(this);">
                    @csrf
                    
                    <!-- Drop Zone -->
                    <label id="dropzone" class="block neu-card-inset hover:border-amber-400/40 p-8 cursor-pointer text-center group border border-transparent transition-all duration-300">
                        <input type="file" id="file-input"
                            name="{{ isset($multiple) && $multiple ? 'files[]' : 'file' }}"
                            accept="{{ $accept ?? '.pdf' }}"
                            {{ isset($multiple) && $multiple ? 'multiple' : '' }}
                            class="hidden" onchange="loadUploadedFiles(this)" required>
                        <div class="text-5xl mb-4 group-hover:scale-110 transition duration-300">📂</div>
                        <p class="text-slate-200 font-bold group-hover:text-amber-400 transition">Click to browse or drop files</p>
                        <p class="text-slate-500 text-xs mt-2">{{ $accept ?? 'PDF' }} documents supported (Max 200MB)</p>
                        <div id="file-list" class="mt-3 text-xs text-amber-400 font-semibold truncate max-w-full"></div>
                    </label>

                    <!-- Extra dynamic options slot -->
                    <div id="extra-options-container" class="mt-6 space-y-4">
                        @isset($extraFields)
                            {!! $extraFields !!}
                        @endisset
                    </div>

                    <!-- Submit Action Button -->
                    <button type="submit" id="submit-btn" class="mt-6 w-full py-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-bold text-base rounded-2xl transition shadow-lg transform hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98]">
                         🚀 {{ $btnText ?? $title }}
                    </button>
                </form>
                
                <div class="mt-5 flex justify-center gap-6 text-xs font-semibold text-slate-500">
                    <span>🔒 Private</span>
                    <span>🗑️ Autopurge</span>
                    <span>⚡ No Limits</span>
                </div>
            </div>
        </div>

        <!-- PDF Previewer Panel (Right Column, expands when files are selected) -->
        <div class="lg:col-span-7">
            <!-- Placeholder -->
            <div id="previewer-placeholder" class="neu-card p-12 text-center text-slate-500 border border-white/5 flex flex-col items-center justify-center min-h-[400px]" data-aos="fade-up" data-aos-delay="100">
                <div class="text-6xl mb-4 opacity-40">👁️‍🗨️</div>
                <h3 class="text-lg font-bold text-slate-400 mb-1">Interactive PDF Preview</h3>
                <p class="text-xs max-w-xs leading-relaxed">Select or drop a PDF file to activate the live viewer and visual layout controls.</p>
            </div>

            <!-- Main Interactive Preview Interface (Hidden by Default) -->
            <div id="previewer-container" class="neu-card p-6 hidden flex-col border border-white/5 min-h-[500px]" data-aos="fade-up" data-aos-delay="100">
                <!-- Preview Header -->
                <div class="flex justify-between items-center border-b border-white/5 pb-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-400 text-lg">📄</span>
                        <h3 class="text-sm font-bold text-white truncate max-w-[240px]" id="preview-filename">document.pdf</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="changeZoom(-0.2)" class="neu-btn p-2 text-xs w-8 h-8">−</button>
                        <span id="zoom-text" class="text-xs text-slate-400 w-10 text-center font-bold">100%</span>
                        <button onclick="changeZoom(0.2)" class="neu-btn p-2 text-xs w-8 h-8">+</button>
                    </div>
                </div>

                <!-- Preview Grid Area -->
                <div class="grid grid-cols-12 gap-4 flex-1 items-stretch min-h-[350px]">
                    <!-- Thumbnails scroll bar -->
                    <div class="col-span-3 neu-card-inset p-3 overflow-y-auto max-h-[420px] flex flex-col gap-3" id="preview-thumbnails">
                        <!-- Thumbs dynamically injected here -->
                    </div>

                    <!-- Canvas Renders main viewport -->
                    <div class="col-span-9 neu-card-inset p-4 relative flex items-center justify-center overflow-auto max-h-[420px] bg-slate-950">
                        <div class="relative shadow-2xl" id="canvas-wrapper">
                            <canvas id="preview-canvas" class="max-w-full block"></canvas>
                            <!-- Watermark visual helper placement block -->
                            <div id="watermark-drag-helper">WATERMARK PREVIEW</div>
                        </div>
                    </div>
                </div>

                <!-- Preview Footer / Pagination -->
                <div class="flex justify-between items-center border-t border-white/5 pt-4 mt-4">
                    <button onclick="navigatePage(-1)" id="btn-prev-page" class="neu-btn px-4 py-2 text-xs">◀ Prev</button>
                    <span id="page-num-indicator" class="text-xs text-slate-400 font-bold">Page 1 of 1</span>
                    <button onclick="navigatePage(1)" id="btn-next-page" class="neu-btn px-4 py-2 text-xs">Next ▶</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- PREVIEWER JS CORE ENGINE -->
<script>
    let currentPdf = null;
    let pdfPagesTotal = 0;
    let pdfPageNum = 1;
    let pdfZoom = 1.0;
    let loadedFilesArray = [];
    
    // Tools options tracking states
    let selectedPagesMap = new Map(); // For page selections (Split/Remove tools)
    let deletedPagesSet = new Set();  // For Remove tool page deletions
    let pagesOrderArray = [];         // For Organize tool page reordering
    let pagesRotationMap = new Map(); // For Rotate tool page rotations

    const toolType = "{{ $toolType ?? 'generic' }}";

    // Handle uploaded file changes
    async function loadUploadedFiles(input) {
        const fileList = document.getElementById('file-list');
        const previewPlaceholder = document.getElementById('previewer-placeholder');
        const previewContainer = document.getElementById('previewer-container');
        
        if (!input.files || input.files.length === 0) return;
        
        loadedFilesArray = Array.from(input.files);
        fileList.innerHTML = `✅ Selected ${loadedFilesArray.length} file(s): ` + loadedFilesArray.map(f => f.name).join(', ');

        // Reset state
        selectedPagesMap.clear();
        deletedPagesSet.clear();
        pagesOrderArray = [];
        pagesRotationMap.clear();

        // Get first PDF file to render
        const firstPdfFile = loadedFilesArray.find(f => f.name.toLowerCase().endsWith('.pdf'));
        
        if (firstPdfFile) {
            previewPlaceholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex');
            
            document.getElementById('preview-filename').textContent = firstPdfFile.name;
            
            try {
                const arrayBuffer = await firstPdfFile.arrayBuffer();
                currentPdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                pdfPagesTotal = currentPdf.numPages;
                pdfPageNum = 1;
                
                // Initialize tools configurations arrays
                for (let i = 1; i <= pdfPagesTotal; i++) {
                    selectedPagesMap.set(i, true); // default all checked
                    pagesOrderArray.push(i);
                    pagesRotationMap.set(i, 0); // default 0 angle
                }
                
                await renderPreviewPage(pdfPageNum);
                await generateThumbnails();
                
            } catch (error) {
                console.error("PDF loading error:", error);
                alert("Failed to render PDF preview: " + error.message);
            }
        } else {
            // Keep placeholder for non-PDF files
            previewPlaceholder.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            previewContainer.classList.remove('flex');
        }
    }

    // Render large preview canvas
    async function renderPreviewPage(num) {
        if (!currentPdf) return;
        pdfPageNum = num;
        
        document.getElementById('page-num-indicator').textContent = `Page ${num} of ${pdfPagesTotal}`;
        document.getElementById('btn-prev-page').disabled = (num <= 1);
        document.getElementById('btn-next-page').disabled = (num >= pdfPagesTotal);

        const page = await currentPdf.getPage(num);
        const viewport = page.getViewport({ scale: pdfZoom });
        
        const canvas = document.getElementById('preview-canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Apply visual rotation from map
        const currentAngle = pagesRotationMap.get(num) || 0;
        canvas.style.transform = `rotate(${currentAngle}deg)`;
        canvas.style.transition = "transform 0.3s ease";

        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;

        // Tool specific hooks inside previewer
        applyToolCanvasOverlay(num, viewport);
    }

    // Generate thumbnails sidebar list
    async function generateThumbnails() {
        const container = document.getElementById('preview-thumbnails');
        container.innerHTML = '';

        for (let i = 1; i <= pdfPagesTotal; i++) {
            const page = await currentPdf.getPage(i);
            const viewport = page.getViewport({ scale: 0.15 });

            const thumbWrapper = document.createElement('div');
            thumbWrapper.className = `neu-card-inset p-1 text-center cursor-pointer transition relative group draggable-thumbnail`;
            thumbWrapper.dataset.page = i;
            thumbWrapper.id = `thumb-page-${i}`;
            thumbWrapper.setAttribute('draggable', toolType === 'organize' ? 'true' : 'false');
            
            // Highlight active view page
            if (i === pdfPageNum) {
                thumbWrapper.classList.add('border-amber-400/50');
            }

            const canvas = document.createElement('canvas');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.className = 'mx-auto rounded block max-w-full';
            
            const context = canvas.getContext('2d');
            await page.render({
                canvasContext: context,
                viewport: viewport
            }).promise;

            const label = document.createElement('div');
            label.className = 'text-[9px] font-bold mt-1 text-slate-400';
            label.textContent = `P. ${i}`;

            thumbWrapper.appendChild(canvas);
            thumbWrapper.appendChild(label);
            
            // Delete Overlay icon for Remove Pages tool
            if (toolType === 'remove-pages') {
                const delIcon = document.createElement('div');
                delIcon.className = 'absolute inset-0 bg-red-600/30 text-white font-extrabold text-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-xl';
                delIcon.innerHTML = '❌ Delete';
                thumbWrapper.appendChild(delIcon);
            }

            // Click listener
            thumbWrapper.onclick = (e) => {
                // If reordering drag, skip simple click triggers
                if (toolType === 'organize') {
                    renderPreviewPage(i);
                    updateThumbnailActiveBorder(i);
                    return;
                }
                
                if (toolType === 'split') {
                    // Toggle selection status
                    const isSelected = selectedPagesMap.get(i);
                    selectedPagesMap.set(i, !isSelected);
                    thumbWrapper.style.opacity = !isSelected ? '1' : '0.4';
                    updatePagesInput();
                } else if (toolType === 'remove-pages') {
                    // Toggle deletion status
                    if (deletedPagesSet.has(i)) {
                        deletedPagesSet.delete(i);
                        thumbWrapper.classList.remove('border-red-500/50');
                        thumbWrapper.querySelector('div').style.opacity = '0';
                        thumbWrapper.querySelector('div').innerHTML = '❌ Delete';
                    } else {
                        deletedPagesSet.add(i);
                        thumbWrapper.classList.add('border-red-500/50');
                        thumbWrapper.querySelector('div').style.opacity = '1';
                        thumbWrapper.querySelector('div').innerHTML = '✅ Restore';
                    }
                    updateRemovePagesInput();
                }

                renderPreviewPage(i);
                updateThumbnailActiveBorder(i);
            };

            // Drag reorder logic for Organize PDF tool
            if (toolType === 'organize') {
                thumbWrapper.addEventListener('dragstart', handleDragStart);
                thumbWrapper.addEventListener('dragover', handleDragOver);
                thumbWrapper.addEventListener('drop', handleDrop);
                thumbWrapper.addEventListener('dragend', handleDragEnd);
            }

            container.appendChild(thumbWrapper);
            
            // Set initial state styling
            if (toolType === 'split' && !selectedPagesMap.get(i)) {
                thumbWrapper.style.opacity = '0.4';
            }
        }
    }

    function updateThumbnailActiveBorder(activeNum) {
        document.querySelectorAll('.draggable-thumbnail').forEach(el => {
            el.classList.remove('border-amber-400/50');
        });
        const activeThumb = document.getElementById(`thumb-page-${activeNum}`);
        if (activeThumb) activeThumb.classList.add('border-amber-400/50');
    }

    // Navigate preview pages
    function navigatePage(direction) {
        const nextNum = pdfPageNum + direction;
        if (nextNum >= 1 && nextNum <= pdfPagesTotal) {
            renderPreviewPage(nextNum);
            updateThumbnailActiveBorder(nextNum);
        }
    }

    // Zoom controls
    function changeZoom(delta) {
        pdfZoom = Math.min(Math.max(pdfZoom + delta, 0.4), 2.5);
        document.getElementById('zoom-text').textContent = `${Math.round(pdfZoom * 100)}%`;
        renderPreviewPage(pdfPageNum);
    }

    // Drag-and-drop page variables
    let dragSourceElement = null;

    function handleDragStart(e) {
        this.style.opacity = '0.4';
        dragSourceElement = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        e.dataTransfer.setData('page-num', this.dataset.page);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        this.classList.add('drag-over');
        return false;
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        if (dragSourceElement !== this) {
            const dragPage = parseInt(e.dataTransfer.getData('page-num'));
            const dropPage = parseInt(this.dataset.page);

            // Reorder inside array
            const dragIdx = pagesOrderArray.indexOf(dragPage);
            const dropIdx = pagesOrderArray.indexOf(dropPage);

            pagesOrderArray.splice(dragIdx, 1);
            pagesOrderArray.splice(dropIdx, 0, dragPage);

            // Rebuild thumbnails container based on the order
            rebuildOrderedThumbnails();
            updateOrganizePagesInput();
        }
        return false;
    }

    function handleDragEnd(e) {
        this.style.opacity = '1';
        document.querySelectorAll('.draggable-thumbnail').forEach(el => {
            el.classList.remove('drag-over');
        });
    }

    function rebuildOrderedThumbnails() {
        const container = document.getElementById('preview-thumbnails');
        const thumbs = Array.from(container.children);
        
        // Sort elements based on pageOrderArray index
        thumbs.sort((a, b) => {
            const aPage = parseInt(a.dataset.page);
            const bPage = parseInt(b.dataset.page);
            return pagesOrderArray.indexOf(aPage) - pagesOrderArray.indexOf(bPage);
        });

        container.innerHTML = '';
        thumbs.forEach(thumb => {
            container.appendChild(thumb);
        });
    }

    // Form inputs synchronizations
    function updatePagesInput() {
        const inputPages = document.querySelector('input[name="pages"]');
        if (!inputPages) return;
        
        const selected = [];
        for (let i = 1; i <= pdfPagesTotal; i++) {
            if (selectedPagesMap.get(i)) {
                selected.push(i);
            }
        }
        
        // Format as list e.g. 1,2,4,5
        inputPages.value = selected.join(',');
    }

    function updateRemovePagesInput() {
        const inputPages = document.querySelector('input[name="pages"]');
        if (!inputPages) return;
        
        const remaining = [];
        for (let i = 1; i <= pdfPagesTotal; i++) {
            if (!deletedPagesSet.has(i)) {
                remaining.push(i);
            }
        }
        
        inputPages.value = remaining.join(',');
    }

    function updateOrganizePagesInput() {
        const inputOrder = document.querySelector('input[name="order"]');
        if (inputOrder) {
            inputOrder.value = pagesOrderArray.join(',');
        }
    }

    // Apply specific overlays (watermark drag overlay, rotation, etc.)
    function applyToolCanvasOverlay(pageNum, viewport) {
        const dragHelper = document.getElementById('watermark-drag-helper');
        if (!dragHelper) return;

        if (toolType === 'watermark') {
            dragHelper.style.display = 'block';
            
            // Center helper in viewport
            const canvasWrapper = document.getElementById('canvas-wrapper');
            dragHelper.textContent = document.getElementById('watermark-text-input')?.value || "WATERMARK";
            
            // Hook input changes to update live overlay text
            const wmTextInput = document.getElementById('watermark-text-input');
            if (wmTextInput) {
                wmTextInput.oninput = (e) => {
                    dragHelper.textContent = e.target.value || "WATERMARK";
                };
            }

            // Draggable mechanics for positioning
            let isDraggingHelper = false;
            let helperX = 50;
            let helperY = 50;

            dragHelper.onmousedown = (e) => {
                isDraggingHelper = true;
                e.preventDefault();
            };

            document.onmousemove = (e) => {
                if (!isDraggingHelper) return;
                const wrapperRect = canvasWrapper.getBoundingClientRect();
                const x = e.clientX - wrapperRect.left - (dragHelper.offsetWidth / 2);
                const y = e.clientY - wrapperRect.top - (dragHelper.offsetHeight / 2);
                
                // Keep boundary check
                const boundX = Math.max(0, Math.min(x, wrapperRect.width - dragHelper.offsetWidth));
                const boundY = Math.max(0, Math.min(y, wrapperRect.height - dragHelper.offsetHeight));

                dragHelper.style.left = `${boundX}px`;
                dragHelper.style.top = `${boundY}px`;
                
                // Feed coordinate ratios to watermark form hidden inputs (if present)
                const inputX = document.getElementById('watermark-x');
                const inputY = document.getElementById('watermark-y');
                if (inputX && inputY) {
                    inputX.value = Math.round((boundX / wrapperRect.width) * 100);
                    inputY.value = Math.round((boundY / wrapperRect.height) * 100);
                }
            };

            document.onmouseup = () => {
                isDraggingHelper = false;
            };
        } else {
            dragHelper.style.display = 'none';
        }
    }

    // Dynamic rotation triggers
    window.rotateAllPages = function(angle) {
        if (!currentPdf) return;
        
        for (let i = 1; i <= pdfPagesTotal; i++) {
            const currentAngle = pagesRotationMap.get(i) || 0;
            const newAngle = (currentAngle + angle) % 360;
            pagesRotationMap.set(i, newAngle);
        }
        
        // Update angle form input if rotation angle input is active
        const angleRadios = document.querySelectorAll('input[name="angle"]');
        if (angleRadios.length > 0) {
            const firstAngle = pagesRotationMap.get(1);
            angleRadios.forEach(radio => {
                if (parseInt(radio.value) === firstAngle) {
                    radio.checked = true;
                }
            });
        }

        renderPreviewPage(pdfPageNum);
        
        // Sync thumbnail canvases angles visually
        for (let i = 1; i <= pdfPagesTotal; i++) {
            const thumbCanvas = document.querySelector(`#thumb-page-${i} canvas`);
            if (thumbCanvas) {
                const angleDeg = pagesRotationMap.get(i) || 0;
                thumbCanvas.style.transform = `rotate(${angleDeg}deg)`;
                thumbCanvas.style.transition = "transform 0.3s ease";
            }
        }
    };
</script>
</x-app-layout>