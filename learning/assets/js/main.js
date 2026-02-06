/**
 * DADE Elearning - Main JavaScript
 * FINAL, COMPLETE, AND CORRECTED VERSION - 15 JULY 2025 v4
 * This file contains all JavaScript for the entire site. No omissions.
 */
(() => {
  document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const html = document.documentElement;

    // ==================================================================
    //                         1. MOBILE NAVIGATION
    // ==================================================================
    const navToggle = document.querySelector(".nav-toggle");
    if (navToggle) {
      navToggle.addEventListener("click", () => {
        body.classList.toggle("nav-active");
        navToggle.setAttribute(
          "aria-expanded",
          body.classList.contains("nav-active")
        );
      });
    }

    // ==================================================================
    //                    2. ADVANCED ACCESSIBILITY WIDGET
    // ==================================================================
    const acc = {
      toggleBtn: document.getElementById("accessibility-toggle"),
      menu: document.getElementById("accessibility-menu"),
      resetBtn: document.getElementById("accessibility-reset"),
      fontIncreaseBtn: document.getElementById("acc-font-increase"),
      fontDecreaseBtn: document.getElementById("acc-font-decrease"),
      fontIndicator: document.getElementById("acc-font-indicator"),
      highContrastBtn: document.getElementById("acc-high-contrast"),
      negativeContrastBtn: document.getElementById("acc-negative-contrast"),
      grayscaleBtn: document.getElementById("acc-grayscale"),
      dyslexiaFontBtn: document.getElementById("acc-dyslexia-font"),
      highlightLinksBtn: document.getElementById("acc-highlight-links"),
      highlightHeadingsBtn: document.getElementById("acc-highlight-headings"),
      readingMaskBtn: document.getElementById("acc-reading-mask"),
      ttsBtn: document.getElementById("acc-text-to-speech"),
      stopAnimationsBtn: document.getElementById("acc-stop-animations"),
      enlargeCursorBtn: document.getElementById("acc-enlarge-cursor"),
      readingMaskTop: document.getElementById("reading-mask-top"),
      readingMaskBottom: document.getElementById("reading-mask-bottom"),
      ttsPopup: document.getElementById("tts-popup"),
      ttsPlayBtn: document.getElementById("tts-play"),
      synth: window.speechSynthesis,
      initialFontSize: 16,
      currentFontScale: 1,
    };

    const settings = {
      fontSize: "acc-font-size",
      highContrast: "acc-high-contrast",
      negativeContrast: "acc-negative-contrast",
      grayscale: "acc-grayscale",
      dyslexiaFont: "acc-dyslexia-font",
      highlightLinks: "acc-highlight-links",
      highlightHeadings: "acc-highlight-headings",
      stopAnimations: "acc-stop-animations",
      enlargeCursor: "acc-enlarge-cursor",
    };

    const toggleFeature = (key, className, button) => {
      if (!button) return;
      body.classList.toggle(className);
      const isActive = body.classList.contains(className);
      localStorage.setItem(key, isActive);
      button.classList.toggle("active", isActive);
    };

    const updateFontIndicator = () => {
      if (acc.fontIndicator) {
        acc.fontIndicator.textContent = `${Math.round(
          acc.currentFontScale * 100
        )}%`;
      }
    };

    const applySavedSettings = () => {
      const savedScale = parseFloat(localStorage.getItem(settings.fontSize));
      if (savedScale && !isNaN(savedScale)) {
        acc.currentFontScale = savedScale;
        html.style.fontSize = `${acc.initialFontSize * acc.currentFontScale}px`;
      }
      updateFontIndicator();
      if (localStorage.getItem(settings.highContrast) === "true")
        toggleFeature(
          settings.highContrast,
          "acc-high-contrast",
          acc.highContrastBtn
        );
      if (localStorage.getItem(settings.negativeContrast) === "true")
        toggleFeature(
          settings.negativeContrast,
          "acc-negative-contrast",
          acc.negativeContrastBtn
        );
      if (localStorage.getItem(settings.grayscale) === "true")
        toggleFeature(settings.grayscale, "acc-grayscale", acc.grayscaleBtn);
      if (localStorage.getItem(settings.dyslexiaFont) === "true")
        toggleFeature(
          settings.dyslexiaFont,
          "acc-dyslexia-font",
          acc.dyslexiaFontBtn
        );
      if (localStorage.getItem(settings.highlightLinks) === "true")
        toggleFeature(
          settings.highlightLinks,
          "acc-highlight-links",
          acc.highlightLinksBtn
        );
      if (localStorage.getItem(settings.highlightHeadings) === "true")
        toggleFeature(
          settings.highlightHeadings,
          "acc-highlight-headings",
          acc.highlightHeadingsBtn
        );
      if (localStorage.getItem(settings.stopAnimations) === "true")
        toggleFeature(
          settings.stopAnimations,
          "acc-stop-animations",
          acc.stopAnimationsBtn
        );
      if (localStorage.getItem(settings.enlargeCursor) === "true")
        toggleFeature(
          settings.enlargeCursor,
          "acc-enlarge-cursor",
          acc.enlargeCursorBtn
        );
    };

    if (acc.toggleBtn && acc.menu) {
      acc.toggleBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isHidden = acc.menu.getAttribute("aria-hidden") === "true";
        acc.menu.setAttribute("aria-hidden", !isHidden);
        acc.toggleBtn.setAttribute("aria-expanded", !isHidden);
      });
      document.addEventListener("click", (e) => {
        if (!acc.menu.contains(e.target) && !acc.toggleBtn.contains(e.target)) {
          acc.menu.setAttribute("aria-hidden", "true");
          acc.toggleBtn.setAttribute("aria-expanded", "false");
        }
      });
    }

    if (acc.fontIncreaseBtn)
      acc.fontIncreaseBtn.addEventListener("click", () => {
        if (acc.currentFontScale < 2) {
          acc.currentFontScale = parseFloat(
            (acc.currentFontScale + 0.1).toFixed(2)
          );
          html.style.fontSize = `${
            acc.initialFontSize * acc.currentFontScale
          }px`;
          localStorage.setItem(settings.fontSize, acc.currentFontScale);
          updateFontIndicator();
        }
      });
    if (acc.fontDecreaseBtn)
      acc.fontDecreaseBtn.addEventListener("click", () => {
        if (acc.currentFontScale > 0.7) {
          acc.currentFontScale = parseFloat(
            (acc.currentFontScale - 0.1).toFixed(2)
          );
          html.style.fontSize = `${
            acc.initialFontSize * acc.currentFontScale
          }px`;
          localStorage.setItem(settings.fontSize, acc.currentFontScale);
          updateFontIndicator();
        }
      });

    if (acc.highContrastBtn)
      acc.highContrastBtn.addEventListener("click", () =>
        toggleFeature(
          settings.highContrast,
          "acc-high-contrast",
          acc.highContrastBtn
        )
      );
    if (acc.negativeContrastBtn)
      acc.negativeContrastBtn.addEventListener("click", () =>
        toggleFeature(
          settings.negativeContrast,
          "acc-negative-contrast",
          acc.negativeContrastBtn
        )
      );
    if (acc.grayscaleBtn)
      acc.grayscaleBtn.addEventListener("click", () =>
        toggleFeature(settings.grayscale, "acc-grayscale", acc.grayscaleBtn)
      );
    if (acc.dyslexiaFontBtn)
      acc.dyslexiaFontBtn.addEventListener("click", () =>
        toggleFeature(
          settings.dyslexiaFont,
          "acc-dyslexia-font",
          acc.dyslexiaFontBtn
        )
      );
    if (acc.highlightLinksBtn)
      acc.highlightLinksBtn.addEventListener("click", () =>
        toggleFeature(
          settings.highlightLinks,
          "acc-highlight-links",
          acc.highlightLinksBtn
        )
      );
    if (acc.highlightHeadingsBtn)
      acc.highlightHeadingsBtn.addEventListener("click", () =>
        toggleFeature(
          settings.highlightHeadings,
          "acc-highlight-headings",
          acc.highlightHeadingsBtn
        )
      );
    if (acc.stopAnimationsBtn)
      acc.stopAnimationsBtn.addEventListener("click", () =>
        toggleFeature(
          settings.stopAnimations,
          "acc-stop-animations",
          acc.stopAnimationsBtn
        )
      );
    if (acc.enlargeCursorBtn)
      acc.enlargeCursorBtn.addEventListener("click", () =>
        toggleFeature(
          settings.enlargeCursor,
          "acc-enlarge-cursor",
          acc.enlargeCursorBtn
        )
      );

    if (acc.readingMaskBtn && acc.readingMaskTop && acc.readingMaskBottom) {
      acc.readingMaskBtn.addEventListener("click", () => {
        body.classList.toggle("acc-reading-mask-active");
        acc.readingMaskBtn.classList.toggle(
          "active",
          body.classList.contains("acc-reading-mask-active")
        );
      });
      window.addEventListener("mousemove", (e) => {
        if (body.classList.contains("acc-reading-mask-active")) {
          const maskHeight = 80;
          acc.readingMaskTop.style.height = `${e.clientY - maskHeight / 2}px`;
          acc.readingMaskBottom.style.top = `${e.clientY + maskHeight / 2}px`;
        }
      });
    }

    if (acc.ttsBtn && acc.ttsPopup && acc.ttsPlayBtn && acc.synth) {
      acc.ttsBtn.addEventListener("click", () => {
        const isActive = acc.ttsBtn.classList.toggle("active");
        if (isActive) {
          alert(
            "Text to Speech is now active. Select any text on the page to hear it read aloud."
          );
        } else {
          if (acc.synth.speaking) acc.synth.cancel();
        }
      });
      document.addEventListener("mouseup", () => {
        if (!acc.ttsBtn.classList.contains("active")) return;
        const selection = window.getSelection();
        const selectedText = selection.toString().trim();
        if (selectedText.length > 1) {
          const range = selection.getRangeAt(0);
          const rect = range.getBoundingClientRect();
          acc.ttsPopup.style.top = `${
            window.scrollY + rect.top - acc.ttsPopup.offsetHeight - 5
          }px`;
          acc.ttsPopup.style.left = `${
            window.scrollX +
            rect.left +
            rect.width / 2 -
            acc.ttsPopup.offsetWidth / 2
          }px`;
          acc.ttsPopup.classList.add("show");
        } else {
          acc.ttsPopup.classList.remove("show");
        }
      });
      acc.ttsPlayBtn.addEventListener("click", () => {
        const selectedText = window.getSelection().toString().trim();
        if (selectedText.length > 0) {
          if (acc.synth.speaking) acc.synth.cancel();
          const utterance = new SpeechSynthesisUtterance(selectedText);
          acc.synth.speak(utterance);
        }
        acc.ttsPopup.classList.remove("show");
      });
    }

    if (acc.resetBtn) {
      acc.resetBtn.addEventListener("click", () => {
        Object.keys(settings).forEach((key) =>
          localStorage.removeItem(settings[key])
        );
        window.location.reload();
      });
    }

    applySavedSettings();

    // ==================================================================
    //                3. DYNAMIC STAR RATING DISPLAY
    // ==================================================================
    document.querySelectorAll(".stars-display").forEach((starBox) => {
      const rating = parseFloat(starBox.dataset.rating) || 0;
      const percentage = (rating / 5) * 100;
      starBox.style.setProperty("--rating-percent", `${percentage}%`);
    });

    // ==================================================================
    //                       4. SITE-WIDE AI CHATBOT
    // ==================================================================
    const chatToggleButton = document.getElementById("ai-assistant-toggle");
    const chatWidget = document.getElementById("ai-chat-widget");
    const closeChatButton = document.getElementById("ai-chat-close");
    const chatForm = document.getElementById("ai-chat-form");
    const chatInput = document.getElementById("ai-chat-input");
    const chatMessages = document.getElementById("ai-chat-messages");
    const urlParams = new URLSearchParams(window.location.search);
    const lessonIdForAI = urlParams.get("id");

    if (chatToggleButton && chatWidget) {
      chatToggleButton.addEventListener("click", () => {
        const isHidden = chatWidget.getAttribute("aria-hidden") === "true";
        chatWidget.setAttribute("aria-hidden", !isHidden);
        if (!isHidden && chatInput) chatInput.focus();
      });
    }
    if (closeChatButton) {
      closeChatButton.addEventListener("click", () =>
        chatWidget.setAttribute("aria-hidden", "true")
      );
    }
    if (chatForm) {
      const addMessage = (text, sender) => {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("message", `${sender}-message`);
        const p = document.createElement("p");
        p.innerHTML = text.replace(/\n/g, "<br>");
        messageDiv.appendChild(p);
        if (chatMessages) {
          chatMessages.appendChild(messageDiv);
          chatMessages.scrollTop = chatMessages.scrollHeight;
        }
      };
      chatForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const userQuestion = chatInput.value.trim();
        if (!userQuestion) return;
        addMessage(userQuestion, "user");
        chatInput.value = "";
        addMessage("Thinking...", "ai");
        try {
          const response = await fetch("/ai_handler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              action: "ask_question",
              question: userQuestion,
              lesson_id: lessonIdForAI,
            }),
          });
          const data = await response.json();
          if (chatMessages.lastChild)
            chatMessages.removeChild(chatMessages.lastChild);
          addMessage(
            data.error ? `<strong>Error:</strong> ${data.error}` : data.answer,
            "ai"
          );
        } catch (error) {
          if (chatMessages.lastChild)
            chatMessages.removeChild(chatMessages.lastChild);
          addMessage("<strong>Sorry, an error occurred.</strong>", "ai");
        }
      });
    }

    // ==================================================================
    //                       5. DELETE CONFIRMATION
    // ==================================================================
    document.querySelectorAll(".delete-form").forEach((form) => {
      form.addEventListener("submit", (e) => {
        if (
          !confirm(
            "Are you sure you want to delete this item? This action is permanent."
          )
        ) {
          e.preventDefault();
        }
      });
    });

    // ==================================================================
    //                       6. AI SUMMARY MODAL
    // ==================================================================
    const summaryButton = document.getElementById("ai-summary-button");
    const summaryModal = document.getElementById("ai-summary-modal");
    if (summaryModal) {
      const summaryContent = summaryModal.querySelector("#ai-summary-content");
      const modalCloseButton = summaryModal.querySelector(
        ".modal-close-button"
      );
      const openSummaryModal = () => {
        summaryModal.setAttribute("aria-hidden", "false");
        body.classList.add("modal-open");
      };
      const closeSummaryModal = () => {
        summaryModal.setAttribute("aria-hidden", "true");
        body.classList.remove("modal-open");
      };
      if (summaryButton && lessonIdForAI) {
        summaryButton.addEventListener("click", async () => {
          openSummaryModal();
          summaryContent.innerHTML = '<div class="spinner"></div>';
          try {
            const response = await fetch("/ai_handler.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                action: "summarize_content",
                lesson_id: lessonIdForAI,
              }),
            });
            const data = await response.json();
            summaryContent.innerHTML = data.error
              ? `<p class="error-text">${data.error}</p>`
              : data.summary.replace(/\n/g, "<br>");
          } catch (error) {
            summaryContent.innerHTML =
              '<p class="error-text">Could not connect to the AI service.</p>';
          }
        });
      }
      if (modalCloseButton)
        modalCloseButton.addEventListener("click", closeSummaryModal);
      summaryModal.addEventListener("click", (e) => {
        if (e.target === summaryModal) closeSummaryModal();
      });
    }

    // ==================================================================
    //          7. AI SUBTITLE GENERATOR (ASSEMBLYAI - with Polling)
    // ==================================================================
    const aiSubtitleButton = document.getElementById("generate-subtitles-ai");
    const aiSubtitleStatus = document.getElementById("ai-subtitle-status");
    const pollTranscriptionStatus = (lessonId, transcriptId) => {
      const intervalId = setInterval(async () => {
        try {
          const response = await fetch("/ai_handler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              action: "generate_subtitles",
              lesson_id: lessonId,
              transcript_id: transcriptId,
            }),
          });
          const data = await response.json();
          if (data.error) {
            aiSubtitleStatus.innerHTML = `<p class="error-text">Error: ${data.error}</p>`;
            clearInterval(intervalId);
            aiSubtitleButton.disabled = false;
            aiSubtitleButton.textContent = "🤖 Generate Accurate Subtitles";
          } else if (data.status === "completed") {
            aiSubtitleStatus.innerHTML = `<p class="success-text">Success! ${data.message}</p>`;
            clearInterval(intervalId);
            setTimeout(() => window.location.reload(), 2000);
          } else if (data.status === "error") {
            aiSubtitleStatus.innerHTML = `<p class="error-text">Transcription failed: ${
              data.error || "Unknown error"
            }.</p>`;
            clearInterval(intervalId);
            aiSubtitleButton.disabled = false;
            aiSubtitleButton.textContent = "🤖 Generate Accurate Subtitles";
          } else {
            aiSubtitleStatus.innerHTML = `<div class="spinner-inline"></div><p>Status: <strong>${data.status}...</strong></p>`;
          }
        } catch (error) {
          aiSubtitleStatus.innerHTML =
            '<p class="error-text">Connection error while checking status.</p>';
          clearInterval(intervalId);
          aiSubtitleButton.disabled = false;
          aiSubtitleButton.textContent = "🤖 Generate Accurate Subtitles";
        }
      }, 10000);
    };
    if (aiSubtitleButton) {
      aiSubtitleButton.addEventListener("click", async () => {
        const lessonId = aiSubtitleButton.dataset.lessonId;
        if (
          !confirm(
            "This will start the transcription process. It can take several minutes. You can close this page and the process will continue. Proceed?"
          )
        )
          return;
        aiSubtitleButton.disabled = true;
        aiSubtitleStatus.innerHTML =
          '<div class="spinner-inline"></div><p>Submitting video to AI service...</p>';
        try {
          const response = await fetch("/ai_handler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              action: "generate_subtitles",
              lesson_id: lessonId,
            }),
          });
          const data = await response.json();
          if (data.error) {
            aiSubtitleStatus.innerHTML = `<p class="error-text">Error: ${data.error}</p>`;
            aiSubtitleButton.disabled = false;
          } else {
            aiSubtitleStatus.innerHTML =
              '<div class="spinner-inline"></div><p>Video submitted! Transcription is processing...</p>';
            pollTranscriptionStatus(lessonId, data.transcript_id);
          }
        } catch (error) {
          aiSubtitleStatus.innerHTML =
            '<p class="error-text">An unexpected error occurred during submission.</p>';
          aiSubtitleButton.disabled = false;
        }
      });
    }

    // ==================================================================
    //          8. INSTRUCTOR VIDEO UPLOAD TABS
    // ==================================================================
    const uploadTabs = document.querySelectorAll(
      ".upload-type-tabs .tab-button"
    );
    if (uploadTabs.length > 0) {
      const fileUploadContent = document.getElementById("file_upload");
      const youtubeEmbedContent = document.getElementById("youtube_embed");
      let typeInput = document.querySelector('input[name="video_upload_type"]');
      if (!typeInput && fileUploadContent) {
        typeInput = document.createElement("input");
        typeInput.type = "hidden";
        typeInput.name = "video_upload_type";
        fileUploadContent.closest("form").appendChild(typeInput);
      }

      uploadTabs.forEach((button) => {
        button.addEventListener("click", () => {
          uploadTabs.forEach((btn) => btn.classList.remove("active"));
          button.classList.add("active");
          if (button.dataset.tab === "youtube_embed") {
            fileUploadContent.classList.remove("active");
            youtubeEmbedContent.classList.add("active");
            if (typeInput) typeInput.value = "youtube";
          } else {
            youtubeEmbedContent.classList.remove("active");
            fileUploadContent.classList.add("active");
            if (typeInput) typeInput.value = "file";
          }
        });
      });
    }
  });
})();

