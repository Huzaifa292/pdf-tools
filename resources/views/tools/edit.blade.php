<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit PDF - SMART PDF Tools</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>window.Alpine = { start: () => {} };</script>
    <style>
        html, body {
            margin: 0; padding: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; }

        /* ══════════════════════════════
           UPLOAD SCREEN
        ══════════════════════════════ */
        #upload-screen {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%);
            z-index: 200;
        }
        .upload-box {
            background: #fff;
            border-radius: 24px;
            padding: 48px 52px;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0,0,0,.12);
            max-width: 520px;
            width: 90%;
        }
        .upload-box h2 {
            font-size: 28px;
            font-weight: 800;
            color: #111;
            margin: 12px 0 8px;
        }
        .upload-box p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .upload-drop {
            display: block;
            border: 2.5px dashed #fca5a5;
            border-radius: 16px;
            padding: 36px 24px;
            cursor: pointer;
            transition: all .2s;
            background: #fef2f2;
            text-decoration: none;
        }
        .upload-drop:hover {
            border-color: #ef4444;
            background: #fee2e2;
        }
        .upload-drop .drop-icon { font-size: 40px; line-height: 1; margin-bottom: 10px; }
        .upload-drop .drop-title { font-size: 15px; font-weight: 600; color: #374151; }
        .upload-drop .drop-sub   { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        #fname { font-size: 13px; color: #ef4444; font-weight: 600; margin-top: 8px; min-height: 18px; }
        .btn-open {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            width: 100%;
            padding: 14px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s, transform .15s;
        }
        .btn-open:hover   { background: #dc2626; transform: translateY(-1px); }
        .btn-open:disabled{ opacity: .5; cursor: not-allowed; transform: none; }
        .upload-badges {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 16px;
            font-size: 12px;
            color: #9ca3af;
        }

        /* ══════════════════════════════
           EDITOR PAGE
        ══════════════════════════════ */
        #editor-page {
            display: none;
            flex-direction: column;
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            background: #f5f5f5;
        }

        /* Top Bar */
        #top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            height: 52px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
            gap: 8px;
        }
        .file-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
        }
        .file-name input {
            border: none;
            outline: none;
            font-size: 14px;
            font-weight: 600;
            color: #111;
            background: transparent;
            width: 220px;
            border-bottom: 2px solid transparent;
            font-family: 'Inter', sans-serif;
            transition: border-color .2s;
        }
        .file-name input:focus { border-bottom-color: #ef4444; }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .btn-dl {
            display: flex; align-items: center; gap: 5px;
            padding: 7px 16px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: background .15s;
            font-family: 'Inter', sans-serif;
        }
        .btn-dl:hover { background: #f9fafb; }
        .btn-done {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 22px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
            font-family: 'Inter', sans-serif;
        }
        .btn-done:hover { background: #dc2626; }

        /* Toolbar */
        #toolbar {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 0 12px;
            height: 52px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
            overflow-x: auto;
        }
        .tool-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            padding: 6px 10px;
            min-width: 52px;
            height: 46px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #6b7280;
            font-size: 10px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            white-space: nowrap;
            transition: all .15s;
        }
        .tool-btn:hover  { background: #f3f4f6; color: #111; }
        .tool-btn.active { background: #fef2f2; color: #ef4444; }
        .tool-btn svg    { width: 20px; height: 20px; }
        .tool-sep { width: 1px; height: 32px; background: #e5e7eb; margin: 0 4px; flex-shrink: 0; }

        /* Properties Bar */
        #prop-bar {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 5px 16px;
            min-height: 40px;
            background: #fafafa;
            border-bottom: 1px solid #e5e7eb;
            flex-shrink: 0;
            flex-wrap: wrap;
        }
        #prop-bar.show { display: flex; }
        #prop-bar label { font-size: 12px; color: #6b7280; }
        #prop-bar select,
        #prop-bar input[type=number] {
            height: 28px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0 8px;
            font-size: 12px;
            background: #fff;
            font-family: 'Inter', sans-serif;
        }
        #prop-font-family { width: 130px; }
        #prop-font-size   { width: 52px; }
        .prop-btn {
            display: flex; align-items: center;
            height: 28px; padding: 0 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background .12s;
        }
        .prop-btn:hover { background: #f3f4f6; }
        .prop-btn.on    { background: #ef4444; color: #fff; border-color: #ef4444; }
        input[type=color].cpick {
            width: 28px; height: 28px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 2px;
            cursor: pointer;
        }

        /* Editor Body */
        #editor-body {
            display: flex;
            flex: 1;
            overflow: hidden;
            min-height: 0;
        }

        /* Pages Panel */
        #pages-panel {
            display: none;
            width: 170px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            flex-shrink: 0;
            padding: 10px 8px;
        }
        #pages-panel.show { display: block; }
        #pages-panel .panel-title {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
            padding: 0 4px;
        }
        .page-thumb {
            border: 2px solid transparent;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: pointer;
            overflow: hidden;
            transition: border-color .15s;
        }
        .page-thumb:hover  { border-color: #fca5a5; }
        .page-thumb.active { border-color: #ef4444; }
        .page-thumb canvas { width: 100%; display: block; }
        .page-thumb .pg-num {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            padding: 3px;
            background: #fff;
        }

        /* Canvas Area */
        #canvas-area {
            flex: 1;
            background: #525659;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px 16px 80px;
            position: relative;
            min-height: 0;
        }
        #page-wrap {
            position: relative;
            display: inline-block;
            box-shadow: 0 8px 32px rgba(0,0,0,.4);
            line-height: 0;
        }
        #pdf-canvas { display: block; }

        /* Text Layer */
        #text-layer {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            line-height: 1;
            pointer-events: none;
        }
        #text-layer span {
            position: absolute;
            white-space: pre;
            cursor: text;
            transform-origin: 0% 0%;
            border: 1.5px solid transparent;
            border-radius: 2px;
            outline: none;
            caret-color: #ef4444;
            padding: 0 2px;
        }
        #text-layer span:hover {
            background: rgba(239,68,68,.07);
            border-color: rgba(239,68,68,.3);
        }
        #text-layer span.ed {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
            z-index: 10;
        }

        /* Annotation Layer */
        #ann-layer {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 5;
        }
        .ann-highlight {
            position: absolute;
            background: rgba(250,204,21,.45);
            pointer-events: auto;
            cursor: pointer;
            border-radius: 2px;
        }
        .ann-text {
            position: absolute;
            background: transparent;
            border: 1.5px dashed transparent;
            outline: none;
            cursor: move;
            font-family: Arial, sans-serif;
            color: #000;
            min-width: 60px;
            min-height: 20px;
            pointer-events: auto;
            padding: 2px 4px;
            line-height: 1.3;
        }
        .ann-text:hover    { border-color: rgba(239,68,68,.5); }
        .ann-text.selected { border-color: #ef4444; background: rgba(255,255,255,.92); cursor: text; }

        /* Draw Canvas */
        #draw-canvas {
            position: absolute;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 6;
        }

        /* Bottom Navigation */
        #bottom-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,.75);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 20;
            white-space: nowrap;
        }
        #bottom-nav button {
            background: none;
            border: none;
            color: #d1d5db;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 6px;
            border-radius: 6px;
            transition: background .12s;
            font-family: 'Inter', sans-serif;
        }
        #bottom-nav button:hover    { background: rgba(255,255,255,.15); color: #fff; }
        #bottom-nav button:disabled { opacity: .35; cursor: not-allowed; }
        #page-indicator { color: #d1d5db; font-size: 13px; min-width: 52px; text-align: center; }
        #zoom-lbl { color: #9ca3af; font-size: 12px; min-width: 36px; text-align: center; }
        .nav-sep { width: 1px; height: 18px; background: rgba(255,255,255,.2); margin: 0 2px; }

        /* Spinner */
        #spinner {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
        }
        #spinner.show { display: flex; }
        .spin-ring {
            width: 44px; height: 44px;
            border: 4px solid rgba(255,255,255,.25);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin-text { color: #d1d5db; font-size: 14px; }

        /* Sign Modal */
        #sign-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 9998;
            align-items: center;
            justify-content: center;
        }
        #sign-modal.show { display: flex; }
        .sign-box {
            background: #fff;
            border-radius: 20px;
            padding: 28px 32px;
            width: 440px;
            max-width: 90vw;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }
        .sign-box h3 { font-size: 18px; font-weight: 700; margin-bottom: 6px; color: #111; }
        .sign-box p  { font-size: 13px; color: #6b7280; margin-bottom: 14px; }
        .sign-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 22px;
            font-style: italic;
            font-family: Georgia, serif;
            outline: none;
            transition: border-color .15s;
            color: #1e3a8a;
        }
        .sign-input:focus { border-color: #ef4444; }
        .sign-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            justify-content: flex-end;
        }
        .btn-cancel {
            padding: 8px 18px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }
        .btn-add-sign {
            padding: 8px 22px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background .15s;
        }
        .btn-add-sign:hover { background: #dc2626; }
    </style>
</head>
<body>

<div id="spinner">
    <div class="spin-ring"></div>
    <div class="spin-text">Processing...</div>
</div>

<div id="upload-screen">
    <div class="upload-box">
        <div style="font-size:52px;line-height:1">✏️</div>
        <h2>Edit PDF Online</h2>
        <p>Click any text to edit it directly.<br>Add text, highlight, draw, sign and more.</p>

        <label class="upload-drop" id="dropZone">
            <input type="file" id="fileIn" accept=".pdf" style="display:none">
            <div class="drop-icon">📂</div>
            <div class="drop-title">Drop PDF here or click to browse</div>
            <div class="drop-sub">Maximum file size: 100 MB</div>
            <div id="fname"></div>
        </label>

        <button class="btn-open" id="openBtn" disabled>
            <span>✏️</span> Open &amp; Edit PDF
        </button>

        <div class="upload-badges">
            <span>🔒 100% Secure</span>
            <span>🗑️ Auto deleted</span>
            <span>⚡ Free forever</span>
        </div>
    </div>
</div>

<div id="editor-page">

    <div id="top-bar">
        <div class="file-name">
            <span>📄</span>
            <input type="text" id="file-name-input" value="document.pdf" spellcheck="false">
        </div>
        <div class="top-actions">
            <button class="btn-dl" onclick="downloadPdf()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Download
            </button>
            <button class="btn-done" onclick="downloadPdf()">✓ Done</button>
        </div>
    </div>

    <div id="toolbar">
        <button class="tool-btn" id="tool-pages" onclick="togglePages()" title="Pages">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Pages
        </button>
        <div class="tool-sep"></div>

        <button class="tool-btn active" id="tool-move" onclick="setTool('move')" title="Move">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 9l-3 3 3 3M9 5l3-3 3 3M15 19l-3 3-3-3M19 9l3 3-3 3M12 3v18M3 12h18"/>
            </svg>
            Move
        </button>

        <button class="tool-btn" onclick="undo()" title="Undo">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/>
            </svg>
            Undo
        </button>

        <button class="tool-btn" onclick="redo()" title="Redo">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/>
            </svg>
            Redo
        </button>
        <div class="tool-sep"></div>

        <button class="tool-btn" id="tool-addtext" onclick="setTool('addtext')" title="Add Text">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m-4 0h8M5 8h14"/>
            </svg>
            Add Text
        </button>

        <button class="tool-btn" id="tool-edittext" onclick="setTool('edittext')" title="Edit Text">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Text
        </button>

        <button class="tool-btn" id="tool-eraser" onclick="setTool('eraser')" title="Eraser">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L17.94 6.06a1.5 1.5 0 010 2.12L9 17.06a1.5 1.5 0 01-2.12 0L3 13.12a1.5 1.5 0 010-2.12L9 5m9 13H9"/>
            </svg>
            Eraser
        </button>

        <button class="tool-btn" id="tool-highlight" onclick="setTool('highlight')" title="Highlight">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21l4-4 10-10-4-4L3 17v4z"/>
            </svg>
            Highlight
        </button>

        <button class="tool-btn" id="tool-pencil" onclick="setTool('pencil')" title="Draw">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M3 21l4.5-1.5 10-10-3-3-10 10L3 21z"/>
            </svg>
            Pencil
        </button>
        <div class="tool-sep"></div>

        <button class="tool-btn" id="tool-sign" onclick="openSignModal()" title="Sign">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
            </svg>
            Sign
        </button>
    </div>

    <div id="prop-bar">
    <label>Font:</label>
    <select id="prop-font-family" onchange="applyTextProp()">
        <option>Arial</option>
        <option>Times New Roman</option>
        <option>Courier New</option>
        <option>Georgia</option>
        <option>Verdana</option>
        <option>Helvetica</option>
    </select>
    <label>Size:</label>
    <input type="number" id="prop-font-size" value="14" min="6" max="96"
        onchange="applyTextProp()" oninput="applyTextProp()">
    <div class="tool-sep"></div>
    <button class="prop-btn" id="pbold"   onclick="toggleProp('bold')"><b>B</b></button>
    <button class="prop-btn" id="pitalic" onclick="toggleProp('italic')"><i>I</i></button>
    <div class="tool-sep"></div>
    <input type="color" class="cpick" id="prop-color"
        value="#000000" onchange="applyTextProp()" oninput="applyTextProp()">
    <div class="tool-sep"></div>
    <button class="prop-btn" onclick="deleteActive()" style="color:#ef4444">🗑 Delete</button>
</div>

    <div id="editor-body">

        <div id="pages-panel">
            <div class="panel-title">📑 Pages</div>
            <div id="thumb-container"></div>
        </div>

        <div id="canvas-area">
            <div id="page-wrap">
                <canvas id="pdf-canvas"></canvas>
                <div id="text-layer"></div>
                <div id="ann-layer"></div>
                <canvas id="draw-canvas"></canvas>
            </div>

            <div id="bottom-nav">
                <button onclick="go(-1)" id="btn-prev" disabled>&#9650;</button>
                <span id="page-indicator">1 / 1</span>
                <button onclick="go(1)" id="btn-next" disabled>&#9660;</button>
                <div class="nav-sep"></div>
                <button onclick="changeZoom(-.25)" style="font-size:18px;font-weight:300">−</button>
                <span id="zoom-lbl">100%</span>
                <button onclick="changeZoom(.25)"  style="font-size:18px;font-weight:300">+</button>
            </div>
        </div>
    </div>
</div>

<div id="sign-modal">
    <div class="sign-box">
        <h3>Add Signature</h3>
        <p>Type your name as signature</p>
        <input type="text" id="sign-input" class="sign-input" placeholder="Your signature...">
        <div class="sign-actions">
            <button class="btn-cancel"   onclick="closeSignModal()">Cancel</button>
            <button class="btn-add-sign" onclick="addSignature()">Add Signature</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

/* ══════════════ STATE ══════════════ */
let SCALE = 1.5;
let pdfDoc = null, pNum = 1, totP = 0;
let currentTool = 'move';
let store     = {};
let annStore  = {};
let drawStore = {};
let historyStack = [], futureStack = [];
let curSpan = null, curSpanData = null;
let curAnn  = null;
let isDrawing = false, drawCtx = null;
let selectedFile = null;

/* ══════════════ UPLOAD ══════════════ */
document.getElementById('fileIn').onchange = e => {
    const f = e.target.files[0]; if(!f) return;
    selectedFile = f;
    document.getElementById('fname').innerText = '✓ ' + f.name;
    document.getElementById('file-name-input').value = f.name;
    document.getElementById('openBtn').disabled = false;
};

document.getElementById('dropZone').ondragover = e => e.preventDefault();
document.getElementById('dropZone').ondrop = e => {
    e.preventDefault();
    const f = e.dataTransfer.files[0];
    if(f?.type === 'application/pdf'){
        const dt = new DataTransfer(); dt.items.add(f);
        document.getElementById('fileIn').files = dt.files;
        document.getElementById('fileIn').dispatchEvent(new Event('change'));
    }
};

document.getElementById('openBtn').onclick = async () => {
    if(!selectedFile) return;
    showSpinner();
    try {
        const buf = await selectedFile.arrayBuffer();
        pdfDoc = await pdfjsLib.getDocument(buf).promise;
        totP = pdfDoc.numPages;
        document.getElementById('upload-screen').style.display = 'none';
        document.getElementById('editor-page').style.display   = 'flex';
        await renderPage(1);
        buildThumbs();
    } catch(err) {
        alert('Could not open this PDF.');
        console.error(err);
    }
    hideSpinner();
};

/* ══════════════ SPINNER ══════════════ */
const showSpinner = () => document.getElementById('spinner').classList.add('show');
const hideSpinner = () => document.getElementById('spinner').classList.remove('show');

/* ══════════════ RENDER PAGE ══════════════ */
async function renderPage(n){
    savePage();
    pNum = n;
    document.getElementById('page-indicator').innerText = `${n} / ${totP}`;
    document.getElementById('btn-prev').disabled = n <= 1;
    document.getElementById('btn-next').disabled = n >= totP;
    document.querySelectorAll('.page-thumb').forEach(t =>
        t.classList.toggle('active', +t.dataset.page === n));

    showSpinner();

    const page = await pdfDoc.getPage(n);
    const vp   = page.getViewport({ scale: SCALE });

    const cv = document.getElementById('pdf-canvas');
    cv.width = vp.width; cv.height = vp.height;
    await page.render({ canvasContext: cv.getContext('2d'), viewport: vp }).promise;

    const dc = document.getElementById('draw-canvas');
    dc.width = vp.width; dc.height = vp.height;
    drawCtx  = dc.getContext('2d');
    if(drawStore[n]){
        const img = new Image();
        img.onload = () => drawCtx.drawImage(img, 0, 0);
        img.src    = drawStore[n];
    }

    const tl = document.getElementById('text-layer');
    const al = document.getElementById('ann-layer');
    tl.style.width = al.style.width  = vp.width  + 'px';
    tl.style.height= al.style.height = vp.height + 'px';
    tl.innerHTML = ''; al.innerHTML = '';
    curSpan = null; curSpanData = null;

    if(!store[n]){
        const tc = await page.getTextContent({ includeMarkedContent: false });
        store[n] = [];
        tc.items.forEach((item, i) => {
            if(!item.str) return;
            const m  = pdfjsLib.Util.transform(vp.transform, item.transform);
            const fs = Math.sqrt(m[0]*m[0] + m[1]*m[1]);
            if(fs < 1) return;
            const fn = (item.fontName||'').toLowerCase();
            let ff = 'Arial';
            if(fn.includes('times'))        ff = 'Times New Roman';
            else if(fn.includes('courier')) ff = 'Courier New';
            store[n].push({
                id: `s${n}_${i}`,
                txt: item.str, orig: item.str,
                x: m[4], y: m[5],
                fs, sx: Math.abs(m[0]/fs), sy: Math.abs(m[3]/fs),
                w: (item.width||0)*SCALE, ff,
                bold:   fn.includes('bold'),
                italic: fn.includes('italic')||fn.includes('oblique'),
                color:  '#000000', mod: false
            });
        });
    }

    if(currentTool === 'edittext'){
        tl.style.pointerEvents = 'auto';
        store[n].forEach(d => makeTextSpan(d, tl));
    }

    (annStore[n]||[]).forEach(a => drawAnn(a, al));
    applyToolPointers();
    hideSpinner();
}

/* ══════════════ TEXT SPANS ══════════════ */
function makeTextSpan(d, container){
    const s = document.createElement('span');
    s.id = d.id;
    s.innerText = d.txt;
    s.contentEditable = 'true';
    s.spellcheck = false;

    applySpanStyle(s, d);

    s.onfocus = () => {
        if(curSpan && curSpan !== s){
            curSpan.classList.remove('ed');
            curSpan.style.color = 'transparent';
            curSpan.style.background = 'transparent';
        }
        curSpan = s;
        curSpanData = d;
        s.classList.add('ed');
        s.style.color = d.color;
        s.style.background = '#fff';
        syncPropBar(d);
    };

    s.onblur = () => {
        s.classList.remove('ed');
        d.txt = s.innerText;
        d.mod = d.txt !== d.orig;
        if(!d.mod){
            s.style.color = 'transparent';
            s.style.background = 'transparent';
        } else {
            s.style.color = d.color;
            s.style.background = '#fff';
        }
        curSpan = null;
        curSpanData = null;
    };

    s.onkeydown = e => {
        if(e.key === 'Enter')  { e.preventDefault(); s.blur(); }
        if(e.key === 'Escape') { s.innerText = d.orig; d.mod=false; s.blur(); }
        if(e.key === 'Delete' && currentTool === 'eraser'){
            d.txt=''; d.mod=true; s.innerText=''; s.blur();
        }
    };

    s.oninput = () => {
        d.txt = s.innerText;
        d.mod = true;
    };

    container.appendChild(s);
    d.el = s;
    return s;
}

function applySpanStyle(s, d){
    s.style.cssText =
        `position:absolute;` +
        `left:${d.x}px;` +
        `top:${d.y - d.fs}px;` +
        `font-size:${d.fs}px;` +
        `font-family:${d.ff};` +
        `font-weight:${d.bold ? 'bold' : 'normal'};` +
        `font-style:${d.italic ? 'italic' : 'normal'};` +
        `color:${d.mod ? d.color : 'transparent'};` +
        `background:${d.mod ? '#fff' : 'transparent'};` +
        `border:1.5px solid transparent;` +
        `border-radius:2px;` +
        `outline:none;` +
        `caret-color:#ef4444;` +
        `white-space:pre;` +
        `line-height:1;` +
        `padding:0 2px;` +
        `min-width:${Math.max(d.w, 20)}px;` +
        `height:auto;` +
        `cursor:text;` +
        `transform-origin:0% 0%;` +
        `pointer-events:auto;`;
}

function syncPropBar(d){
    document.getElementById('prop-font-family').value = d.ff;
    document.getElementById('prop-font-size').value   = Math.round(d.fs);
    document.getElementById('prop-color').value       = d.color;
    document.getElementById('pbold').classList.toggle('on',   d.bold);
    document.getElementById('pitalic').classList.toggle('on', d.italic);
}

/* ══════════════ SET TOOL ══════════════ */
function setTool(t){
    currentTool = t;
    document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active'));
    const btn = document.getElementById('tool-' + t);
    if(btn) btn.classList.add('active');

    document.getElementById('prop-bar').classList.toggle('show',
        ['addtext','edittext','pencil'].includes(t));

    const tl = document.getElementById('text-layer');
    tl.innerHTML = '';

    if(t === 'edittext'){
        tl.style.pointerEvents = 'auto';
        (store[pNum]||[]).forEach(d => makeTextSpan(d, tl));
    } else {
        tl.style.pointerEvents = 'none';
        curSpan = null; curSpanData = null;
    }

    applyToolPointers();
}

function applyToolPointers(){
    const al = document.getElementById('ann-layer');
    const dc = document.getElementById('draw-canvas');

    al.style.pointerEvents = 'none';
    dc.style.pointerEvents = 'none';
    al.onclick = al.onmousedown = al.onmousemove = al.onmouseup = null;
    dc.onmousedown = dc.onmousemove = dc.onmouseup = dc.onmouseleave = null;

    if(['addtext','eraser','highlight'].includes(currentTool)){
        al.style.pointerEvents = 'auto';
    }
    if(currentTool === 'addtext')   al.onclick    = addTextClick;
    if(currentTool === 'highlight') setupHighlight();
    if(currentTool === 'pencil')  { dc.style.pointerEvents = 'auto'; setupDraw(); }
}

/* ══════════════ TOOLBAR ACTIONS ══════════════ */
function applyTextProp(){
    const ff = document.getElementById('prop-font-family').value;
    const fs = parseFloat(document.getElementById('prop-font-size').value);
    const c  = document.getElementById('prop-color').value;

    // Edit text span
    if(curSpan && curSpanData){
        curSpanData.ff    = ff;
        curSpanData.fs    = fs;
        curSpanData.color = c;
        curSpanData.mod   = true;
        curSpan.style.fontFamily = ff;
        curSpan.style.fontSize   = fs + 'px';
        curSpan.style.color      = c;
        curSpan.style.top        = (curSpanData.y - fs) + 'px';
    }

    // Annotation text
    if(curAnn && curAnn.type === 'text'){
        curAnn.fontFamily = ff;
        curAnn.fontSize   = fs;
        curAnn.color      = c;
        const el = document.querySelector(`[data-id="${curAnn.id}"]`);
        if(el){
            el.style.fontFamily = ff;
            el.style.fontSize   = fs + 'px';
            el.style.color      = c;
        }
    }
}

function toggleProp(t){
    const btn = document.getElementById(t === 'bold' ? 'pbold' : 'pitalic');
    btn.classList.toggle('on');
    const on = btn.classList.contains('on');

    if(curSpan && curSpanData){
        if(t === 'bold'){
            curSpanData.bold = on;
            curSpan.style.fontWeight = on ? 'bold' : 'normal';
        } else {
            curSpanData.italic = on;
            curSpan.style.fontStyle = on ? 'italic' : 'normal';
        }
        curSpanData.mod = true;
    }

    if(curAnn && curAnn.type === 'text'){
        const el = document.querySelector(`[data-id="${curAnn.id}"]`);
        if(t === 'bold'){
            curAnn.bold = on;
            if(el) el.style.fontWeight = on ? 'bold' : 'normal';
        } else {
            curAnn.italic = on;
            if(el) el.style.fontStyle = on ? 'italic' : 'normal';
        }
    }
}

function deleteActive(){
    // Delete focused text span
    if(curSpan && curSpanData){
        curSpanData.txt = '';
        curSpanData.mod = true;
        curSpan.innerText = '';
        curSpan.blur();
        pushHistory();
        return;
    }
    // Delete selected annotation
    if(curAnn){
        const el = document.querySelector(`[data-id="${curAnn.id}"]`);
        if(el) el.remove();
        removeAnn(curAnn.id);
        curAnn = null;
        return;
    }
}

/* ══════════════ ADD TEXT ══════════════ */
function addTextClick(e){
    if(currentTool !== 'addtext') return;
    const rect = document.getElementById('page-wrap').getBoundingClientRect();
    const ann  = {
        id:         'at' + Date.now(),
        type:       'text',
        x:          e.clientX - rect.left,
        y:          e.clientY - rect.top,
        text:       'New Text',
        fontSize:   parseFloat(document.getElementById('prop-font-size').value) || 14,
        fontFamily: document.getElementById('prop-font-family').value,
        color:      document.getElementById('prop-color').value,
        bold:       document.getElementById('pbold').classList.contains('on'),
        italic:     document.getElementById('pitalic').classList.contains('on'),
    };
    if(!annStore[pNum]) annStore[pNum] = [];
    annStore[pNum].push(ann);
    pushHistory();
    const al = document.getElementById('ann-layer');
    al.style.pointerEvents = 'auto';
    drawAnn(ann, al);

    // Auto focus new text
    setTimeout(() => {
        const el = document.querySelector(`[data-id="${ann.id}"]`);
        if(el){ el.focus(); selectAll(el); }
    }, 50);
}

function selectAll(el){
    const range = document.createRange();
    range.selectNodeContents(el);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
}

/* ══════════════ DRAW ANNOTATION ══════════════ */
function drawAnn(ann, layer){
    if(ann.type === 'text'){
        const div = document.createElement('div');
        div.className      = 'ann-text';
        div.contentEditable= 'true';
        div.spellcheck     = false;
        div.innerText      = ann.text;
        div.dataset.id     = ann.id;
        div.style.cssText  =
            `left:${ann.x}px;top:${ann.y}px;` +
            `font-size:${ann.fontSize}px;` +
            `font-family:${ann.fontFamily};` +
            `color:${ann.color};` +
            `font-weight:${ann.bold ? 'bold' : 'normal'};` +
            `font-style:${ann.italic ? 'italic' : 'normal'};`;

        makeDraggable(div, ann);

        div.oninput  = () => { ann.text = div.innerText; };
        div.onfocus  = () => {
            curAnn = ann;
            div.classList.add('selected');
            syncPropBar({
                ff:     ann.fontFamily,
                fs:     ann.fontSize,
                color:  ann.color,
                bold:   ann.bold,
                italic: ann.italic
            });
        };
        div.onblur   = () => {
            div.classList.remove('selected');
            ann.text = div.innerText;
            pushHistory();
        };
        div.onclick  = e => {
            if(currentTool === 'eraser'){
                e.stopPropagation();
                removeAnn(ann.id);
                div.remove();
            }
        };
        div.onkeydown = e => {
            if(e.key === 'Escape') div.blur();
        };
        layer.appendChild(div);

    } else if(ann.type === 'highlight'){
        const div = document.createElement('div');
        div.className  = 'ann-highlight';
        div.dataset.id = ann.id;
        div.style.cssText =
            `left:${ann.x}px;top:${ann.y}px;` +
            `width:${ann.w}px;height:${ann.h}px;` +
            `background:${ann.color || 'rgba(250,204,21,.45)'};`;
        div.onclick = () => {
            if(currentTool === 'eraser'){ removeAnn(ann.id); div.remove(); }
        };
        layer.appendChild(div);
    }
}

function removeAnn(id){
    if(annStore[pNum]) annStore[pNum] = annStore[pNum].filter(a => a.id !== id);
    pushHistory();
}

/* ══════════════ DRAG ══════════════ */
function makeDraggable(el, ann){
    let ox, oy, dragging = false;
    el.addEventListener('mousedown', e => {
        if(currentTool !== 'move') return;
        dragging = true;
        ox = e.clientX - ann.x;
        oy = e.clientY - ann.y;
        e.preventDefault();
    });
    document.addEventListener('mousemove', e => {
        if(!dragging) return;
        ann.x = e.clientX - ox;
        ann.y = e.clientY - oy;
        el.style.left = ann.x + 'px';
        el.style.top  = ann.y + 'px';
    });
    document.addEventListener('mouseup', () => {
        if(dragging){ dragging = false; pushHistory(); }
    });
}

/* ══════════════ HIGHLIGHT ══════════════ */
function setupHighlight(){
    const al = document.getElementById('ann-layer');
    let startX, startY, hlDiv = null;

    al.onmousedown = e => {
        if(currentTool !== 'highlight') return;
        const r = document.getElementById('page-wrap').getBoundingClientRect();
        startX = e.clientX - r.left;
        startY = e.clientY - r.top;
        hlDiv  = document.createElement('div');
        hlDiv.style.cssText =
            `position:absolute;left:${startX}px;top:${startY}px;` +
            `width:0;height:22px;background:rgba(250,204,21,.5);` +
            `border-radius:2px;pointer-events:none;`;
        al.appendChild(hlDiv);
    };
    al.onmousemove = e => {
        if(!hlDiv || currentTool !== 'highlight') return;
        const r = document.getElementById('page-wrap').getBoundingClientRect();
        hlDiv.style.width = Math.max(e.clientX - r.left - startX, 0) + 'px';
    };
    al.onmouseup = () => {
        if(!hlDiv) return;
        const w = parseFloat(hlDiv.style.width);
        if(w > 10){
            const ann = {
                id: 'hl' + Date.now(), type: 'highlight',
                x: startX, y: startY, w, h: 22,
                color: 'rgba(250,204,21,.5)'
            };
            if(!annStore[pNum]) annStore[pNum] = [];
            annStore[pNum].push(ann);
            pushHistory();
            hlDiv.dataset.id = ann.id;
            hlDiv.style.pointerEvents = 'auto';
            hlDiv.onclick = () => {
                if(currentTool === 'eraser'){ removeAnn(ann.id); hlDiv.remove(); }
            };
        } else {
            hlDiv.remove();
        }
        hlDiv = null;
    };
}

/* ══════════════ PENCIL ══════════════ */
function setupDraw(){
    const dc = document.getElementById('draw-canvas');
    let color = '#ef4444';

    dc.onmousedown = e => {
        if(currentTool !== 'pencil') return;
        color = document.getElementById('prop-color')?.value || '#ef4444';
        isDrawing = true;
        const r = dc.getBoundingClientRect();
        drawCtx.beginPath();
        drawCtx.moveTo(e.clientX - r.left, e.clientY - r.top);
        drawCtx.strokeStyle = color;
        drawCtx.lineWidth   = 2.5;
        drawCtx.lineCap     = 'round';
        drawCtx.lineJoin    = 'round';
    };
    dc.onmousemove = e => {
        if(!isDrawing || currentTool !== 'pencil') return;
        const r = dc.getBoundingClientRect();
        drawCtx.lineTo(e.clientX - r.left, e.clientY - r.top);
        drawCtx.stroke();
    };
    const endDraw = () => {
        if(!isDrawing) return;
        isDrawing = false;
        drawCtx.closePath();
        drawStore[pNum] = document.getElementById('draw-canvas').toDataURL();
        pushHistory();
    };
    dc.onmouseup    = endDraw;
    dc.onmouseleave = endDraw;
}

/* ══════════════ SAVE ══════════════ */
function savePage(){
    (store[pNum]||[]).forEach(d => {
        if(d.el){ d.txt = d.el.innerText; d.mod = d.txt !== d.orig; }
    });
    document.querySelectorAll('.ann-text').forEach(el => {
        const ann = (annStore[pNum]||[]).find(a => a.id === el.dataset.id);
        if(ann) ann.text = el.innerText;
    });
}

/* ══════════════ PAGES PANEL ══════════════ */
function togglePages(){
    document.getElementById('pages-panel').classList.toggle('show');
    document.getElementById('tool-pages').classList.toggle('active');
}

async function buildThumbs(){
    const container = document.getElementById('thumb-container');
    container.innerHTML = '';
    for(let p = 1; p <= totP; p++){
        const wrap = document.createElement('div');
        wrap.className    = 'page-thumb' + (p === pNum ? ' active' : '');
        wrap.dataset.page = p;
        wrap.onclick      = () => renderPage(p);
        const cv   = document.createElement('canvas');
        const page = await pdfDoc.getPage(p);
        const vp   = page.getViewport({ scale: 0.18 });
        cv.width = vp.width; cv.height = vp.height;
        await page.render({ canvasContext: cv.getContext('2d'), viewport: vp }).promise;
        const num = document.createElement('div');
        num.className = 'pg-num';
        num.innerText = p;
        wrap.appendChild(cv);
        wrap.appendChild(num);
        container.appendChild(wrap);
    }
}

/* ══════════════ NAV & ZOOM ══════════════ */
function go(dir){
    const n = pNum + dir;
    if(n < 1 || n > totP) return;
    renderPage(n);
}

function changeZoom(delta){
    SCALE = Math.min(Math.max(SCALE + delta, 0.5), 4);
    document.getElementById('zoom-lbl').innerText = Math.round(SCALE / 1.5 * 100) + '%';
    renderPage(pNum);
}

/* ══════════════ HISTORY (UNDO/REDO) ══════════════ */
function pushHistory(){
    historyStack.push(JSON.stringify({ store, annStore }));
    if(historyStack.length > 50) historyStack.shift();
    futureStack = [];
}

function undo(){
    if(!historyStack.length) return;
    futureStack.push(JSON.stringify({ store, annStore }));
    const prev = JSON.parse(historyStack.pop());
    store    = prev.store;
    annStore = prev.annStore;
    renderPage(pNum);
}

function redo(){
    if(!futureStack.length) return;
    historyStack.push(JSON.stringify({ store, annStore }));
    const next = JSON.parse(futureStack.pop());
    store    = next.store;
    annStore = next.annStore;
    renderPage(pNum);
}

/* ══════════════ SIGN ══════════════ */
function openSignModal(){
    document.getElementById('sign-modal').classList.add('show');
    setTimeout(() => document.getElementById('sign-input').focus(), 100);
}
function closeSignModal(){
    document.getElementById('sign-modal').classList.remove('show');
}
function addSignature(){
    const sig = document.getElementById('sign-input').value.trim();
    if(!sig){ alert('Please type your signature.'); return; }
    const cv  = document.getElementById('pdf-canvas');
    const ann = {
        id:         'sg' + Date.now(),
        type:       'text',
        x:          cv.width / 2 - 100,
        y:          cv.height - 120,
        text:       sig,
        fontSize:   32,
        fontFamily: 'Georgia, serif',
        color:      '#1e3a8a',
        bold:       false,
        italic:     true
    };
    if(!annStore[pNum]) annStore[pNum] = [];
    annStore[pNum].push(ann);
    const al = document.getElementById('ann-layer');
    al.style.pointerEvents = 'auto';
    drawAnn(ann, al);
    pushHistory();
    closeSignModal();
    document.getElementById('sign-input').value = '';
}

/* ══════════════ DOWNLOAD ══════════════ */
async function downloadPdf(){
    savePage();
    showSpinner();
    const { jsPDF } = window.jspdf;
    let pdf = null;

    for(let p = 1; p <= totP; p++){
        const page = await pdfDoc.getPage(p);
        const vp   = page.getViewport({ scale: SCALE });
        const cv   = document.createElement('canvas');
        cv.width   = vp.width;
        cv.height  = vp.height;
        const ctx  = cv.getContext('2d');
        await page.render({ canvasContext: ctx, viewport: vp }).promise;

        /* ── Text edits ── */
        (store[p]||[]).forEach(d => {
            if(!d.mod) return;

            ctx.save();
            let fnt = '';
            if(d.italic) fnt += 'italic ';
            if(d.bold)   fnt += 'bold ';
            fnt += d.fs + 'px ' + d.ff;
            ctx.font = fnt;

            const measuredW = Math.max(ctx.measureText(d.orig || d.txt).width, d.w) + 12;

            // White out original
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(d.x - 2, d.y - d.fs * 1.1, measuredW, d.fs * 1.3);

            // Draw new text at baseline
            if(d.txt.trim()){
                ctx.fillStyle    = d.color || '#000000';
                ctx.textBaseline = 'alphabetic';
                ctx.fillText(d.txt, d.x, d.y);
            }
            ctx.restore();
        });

        /* ── Annotations ── */
        (annStore[p]||[]).forEach(ann => {
            ctx.save();
            if(ann.type === 'highlight'){
                ctx.fillStyle = ann.color || 'rgba(250,204,21,.5)';
                ctx.fillRect(ann.x, ann.y, ann.w, ann.h);
            } else if(ann.type === 'text'){
                let fnt = '';
                if(ann.italic) fnt += 'italic ';
                if(ann.bold)   fnt += 'bold ';
                fnt += ann.fontSize + 'px ' + (ann.fontFamily || 'Arial');
                ctx.font         = fnt;
                ctx.fillStyle    = ann.color || '#000';
                ctx.textBaseline = 'top';
                ctx.fillText(ann.text || '', ann.x, ann.y);
            }
            ctx.restore();
        });

        /* ── Drawings ── */
        if(drawStore[p]){
            await new Promise(res => {
                const img = new Image();
                img.onload = () => { ctx.drawImage(img, 0, 0); res(); };
                img.src    = drawStore[p];
            });
        }

        const imgData = cv.toDataURL('image/jpeg', 1.0);
        if(!pdf) pdf = new jsPDF({ unit: 'pt', format: [vp.width, vp.height] });
        else     pdf.addPage([vp.width, vp.height]);
        pdf.addImage(imgData, 'JPEG', 0, 0, vp.width, vp.height);
    }

    hideSpinner();
    const fn = document.getElementById('file-name-input').value.trim() || 'edited';
    pdf.save(fn.endsWith('.pdf') ? fn : fn + '.pdf');
}
</script>
</body>
</html>