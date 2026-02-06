/**
 * DADE Learn - Accessibility Widget
 * Enhances platform accessibility with various visual toggles.
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
                    <h3>Accessibility</h3>
                    <button class="access-close" id="accessClose">&times;</button>
                </div>
                <div class="access-options">
                    <div class="access-option">
                        <span>High Contrast</span>
                        <label class="switch">
                            <input type="checkbox" id="contrastToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="access-option">
                        <span>Dyslexic Font</span>
                        <label class="switch">
                            <input type="checkbox" id="dyslexicToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="access-option">
                        <span>Text Size</span>
                        <div class="size-controls">
                            <button id="sizeDecrease">A-</button>
                            <button id="sizeReset">A</button>
                            <button id="sizeIncrease">A+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            width: 250px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.2);
            padding: 20px;
            display: none;
            color: #333;
        }
        .access-menu.active { display: block; animation: fadeInUp 0.3s; }
        .access-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .access-header h3 { margin: 0; font-size: 18px; }
        .access-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .access-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .size-controls { display: flex; gap: 5px; }
        .size-controls button {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 5px;
            cursor: pointer;
        }
        .size-controls button:hover { background: #eee; }

        /* Switches */
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #005f73; }
        input:checked + .slider:before { transform: translateX(20px); }

        /* Modifier Classes */
        body.high-contrast {
            background: #000 !important;
            color: #fff !important;
        }
        body.high-contrast * {
            background-color: transparent !important;
            color: white !important;
            border-color: #fff !important;
        }
        body.dyslexic-font {
            font-family: 'OpenDyslexic', sans-serif !important;
        }
        @font-face {
            font-family: 'OpenDyslexic';
            src: url('https://antijingoist.github.io/web-accessibility/fonts/opendyslexic/OpenDyslexic-Regular.otf');
        }
    `;
    document.head.appendChild(style);

    // 4. Logic
    const toggle = document.getElementById('accessToggle');
    const menu = document.getElementById('accessMenu');
    const close = document.getElementById('accessClose');
    const contrastToggle = document.getElementById('contrastToggle');
    const dyslexicToggle = document.getElementById('dyslexicToggle');

    toggle.addEventListener('click', () => menu.classList.toggle('active'));
    close.addEventListener('click', () => menu.classList.remove('active'));

    // High Contrast
    if (localStorage.getItem('access-contrast') === 'true') {
        document.body.classList.add('high-contrast');
        contrastToggle.checked = true;
    }
    contrastToggle.addEventListener('change', (e) => {
        document.body.classList.toggle('high-contrast', e.target.checked);
        localStorage.setItem('access-contrast', e.target.checked);
    });

    // Dyslexic Font
    if (localStorage.getItem('access-dyslexic') === 'true') {
        document.body.classList.add('dyslexic-font');
        dyslexicToggle.checked = true;
    }
    dyslexicToggle.addEventListener('change', (e) => {
        document.body.classList.toggle('dyslexic-font', e.target.checked);
        localStorage.setItem('access-dyslexic', e.target.checked);
    });

    // Font Size
    let currentScale = parseFloat(localStorage.getItem('access-scale')) || 1;
    const applyScale = () => {
        document.documentElement.style.fontSize = (currentScale * 16) + 'px';
        localStorage.setItem('access-scale', currentScale);
    };

    document.getElementById('sizeIncrease').addEventListener('click', () => {
        if (currentScale < 1.5) { currentScale += 0.1; applyScale(); }
    });
    document.getElementById('sizeDecrease').addEventListener('click', () => {
        if (currentScale > 0.8) { currentScale -= 0.1; applyScale(); }
    });
    document.getElementById('sizeReset').addEventListener('click', () => {
        currentScale = 1; applyScale();
    });

    applyScale();

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!document.getElementById('accessWidget').contains(e.target)) {
            menu.classList.remove('active');
        }
    });
});
