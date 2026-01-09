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
    const boatTypeFieldset = document.getElementById('boat-type-fieldset');
    const boatTypeInput = document.getElementById('product-category');
    const lengthGroup = document.getElementById('length-group');
    const durationGroup = document.getElementById('duration-group');
    const durationInput = document.getElementById('product-duration');
    const licenseOption = document.getElementById('license-option');
    const accessOption = document.getElementById('access-option');
    const licenseCheckbox = document.getElementById('requires-license');
    const accessCheckbox = document.getElementById('is-accessible');
    const languageFieldset = document.getElementById('language-fieldset');
    const languageCheckboxes = Array.from(document.querySelectorAll('input[name="product-languages[]"]'));
    const languageError = document.getElementById('product-languages-error');
    const extrasContainer = document.getElementById('product-extras-container');

    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    if (!form || !nameInput || !typeInput || !priceInput || !altInput || !longDescriptionInput) {
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
        if (!statusInput) {
            return true;
        }
        if (statusInput.type === 'checkbox') {
            if (statusInput.hasAttribute('required') && !statusInput.checked) {
                showFieldError(statusInput, 'Seleziona lo stato del prodotto');
                return false;
            }
            clearFieldError(statusInput);
            return true;
        }
        if (statusInput.value === '') { showFieldError(statusInput, 'Lo stato è obbligatorio'); return false; }
        clearFieldError(statusInput); return true;
    }

    function validateDuration() {
        if (!durationInput || typeInput.value !== 'experience') {
            if (durationInput) {
                clearFieldError(durationInput);
            }
            return true;
        }
        const val = parseInt(durationInput.value, 10);
        if (durationInput.value === '') { showFieldError(durationInput, 'La durata è obbligatoria'); return false; }
        if (isNaN(val) || val < 1) { showFieldError(durationInput, 'Inserisci una durata valida in ore'); return false; }
        clearFieldError(durationInput); return true;
    }

    function validateBoatType() {
        if (!boatTypeInput || typeInput.value !== 'noleggio') {
            if (boatTypeInput) {
                clearFieldError(boatTypeInput);
            }
            return true;
        }
        if (boatTypeInput.value === '') { showFieldError(boatTypeInput, 'Seleziona la tipologia della barca per il noleggio'); return false; }
        clearFieldError(boatTypeInput); return true;
    }

    function clearBoatTypeSelection() {
        if (boatTypeInput) {
            boatTypeInput.value = '';
        }
    }

    function updateBoatTypeVisibility() {
        if (!boatTypeFieldset || !boatTypeInput) {
            return;
        }

        if (typeInput.value === 'noleggio') {
            boatTypeFieldset.classList.remove('hidden');
            boatTypeInput.setAttribute('required', '');
            boatTypeInput.setAttribute('aria-required', 'true');
        } else {
            boatTypeFieldset.classList.add('hidden');
            boatTypeInput.removeAttribute('required');
            boatTypeInput.setAttribute('aria-required', 'false');
            clearBoatTypeSelection();
            clearFieldError(boatTypeInput);
        }
    }

    function updateExperienceFieldsVisibility() {
        if (!lengthGroup || !durationGroup) {
            return;
        }

        if (typeInput.value === 'experience') {
            lengthGroup.classList.add('hidden');
            durationGroup.classList.remove('hidden');
            if (durationInput) {
                durationInput.setAttribute('required', '');
                durationInput.setAttribute('aria-required', 'true');
            }
        } else {
            durationGroup.classList.add('hidden');
            lengthGroup.classList.remove('hidden');
            if (durationInput) {
                durationInput.removeAttribute('required');
                durationInput.setAttribute('aria-required', 'false');
                durationInput.value = '';
                clearFieldError(durationInput);
            }
        }
    }

    function updateExtraOptionsVisibility() {
        if (!licenseOption || !accessOption) {
            return;
        }

        if (typeInput.value === 'noleggio') {
            licenseOption.classList.remove('hidden');
            accessOption.classList.add('hidden');
            if (accessCheckbox) {
                accessCheckbox.checked = false;
                accessCheckbox.disabled = true;
            }
            if (licenseCheckbox) {
                licenseCheckbox.disabled = false;
            }
        } else if (typeInput.value === 'experience') {
            accessOption.classList.remove('hidden');
            licenseOption.classList.add('hidden');
            if (licenseCheckbox) {
                licenseCheckbox.checked = false;
                licenseCheckbox.disabled = true;
            }
            if (accessCheckbox) {
                accessCheckbox.disabled = false;
            }
        } else {
            licenseOption.classList.add('hidden');
            accessOption.classList.add('hidden');
            if (licenseCheckbox) {
                licenseCheckbox.disabled = true;
            }
            if (accessCheckbox) {
                accessCheckbox.disabled = true;
            }
        }
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
        const nameInputs = extrasContainer.querySelectorAll('input[name="extra_name[]"]');
        const priceInputs = extrasContainer.querySelectorAll('input[name="extra_price[]"]');
        const len = Math.max(nameInputs.length, priceInputs.length);
        let ok = true;

        for (let i = 0; i < len; i++) {
            const nameInput = nameInputs[i];
            const priceInput = priceInputs[i];
            const nameVal = nameInput ? nameInput.value.trim() : '';
            const priceVal = priceInput ? priceInput.value.trim() : '';

            if (nameVal === '' && priceVal === '') {
                if (nameInput) {
                    clearExtraError(nameInput, '.extra-name-error');
                }
                if (priceInput) {
                    clearExtraError(priceInput, '.extra-price-error');
                }
                continue;
            }

            if (!nameInput || nameVal === '') {
                if (nameInput) {
                    showExtraError(nameInput, 'Inserisci il nome extra', '.extra-name-error');
                }
                ok = false;
            } else {
                clearExtraError(nameInput, '.extra-name-error');
            }

            const priceInt = Number.parseInt(priceVal, 10);
            if (!priceInput || priceVal === '' || Number.isNaN(priceInt) || priceInt < 1) {
                if (priceInput) {
                    showExtraError(priceInput, 'Inserisci un prezzo valido', '.extra-price-error');
                }
                ok = false;
            } else {
                clearExtraError(priceInput, '.extra-price-error');
            }
        }

        return ok;
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
        const v10 = validateDuration();
        const v11 = validateBoatType();
        const v12 = validateLanguages();
        const v13 = validateExtras();

        return v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10 && v11 && v12 && v13;
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
    statusInput.addEventListener('change', validateStatus);
    if (durationInput) {
        durationInput.addEventListener('blur', validateDuration);
    }
    if (boatTypeInput) {
        boatTypeInput.addEventListener('blur', validateBoatType);
    }

    const inputs = [
        nameInput, typeInput, descriptionInput, longDescriptionInput,
        priceInput, capacityInput, imageInput, altInput, statusInput,
        boatTypeInput, durationInput
    ].filter(Boolean);

    inputs.forEach(function (input) {
        input.addEventListener('input', hideGlobalMessages);
    });

    typeInput.addEventListener('change', function () {
        updateExperienceFieldsVisibility();
        updateBoatTypeVisibility();
        updateExtraOptionsVisibility();
        updateLanguageFieldsetVisibility();
        hideGlobalMessages();
    });

    if (boatTypeInput) {
        boatTypeInput.addEventListener('change', function () {
            hideGlobalMessages();
            validateBoatType();
        });
    }

    languageCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            hideGlobalMessages();
            validateLanguages();
        });
    });

    if (extrasContainer) {
        extrasContainer.addEventListener('input', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            if (target.name === 'extra_name[]') {
                clearExtraError(target, '.extra-name-error');
                hideGlobalMessages();
            } else if (target.name === 'extra_price[]') {
                clearExtraError(target, '.extra-price-error');
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

    updateBoatTypeVisibility();
    updateExtraOptionsVisibility();
    updateExperienceFieldsVisibility();
    updateLanguageFieldsetVisibility();

})();
