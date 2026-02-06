/**
 * DADE Learn - AI Study Assistant Widget
 * Floating chatbot with context-aware learning
 */

(function () {
    'use strict';

    // Get current course and lesson from URL
    const path = window.location.pathname;
    const courseMatch = path.match(/\/learn\/([^\/]+)/);
    const lessonMatch = path.match(/\/learn\/[^\/]+\/(\d+)/);

    const courseSlug = courseMatch ? courseMatch[1] : null;
    const lessonId = lessonMatch ? lessonMatch[1] : null;

    // Get course ID from page if available
    const courseIdEl = document.querySelector('[data-course-id]');
    const courseId = courseIdEl ? courseIdEl.dataset.courseId : null;

    // Create chat widget HTML
    const widgetHTML = `
        <div id="ai-assistant" class="ai-widget">
            <button id="ai-toggle" class="ai-toggle" title="AI Study Assistant">
                <i class="fas fa-robot"></i>
                <span class="ai-badge">AI</span>
            </button>
            
            <div id="ai-panel" class="ai-panel">
                <div class="ai-header">
                    <div class="ai-brand">
                        <div class="ai-avatar">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3>DADE AI</h3>
                            <span>Your Study Assistant</span>
                        </div>
                    </div>
                    <button id="ai-close" class="ai-close"><i class="fas fa-times"></i></button>
                </div>
                
                <div id="ai-messages" class="ai-messages">
                    <div class="ai-message bot">
                        <div class="ai-message-content">
                            <p>👋 Hi! I'm DADE AI, your personal study assistant.</p>
                            <p>I can help you understand your course material, answer questions, and guide your learning journey.</p>
                            <p>What would you like to learn about today?</p>
                        </div>
                    </div>
                </div>
                
                <form id="ai-form" class="ai-input-area">
                    <input type="text" id="ai-input" placeholder="Ask me anything..." autocomplete="off">
                    <button type="submit" id="ai-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    `;

    // Create styles
    const styles = `
        <style id="ai-widget-styles">
            .ai-widget {
                position: fixed;
                bottom: 90px;
                right: 24px;
                z-index: 9998;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            
            .ai-toggle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #10b981, #059669);
                border: none;
                color: white;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                transition: all 0.3s;
                position: relative;
            }
            
            .ai-toggle:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 30px rgba(16, 185, 129, 0.5);
            }
            
            .ai-badge {
                position: absolute;
                top: -4px;
                right: -4px;
                background: #f59e0b;
                color: white;
                font-size: 10px;
                font-weight: 700;
                padding: 2px 6px;
                border-radius: 8px;
            }
            
            .ai-panel {
                display: none;
                position: absolute;
                bottom: 70px;
                right: 0;
                width: 340px;
                max-width: calc(100vw - 48px);
                height: 420px;
                max-height: calc(100vh - 160px);
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 10px 50px rgba(0,0,0,0.15);
                flex-direction: column;
                overflow: hidden;
                animation: slideUp 0.3s ease;
            }
            
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .ai-panel.active { display: flex; }
            
            .ai-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 20px;
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
            }
            
            .ai-brand {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            .ai-avatar {
                width: 44px;
                height: 44px;
                background: rgba(255,255,255,0.2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }
            
            .ai-brand h3 { font-size: 16px; font-weight: 600; margin: 0; }
            .ai-brand span { font-size: 12px; opacity: 0.9; }
            
            .ai-close {
                width: 32px;
                height: 32px;
                border: none;
                background: rgba(255,255,255,0.2);
                color: white;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .ai-close:hover { background: rgba(255,255,255,0.3); }
            
            .ai-messages {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 16px;
                background: #f8fafc;
            }
            
            .ai-message {
                display: flex;
                gap: 12px;
                max-width: 85%;
            }
            
            .ai-message.user { align-self: flex-end; flex-direction: row-reverse; }
            
            .ai-message-content {
                padding: 12px 16px;
                border-radius: 16px;
                font-size: 14px;
                line-height: 1.5;
            }
            
            .ai-message.bot .ai-message-content {
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08);
                border-bottom-left-radius: 4px;
            }
            
            .ai-message.user .ai-message-content {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
                border-bottom-right-radius: 4px;
            }
            
            .ai-message-content p { margin: 0 0 8px; }
            .ai-message-content p:last-child { margin: 0; }
            .ai-message-content code { background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 4px; font-size: 13px; }
            .ai-message-content pre { background: #1e293b; color: #e2e8f0; padding: 12px; border-radius: 8px; overflow-x: auto; margin: 8px 0; }
            .ai-message-content ul, .ai-message-content ol { margin: 8px 0; padding-left: 20px; }
            .ai-message-content li { margin: 4px 0; }
            
            .ai-typing {
                display: flex;
                gap: 4px;
                padding: 8px 0;
            }
            
            .ai-typing span {
                width: 8px;
                height: 8px;
                background: #10b981;
                border-radius: 50%;
                animation: typing 1.4s infinite;
            }
            
            .ai-typing span:nth-child(2) { animation-delay: 0.2s; }
            .ai-typing span:nth-child(3) { animation-delay: 0.4s; }
            
            @keyframes typing {
                0%, 60%, 100% { transform: translateY(0); opacity: 0.6; }
                30% { transform: translateY(-6px); opacity: 1; }
            }
            
            .ai-input-area {
                display: flex;
                gap: 10px;
                padding: 16px 20px;
                background: white;
                border-top: 1px solid #e2e8f0;
            }
            
            #ai-input {
                flex: 1;
                padding: 12px 16px;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                font-size: 14px;
                transition: all 0.2s;
            }
            
            #ai-input:focus {
                outline: none;
                border-color: #10b981;
            }
            
            #ai-send {
                width: 44px;
                height: 44px;
                border: none;
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            #ai-send:hover { transform: scale(1.05); }
            #ai-send:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
            
            @media (max-width: 480px) {
                .ai-panel {
                    width: calc(100vw - 20px);
                    right: 10px;
                    bottom: 70px;
                    height: calc(100vh - 100px);
                }
                .ai-widget { right: 10px; bottom: 10px; }
            }
        </style>
    `;

    // Inject styles and widget
    document.head.insertAdjacentHTML('beforeend', styles);
    document.body.insertAdjacentHTML('beforeend', widgetHTML);

    // Elements
    const toggle = document.getElementById('ai-toggle');
    const panel = document.getElementById('ai-panel');
    const closeBtn = document.getElementById('ai-close');
    const form = document.getElementById('ai-form');
    const input = document.getElementById('ai-input');
    const messages = document.getElementById('ai-messages');
    const sendBtn = document.getElementById('ai-send');

    // Toggle panel
    toggle.addEventListener('click', () => {
        panel.classList.toggle('active');
        if (panel.classList.contains('active')) {
            input.focus();
        }
    });

    closeBtn.addEventListener('click', () => {
        panel.classList.remove('active');
    });

    // Handle form submit
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        input.value = '';

        // Show typing indicator
        const typingId = showTyping();
        sendBtn.disabled = true;

        try {
            const response = await fetch('/Elearning/api/ai/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: message,
                    course_id: courseId || 0,
                    lesson_id: lessonId || 0
                })
            });

            const data = await response.json();
            removeTyping(typingId);

            if (data.error) {
                addMessage('❌ ' + data.error, 'bot');
            } else {
                addMessage(data.response, 'bot', true);
            }
        } catch (error) {
            removeTyping(typingId);
            addMessage('❌ Connection error. Please try again.', 'bot');
        }

        sendBtn.disabled = false;
        input.focus();
    });

    // Add message to chat
    function addMessage(content, type, isMarkdown = false) {
        const div = document.createElement('div');
        div.className = 'ai-message ' + type;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'ai-message-content';

        if (isMarkdown) {
            contentDiv.innerHTML = parseMarkdown(content);
        } else {
            contentDiv.innerHTML = '<p>' + escapeHtml(content) + '</p>';
        }

        div.appendChild(contentDiv);
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    // Show typing indicator
    function showTyping() {
        const id = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.className = 'ai-message bot';
        div.id = id;
        div.innerHTML = '<div class="ai-message-content"><div class="ai-typing"><span></span><span></span><span></span></div></div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return id;
    }

    function removeTyping(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    // Simple markdown parser
    function parseMarkdown(text) {
        return text
            .replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre><code>$2</code></pre>')
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            .replace(/^\s*[-*]\s+(.+)/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>')
            .replace(/^\d+\.\s+(.+)/gm, '<li>$1</li>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/^(.+)$/gm, function (match) {
                if (match.startsWith('<')) return match;
                return '<p>' + match + '</p>';
            })
            .replace(/<p><\/p>/g, '')
            .replace(/<p>(<ul>|<ol>|<pre>)/g, '$1')
            .replace(/(<\/ul>|<\/ol>|<\/pre>)<\/p>/g, '$1');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

})();
