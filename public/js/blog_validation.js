(function () {
    'use strict';

    const form = document.getElementById('blog-form');

    const titleInput = document.getElementById('post-title');
    const categoryInput = document.getElementById('post-category');
    const dateInput = document.getElementById('post-date');
    const excerptInput = document.getElementById('post-excerpt');
    const contentInput = document.getElementById('post-content');
    const imageInput = document.getElementById('post-image');
    const existingImageInput = document.getElementById('existing-image-url');
    const altInput = document.getElementById('Testo_Alternativo');

    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    if (!form || !titleInput || !contentInput || !errorMessageDiv || !altInput) {
        console.warn('Blog validation: missing form elements, aborting initialization.');
        return;
    }

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

        messageDiv.textContent = message;
        messageDiv.classList.remove('hidden');
        otherDiv.classList.add('hidden');

        if (!messageDiv.hasAttribute('tabindex')) {
            messageDiv.setAttribute('tabindex', '-1');
        }
        messageDiv.focus();
    }

    function hideGlobalMessages() {
        errorMessageDiv.textContent = '';
        errorMessageDiv.classList.add('hidden');
        if (successMessageDiv) {
            successMessageDiv.textContent = '';
            successMessageDiv.classList.add('hidden');
        }
    }

    function validateTitle() {
        const val = titleInput.value.trim();
        if (val === '') { showFieldError(titleInput, 'Il titolo è obbligatorio'); return false; }
        clearFieldError(titleInput); return true;
    }

    function validateCategory() {
        if (categoryInput.value === '') { showFieldError(categoryInput, 'Seleziona una categoria'); return false; }
        clearFieldError(categoryInput); return true;
    }

    function validateDate() {
        if (dateInput.value === '') { showFieldError(dateInput, 'La data è obbligatoria'); return false; }
        clearFieldError(dateInput); return true;
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
            showFieldError(imageInput, 'Carica un\'immagine (JPG/PNG/WebP, max 2MB)');
            return false;
        }

        clearFieldError(imageInput); return true;
    }

    function validateAlt() {
        if (altInput.value.trim() === '') { showFieldError(altInput, 'Il testo alternativo è obbligatorio'); return false; }
        clearFieldError(altInput); return true;
    }

    function validateForm() {
        hideGlobalMessages();

        const v1 = validateTitle();
        const v2 = validateCategory();
        const v3 = validateDate();
        const v4 = validateExcerpt();
        const v5 = validateContent();
        const v6 = validateImage();
        const v7 = validateAlt();

        return v1 && v2 && v3 && v4 && v5 && v6 && v7;
    }

    titleInput.addEventListener('blur', validateTitle);
    categoryInput.addEventListener('blur', validateCategory);
    dateInput.addEventListener('blur', validateDate);
    excerptInput.addEventListener('blur', validateExcerpt);
    contentInput.addEventListener('blur', validateContent);
    imageInput.addEventListener('blur', validateImage);
    altInput.addEventListener('blur', validateAlt);

    const inputs = [
        titleInput, categoryInput, dateInput,
        excerptInput, contentInput, imageInput, altInput
    ];

    inputs.forEach(function (input) {
        input.addEventListener('input', hideGlobalMessages);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            form.submit();
        } else {
            showGlobalMessage('Il form non è stato compilato correttamente!\n Correggere prima di poter continuare', 'error');

            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

})();
