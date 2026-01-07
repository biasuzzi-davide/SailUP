(function () {
    'use strict';

    const form = document.getElementById('product-form');
    
    const nameInput = document.getElementById('product-name');
    const typeInput = document.getElementById('product-type');
    const descriptionInput = document.getElementById('product-description');
    const longDescriptionInput = document.getElementById('product-long-description');
    const priceInput = document.getElementById('product-price');
    const capacityInput = document.getElementById('product-capacity');
    const imageInput = document.getElementById('product-image-main');
    const existingImageInput = document.getElementById('existing-image-url');
    const altInput = document.getElementById('Testo_Alternativo'); 
    const statusInput = document.getElementById('product-status');
    const languageFieldset = document.getElementById('language-fieldset');
    const languageCheckboxes = Array.from(document.querySelectorAll('input[name="product-languages[]"]'));
    const languageError = document.getElementById('product-languages-error');

    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    if (!form || !nameInput || !priceInput || !errorMessageDiv || !altInput || !longDescriptionInput) {
        console.warn('Product validation: missing form elements, aborting initialization.');
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
        
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

    function validateName() {
        if (nameInput.value.trim() === '') { showFieldError(nameInput, 'Il nome è obbligatorio'); return false; }
        clearFieldError(nameInput); return true;
    }

    function validateType() {
        if (typeInput.value === '') { showFieldError(typeInput, 'Seleziona un tipo'); return false; }
        clearFieldError(typeInput); return true;
    }

    function validateDescription() {
        if (descriptionInput.value.trim() === '') { showFieldError(descriptionInput, 'La descrizione breve è obbligatoria'); return false; }
        clearFieldError(descriptionInput); return true;
    }

    function validateLongDescription() {
        if (longDescriptionInput.value.trim() === '') { showFieldError(longDescriptionInput, 'La descrizione dettagliata è obbligatoria'); return false; }
        clearFieldError(longDescriptionInput); return true;
    }

    function validatePrice() {
        const val = parseFloat(priceInput.value);
        if (priceInput.value === '') { showFieldError(priceInput, 'Il prezzo è obbligatorio'); return false; }
        if (isNaN(val) || val < 0) { showFieldError(priceInput, 'Inserisci un prezzo valido (es. 850)'); return false; }
        clearFieldError(priceInput); return true;
    }

    function validateCapacity() {
        const val = parseInt(capacityInput.value, 10);
        if (capacityInput.value === '') { showFieldError(capacityInput, 'La capacità è obbligatoria'); return false; }
        if (isNaN(val) || val < 1 || val > 50) { showFieldError(capacityInput, 'Valore tra 1 e 50'); return false; }
        clearFieldError(capacityInput); return true;
    }

    function validateImage() {
        const hasFile = imageInput && imageInput.files && imageInput.files.length > 0;
        const existingVal = existingImageInput ? existingImageInput.value.trim() : '';

        if (!hasFile && existingVal === '') {
            showFieldError(imageInput, 'Carica un\'immagine (JPG/PNG/WebP, max 2MB)');
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
        if (statusInput.value === '') { showFieldError(statusInput, 'Lo stato è obbligatorio'); return false; }
        clearFieldError(statusInput); return true;
    }

    function clearLanguageSelection() {
        languageCheckboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }

    function clearLanguageError() {
        if (languageError) {
            languageError.textContent = '';
        }
        if (languageFieldset) {
            languageFieldset.setAttribute('aria-invalid', 'false');
        }
    }

    function updateLanguageFieldsetVisibility() {
        if (!languageFieldset) {
            return;
        }

        if (typeInput.value === 'experience') {
            languageFieldset.classList.remove('hidden');
        } else {
            languageFieldset.classList.add('hidden');
            clearLanguageSelection();
            clearLanguageError();
        }
    }

    function validateLanguages() {
        if (!languageFieldset || typeInput.value !== 'experience') {
            clearLanguageError();
            return true;
        }

        const hasSelection = languageCheckboxes.some(function (checkbox) {
            return checkbox.checked;
        });

        if (!hasSelection) {
            if (languageError) {
                languageError.textContent = 'Seleziona almeno una lingua per l\'esperienza';
            }
            languageFieldset.setAttribute('aria-invalid', 'true');
            return false;
        }

        clearLanguageError();
        return true;
    }

    function validateForm() {
        hideGlobalMessages();

        const v1 = validateName();
        const v2 = validateType();
        const v3 = validateDescription();
        const v4 = validateLongDescription();
        const v5 = validatePrice();
        const v6 = validateCapacity();
        const v7 = validateImage();
        const v8 = validateAlt();
        const v9 = validateStatus();
        const v10 = validateLanguages();

        return v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10;
    }

    nameInput.addEventListener('blur', validateName);
    typeInput.addEventListener('blur', validateType);
    descriptionInput.addEventListener('blur', validateDescription);
    longDescriptionInput.addEventListener('blur', validateLongDescription);
    priceInput.addEventListener('blur', validatePrice);
    capacityInput.addEventListener('blur', validateCapacity);
    imageInput.addEventListener('blur', validateImage);
    altInput.addEventListener('blur', validateAlt);
    statusInput.addEventListener('blur', validateStatus);

    const inputs = [
        nameInput, typeInput, descriptionInput, longDescriptionInput,
        priceInput, capacityInput, imageInput, altInput, statusInput
    ];

    inputs.forEach(function (input) {
        input.addEventListener('input', hideGlobalMessages);
    });

    typeInput.addEventListener('change', function () {
        updateLanguageFieldsetVisibility();
        hideGlobalMessages();
    });

    languageCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            hideGlobalMessages();
            validateLanguages();
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            form.submit();
        } else {
            showGlobalMessage('Il form non è stato compilato correttamente!\n Correggere prima di poter continuare', 'error');
        }
    });

    updateLanguageFieldsetVisibility();

})();
