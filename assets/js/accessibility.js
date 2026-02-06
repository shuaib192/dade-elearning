/**
 * DADE Learn - Accessibility Widget
 * Enhanced with 18 accessibility features for inclusive learning.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Create Widget HTML
    const widgetHTML = `
        <div class="access-widget" id="accessWidget">
            <button class="access-toggle" id="accessToggle" aria-label="Accessibility Menu">
                <i class="fas fa-universal-access"></i>
            </button>
            <div class="access-menu" id="accessMenu">
                <div class="access-header">
                    <h3><i class="fas fa-universal-access"></i> Accessibility</h3>
                    <button class="access-close" id="accessClose">&times;</button>
                </div>
                <div class="access-tabs">
                    <button class="access-tab active" data-tab="vision">Vision</button>
                    <button class="access-tab" data-tab="reading">Reading</button>
                    <button class="access-tab" data-tab="navigation">Navigation</button>
                </div>
                <div class="access-options">
                    <!-- VISION TAB -->
                    <div class="tab-content active" id="tab-vision">
                        <div class="access-option">
                            <span><i class="fas fa-moon"></i> Dark Mode</span>
                            <label class="switch"><input type="checkbox" id="darkModeToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-adjust"></i> High Contrast</span>
                            <label class="switch"><input type="checkbox" id="contrastToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-palette"></i> Monochrome</span>
                            <label class="switch"><input type="checkbox" id="monochromeToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-tint-slash"></i> Low Saturation</span>
                            <label class="switch"><input type="checkbox" id="lowSatToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-image"></i> Hide Images</span>
                            <label class="switch"><input type="checkbox" id="hideImagesToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-mouse-pointer"></i> Large Cursor</span>
                            <label class="switch"><input type="checkbox" id="largeCursorToggle"><span class="slider round"></span></label>
                        </div>
                    </div>
                    <!-- READING TAB -->
                    <div class="tab-content" id="tab-reading">
                        <div class="access-option">
                            <span><i class="fas fa-font"></i> Dyslexic Font</span>
                            <label class="switch"><input type="checkbox" id="dyslexicToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-text-height"></i> Text Size</span>
                            <div class="size-controls">
                                <button id="sizeDecrease">A-</button>
                                <button id="sizeReset">A</button>
                                <button id="sizeIncrease">A+</button>
                            </div>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-arrows-alt-v"></i> Line Height</span>
                            <div class="size-controls">
                                <button id="lineHeightDecrease">-</button>
                                <button id="lineHeightReset">↺</button>
                                <button id="lineHeightIncrease">+</button>
                            </div>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-arrows-alt-h"></i> Letter Spacing</span>
                            <div class="size-controls">
                                <button id="letterSpaceDecrease">-</button>
                                <button id="letterSpaceReset">↺</button>
                                <button id="letterSpaceIncrease">+</button>
                            </div>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-text-width"></i> Word Spacing</span>
                            <div class="size-controls">
                                <button id="wordSpaceDecrease">-</button>
                                <button id="wordSpaceReset">↺</button>
                                <button id="wordSpaceIncrease">+</button>
                            </div>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-align-left"></i> Left Align Text</span>
                            <label class="switch"><input type="checkbox" id="leftAlignToggle"><span class="slider round"></span></label>
                        </div>
                    </div>
                    <!-- NAVIGATION TAB -->
                    <div class="tab-content" id="tab-navigation">
                        <div class="access-option">
                            <span><i class="fas fa-ruler-horizontal"></i> Reading Guide</span>
                            <label class="switch"><input type="checkbox" id="readingGuideToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-bullseye"></i> Focus Mode</span>
                            <label class="switch"><input type="checkbox" id="focusModeToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-link"></i> Highlight Links</span>
                            <label class="switch"><input type="checkbox" id="highlightLinksToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-pause"></i> Pause Animations</span>
                            <label class="switch"><input type="checkbox" id="pauseAnimToggle"><span class="slider round"></span></label>
                        </div>
                        <div class="access-option">
                            <span><i class="fas fa-info-circle"></i> Show Alt Text</span>
                            <label class="switch"><input type="checkbox" id="showAltToggle"><span class="slider round"></span></label>
                        </div>
                    </div>
                </div>
                <div class="access-footer">
                    <button class="reset-all-btn" id="resetAllAccess"><i class="fas fa-redo"></i> Reset All</button>
                </div>
            </div>
        </div>
        <div class="reading-guide" id="readingGuide"></div>
    `;

    // 2. Add to body
    document.body.insertAdjacentHTML('beforeend', widgetHTML);

    // 3. Styles
    const style = document.createElement('style');
    style.textContent = `
        .access-widget {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 99999;
            font-family: inherit;
        }
        .access-toggle {
            width: 50px;
            height: 50px;
            background: var(--primary, #005f73);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: none;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .access-toggle:hover { transform: scale(1.1); }
        .access-menu {
            position: absolute;
            bottom: 70px;
            left: 0;
            width: 320px;
            max-height: 80vh;
            overflow-y: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.2);
            display: none;
            color: #333;
        }
        .access-menu.active { display: block; animation: fadeInUp 0.3s; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .access-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            background: linear-gradient(135deg, #005f73, #0a9396);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .access-header h3 { margin: 0; font-size: 16px; display: flex; align-items: center; gap: 8px; }
        .access-close { background: none; border: none; font-size: 24px; cursor: pointer; color: white; opacity: 0.8; }
        .access-close:hover { opacity: 1; }
        
        /* Tabs */
        .access-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            background: #f9f9f9;
        }
        .access-tab {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            transition: all 0.2s;
        }
        .access-tab.active {
            background: white;
            color: #005f73;
            border-bottom: 2px solid #005f73;
        }
        
        .access-options { padding: 15px 20px; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        .access-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .access-option:last-child { border-bottom: none; margin-bottom: 0; }
        .access-option span { font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .access-option span i { color: #005f73; width: 16px; }
        
        .size-controls { display: flex; gap: 5px; }
        .size-controls button {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            min-width: 32px;
        }
        .size-controls button:hover { background: #eee; }

        /* Switches */
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #005f73; }
        input:checked + .slider:before { transform: translateX(20px); }
        
        .access-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            text-align: center;
        }
        .reset-all-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .reset-all-btn:hover { background: #d32f2f; }

        /* Reading Guide */
        .reading-guide {
            position: fixed;
            left: 0;
            width: 100%;
            height: 40px;
            background: rgba(255, 255, 0, 0.3);
            pointer-events: none;
            z-index: 99998;
            display: none;
            border-top: 2px solid rgba(0,0,0,0.2);
            border-bottom: 2px solid rgba(0,0,0,0.2);
        }
        body.reading-guide-active .reading-guide { display: block; }

        /* Modifier Classes */
        body.dark-mode {
            background: #1a1a2e !important;
            color: #eee !important;
        }
        body.dark-mode * {
            background-color: inherit;
            color: inherit;
            border-color: #333 !important;
        }
        body.dark-mode .card, body.dark-mode .dashboard-sidebar, 
        body.dark-mode .access-menu, body.dark-mode .header {
            background: #16213e !important;
        }
        
        body.high-contrast {
            background: #000 !important;
            color: #fff !important;
        }
        body.high-contrast * {
            background-color: transparent !important;
            color: white !important;
            border-color: #fff !important;
        }
        
        body.monochrome { filter: grayscale(100%) !important; }
        body.low-saturation { filter: saturate(50%) !important; }
        body.hide-images img { opacity: 0 !important; }
        
        body.large-cursor, body.large-cursor * {
            cursor: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path fill="%23000" d="M7 2l12 11.5-5.5 1 3.5 6.5-2 1-3.5-6.5-4.5 3.5z"/></svg>') 0 0, auto !important;
        }
        
        body.dyslexic-font, body.dyslexic-font * {
            font-family: 'OpenDyslexic', Comic Sans MS, sans-serif !important;
        }
        @font-face {
            font-family: 'OpenDyslexic';
            src: url('https://cdn.jsdelivr.net/npm/open-dyslexic@1.0.3/woff/OpenDyslexic-Regular.woff') format('woff');
        }
        
        body.left-align-text, body.left-align-text * { text-align: left !important; }
        
        body.focus-mode *:focus {
            outline: 3px solid #ff6600 !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 10px rgba(255,102,0,0.5) !important;
        }
        
        body.highlight-links a {
            background: yellow !important;
            color: #000 !important;
            text-decoration: underline !important;
            padding: 2px 4px !important;
        }
        
        body.pause-anim, body.pause-anim * {
            animation: none !important;
            transition: none !important;
        }
        body.pause-anim img[src$=".gif"] { visibility: hidden; }
        
        body.show-alt img::after {
            content: attr(alt);
            display: block;
            background: #333;
            color: #fff;
            padding: 5px;
            font-size: 12px;
        }
    `;
    document.head.appendChild(style);

    // 4. Tab Logic
    document.querySelectorAll('.access-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.access-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });

    // 5. Toggle Menu
    const toggle = document.getElementById('accessToggle');
    const menu = document.getElementById('accessMenu');
    const close = document.getElementById('accessClose');
    toggle.addEventListener('click', () => menu.classList.toggle('active'));
    close.addEventListener('click', () => menu.classList.remove('active'));

    // 6. Feature Toggles (with localStorage)
    const features = [
        { id: 'darkModeToggle', class: 'dark-mode', key: 'access-dark' },
        { id: 'contrastToggle', class: 'high-contrast', key: 'access-contrast' },
        { id: 'monochromeToggle', class: 'monochrome', key: 'access-mono' },
        { id: 'lowSatToggle', class: 'low-saturation', key: 'access-lowsat' },
        { id: 'hideImagesToggle', class: 'hide-images', key: 'access-hideimg' },
        { id: 'largeCursorToggle', class: 'large-cursor', key: 'access-cursor' },
        { id: 'dyslexicToggle', class: 'dyslexic-font', key: 'access-dyslexic' },
        { id: 'leftAlignToggle', class: 'left-align-text', key: 'access-leftalign' },
        { id: 'readingGuideToggle', class: 'reading-guide-active', key: 'access-readguide' },
        { id: 'focusModeToggle', class: 'focus-mode', key: 'access-focus' },
        { id: 'highlightLinksToggle', class: 'highlight-links', key: 'access-links' },
        { id: 'pauseAnimToggle', class: 'pause-anim', key: 'access-pauseanim' },
        { id: 'showAltToggle', class: 'show-alt', key: 'access-showalt' }
    ];

    features.forEach(f => {
        const el = document.getElementById(f.id);
        if (!el) return;
        if (localStorage.getItem(f.key) === 'true') {
            document.body.classList.add(f.class);
            el.checked = true;
        }
        el.addEventListener('change', (e) => {
            document.body.classList.toggle(f.class, e.target.checked);
            localStorage.setItem(f.key, e.target.checked);
        });
    });

    // 7. Reading Guide Movement
    const readingGuide = document.getElementById('readingGuide');
    document.addEventListener('mousemove', (e) => {
        if (document.body.classList.contains('reading-guide-active')) {
            readingGuide.style.top = (e.clientY - 20) + 'px';
        }
    });

    // 8. Font Size Controls
    let currentScale = parseFloat(localStorage.getItem('access-scale')) || 1;
    const applyScale = () => {
        document.documentElement.style.fontSize = (currentScale * 16) + 'px';
        localStorage.setItem('access-scale', currentScale);
    };
    document.getElementById('sizeIncrease')?.addEventListener('click', () => { if (currentScale < 1.5) { currentScale += 0.1; applyScale(); } });
    document.getElementById('sizeDecrease')?.addEventListener('click', () => { if (currentScale > 0.8) { currentScale -= 0.1; applyScale(); } });
    document.getElementById('sizeReset')?.addEventListener('click', () => { currentScale = 1; applyScale(); });
    applyScale();

    // 9. Line Height Controls
    let lineHeight = parseFloat(localStorage.getItem('access-lineheight')) || 1.6;
    const applyLineHeight = () => {
        document.body.style.lineHeight = lineHeight;
        localStorage.setItem('access-lineheight', lineHeight);
    };
    document.getElementById('lineHeightIncrease')?.addEventListener('click', () => { if (lineHeight < 2.5) { lineHeight += 0.2; applyLineHeight(); } });
    document.getElementById('lineHeightDecrease')?.addEventListener('click', () => { if (lineHeight > 1) { lineHeight -= 0.2; applyLineHeight(); } });
    document.getElementById('lineHeightReset')?.addEventListener('click', () => { lineHeight = 1.6; applyLineHeight(); });
    if (localStorage.getItem('access-lineheight')) applyLineHeight();

    // 10. Letter Spacing Controls
    let letterSpace = parseFloat(localStorage.getItem('access-letterspace')) || 0;
    const applyLetterSpace = () => {
        document.body.style.letterSpacing = letterSpace + 'px';
        localStorage.setItem('access-letterspace', letterSpace);
    };
    document.getElementById('letterSpaceIncrease')?.addEventListener('click', () => { if (letterSpace < 5) { letterSpace += 1; applyLetterSpace(); } });
    document.getElementById('letterSpaceDecrease')?.addEventListener('click', () => { if (letterSpace > -2) { letterSpace -= 1; applyLetterSpace(); } });
    document.getElementById('letterSpaceReset')?.addEventListener('click', () => { letterSpace = 0; applyLetterSpace(); });
    if (localStorage.getItem('access-letterspace')) applyLetterSpace();

    // 11. Word Spacing Controls
    let wordSpace = parseFloat(localStorage.getItem('access-wordspace')) || 0;
    const applyWordSpace = () => {
        document.body.style.wordSpacing = wordSpace + 'px';
        localStorage.setItem('access-wordspace', wordSpace);
    };
    document.getElementById('wordSpaceIncrease')?.addEventListener('click', () => { if (wordSpace < 10) { wordSpace += 2; applyWordSpace(); } });
    document.getElementById('wordSpaceDecrease')?.addEventListener('click', () => { if (wordSpace > -4) { wordSpace -= 2; applyWordSpace(); } });
    document.getElementById('wordSpaceReset')?.addEventListener('click', () => { wordSpace = 0; applyWordSpace(); });
    if (localStorage.getItem('access-wordspace')) applyWordSpace();

    // 12. Reset All
    document.getElementById('resetAllAccess')?.addEventListener('click', () => {
        features.forEach(f => {
            document.body.classList.remove(f.class);
            localStorage.removeItem(f.key);
            const el = document.getElementById(f.id);
            if (el) el.checked = false;
        });
        currentScale = 1; applyScale();
        lineHeight = 1.6; document.body.style.lineHeight = '';
        letterSpace = 0; document.body.style.letterSpacing = '';
        wordSpace = 0; document.body.style.wordSpacing = '';
        localStorage.removeItem('access-scale');
        localStorage.removeItem('access-lineheight');
        localStorage.removeItem('access-letterspace');
        localStorage.removeItem('access-wordspace');
    });

    // 13. Close menu on outside click
    document.addEventListener('click', (e) => {
        if (!document.getElementById('accessWidget').contains(e.target)) {
            menu.classList.remove('active');
        }
    });
});
