(function () {
    'use strict';

    const form = document.getElementById('blog-form');

    const titleInput = document.getElementById('post-title');
    const dateInput = document.getElementById('post-date');
    const readingTimeInput = document.getElementById('post-reading-time');
    const excerptInput = document.getElementById('post-excerpt');
    const contentInput = document.getElementById('post-content');
    const imageInput = document.getElementById('post-image');
    const existingImageInput = document.getElementById('existing-image-url');
    const altInput = document.getElementById('Testo_Alternativo');
    const statusInput = document.getElementById('post-status');
    const extrasContainer = document.getElementById('extras-container');

    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    // Check se ci sono tutti
    if (!form || !titleInput || !contentInput || !altInput) {
        console.warn('Blog validation: missing form elements, aborting initialization.');
        return;
    }

    // rendo visibile trovando l'id dello span l'errore con il messaggio dedicato
    function showFieldError(input, message) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = message;
        }
        input.classList.add('error');
        input.classList.remove('valid');
        input.setAttribute('aria-invalid', 'true');
    }

    function clearFieldError(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = '';
        }
        input.classList.remove('error');
        input.classList.add('valid');
        input.setAttribute('aria-invalid', 'false');
    }

    function showGlobalMessage(message, type) {
        const messageDiv = type === 'error' ? errorMessageDiv : successMessageDiv;
        const otherDiv = type === 'error' ? successMessageDiv : errorMessageDiv;

        if (!messageDiv) {
            return;
        }

        messageDiv.textContent = message;
        messageDiv.classList.remove('hidden');
        if (otherDiv) {
            otherDiv.classList.add('hidden');
        }

        if (!messageDiv.hasAttribute('tabindex')) {
            messageDiv.setAttribute('tabindex', '-1');
        }

        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        messageDiv.focus();
    }

    function hideGlobalMessages() {
        if (errorMessageDiv) {
            errorMessageDiv.textContent = '';
            errorMessageDiv.classList.add('hidden');
        }
        if (successMessageDiv) {
            successMessageDiv.textContent = '';
            successMessageDiv.classList.add('hidden');
        }
    }

    function validateTitle() {
        if (titleInput.value.trim() === '') { showFieldError(titleInput, 'Il titolo è obbligatorio'); return false; }
        clearFieldError(titleInput); return true;
    }

    function validateDate() {
        if (dateInput.value === '') { showFieldError(dateInput, 'La data è obbligatoria'); return false; }
        clearFieldError(dateInput); return true;
    }

    function validateReadingTime() {
        if (!readingTimeInput) {
            return true;
        }
        const val = parseInt(readingTimeInput.value, 10);
        if (readingTimeInput.value === '') { showFieldError(readingTimeInput, 'Il tempo di lettura è obbligatorio'); return false; }
        if (isNaN(val) || val < 1) { showFieldError(readingTimeInput, 'Inserisci minuti validi'); return false; }
        clearFieldError(readingTimeInput); return true;
    }

    function validateExcerpt() {
        const val = excerptInput.value.trim();
        if (val === '') { showFieldError(excerptInput, 'L\'estratto è obbligatorio'); return false; }
        if (val.length > 200) { showFieldError(excerptInput, 'Massimo 200 caratteri'); return false; }
        clearFieldError(excerptInput); return true;
    }

    function validateContent() {
        if (contentInput.value.trim() === '') { showFieldError(contentInput, 'Il contenuto è obbligatorio'); return false; }
        clearFieldError(contentInput); return true;
    }

    function validateImage() {
        const hasFile = imageInput && imageInput.files && imageInput.files.length > 0;
        const existingVal = existingImageInput ? existingImageInput.value.trim() : '';

        if (!hasFile && existingVal === '') {
            showFieldError(imageInput, 'Carica un\'immagine (JPG/PNG/WebP, max 1MB)');
            return false;
        }

        clearFieldError(imageInput);
        return true;
    }

    function validateAlt() {
        if (altInput.value.trim() === '') { showFieldError(altInput, 'Il testo alternativo è obbligatorio'); return false; }
        clearFieldError(altInput); return true;
    }

    function validateStatus() {
        if (!statusInput) {
            return true;
        }
        if (statusInput.value === '') { showFieldError(statusInput, 'Lo stato è obbligatorio'); return false; }
        clearFieldError(statusInput); return true;
    }

    function showExtraError(input, message, errorClass) {
        const row = input.closest('.extra-row');
        const errorSpan = row ? row.querySelector(errorClass) : null;
        if (errorSpan) {
            errorSpan.textContent = message;
        }
        input.classList.add('error');
        input.classList.remove('valid');
        input.setAttribute('aria-invalid', 'true');
    }

    function clearExtraError(input, errorClass) {
        const row = input.closest('.extra-row');
        const errorSpan = row ? row.querySelector(errorClass) : null;
        if (errorSpan) {
            errorSpan.textContent = '';
        }
        input.classList.remove('error');
        input.classList.add('valid');
        input.setAttribute('aria-invalid', 'false');
    }

    function validateExtras() {
        if (!extrasContainer) {
            return true;
        }
        const titleInputs = extrasContainer.querySelectorAll('input[name="extra_title[]"]');
        const itemInputs = extrasContainer.querySelectorAll('textarea[name="extra_item[]"]');
        const len = Math.max(titleInputs.length, itemInputs.length);
        let ok = true;

        for (let i = 0; i < len; i++) {
            const titleInput = titleInputs[i];
            const itemInput = itemInputs[i];
            const titleVal = titleInput ? titleInput.value.trim() : '';
            const itemVal = itemInput ? itemInput.value.trim() : '';

            if (titleVal === '' && itemVal === '') {
                if (titleInput) {
                    clearExtraError(titleInput, '.extra-title-error');
                }
                if (itemInput) {
                    clearExtraError(itemInput, '.extra-item-error');
                }
                continue;
            }

            if (!titleInput || titleVal === '') {
                if (titleInput) {
                    showExtraError(titleInput, 'Inserisci il titolo extra', '.extra-title-error');
                }
                ok = false;
            } else {
                clearExtraError(titleInput, '.extra-title-error');
            }

            if (!itemInput || itemVal === '') {
                if (itemInput) {
                    showExtraError(itemInput, 'Inserisci il contenuto', '.extra-item-error');
                }
                ok = false;
            } else {
                clearExtraError(itemInput, '.extra-item-error');
            }
        }

        return ok;
    }

    function validateForm() {
        hideGlobalMessages();

        const v1 = validateTitle();
        const v2 = validateDate();
        const v3 = validateReadingTime();
        const v4 = validateExcerpt();
        const v5 = validateContent();
        const v6 = validateImage();
        const v7 = validateAlt();
        const v8 = validateStatus();
        const v9 = validateExtras();

        return v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9;
    }

    titleInput.addEventListener('blur', validateTitle);
    dateInput.addEventListener('blur', validateDate);
    if (readingTimeInput) {
        readingTimeInput.addEventListener('blur', validateReadingTime);
    }
    excerptInput.addEventListener('blur', validateExcerpt);
    contentInput.addEventListener('blur', validateContent);
    imageInput.addEventListener('blur', validateImage);
    altInput.addEventListener('blur', validateAlt);
    if (statusInput) {
        statusInput.addEventListener('blur', validateStatus);
        statusInput.addEventListener('change', validateStatus);
    }

    const inputs = [
        titleInput, dateInput, readingTimeInput,
        excerptInput, contentInput, imageInput, altInput, statusInput
    ].filter(Boolean);

    inputs.forEach(function (input) {
        input.addEventListener('input', hideGlobalMessages);
    });

    if (extrasContainer) {
        extrasContainer.addEventListener('input', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            if (target.name === 'extra_title[]') {
                clearExtraError(target, '.extra-title-error');
                hideGlobalMessages();
            } else if (target.name === 'extra_item[]') {
                clearExtraError(target, '.extra-item-error');
                hideGlobalMessages();
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            form.submit();
        } else {
            showGlobalMessage('Il form non è stato compilato correttamente!\n Correggere prima di poter continuare', 'error');
        }
    });

})();
