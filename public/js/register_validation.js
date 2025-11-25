(function () {
    'use strict';

    const form = document.getElementById('register-form');
    
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const cfInput = document.getElementById('cf');
    const emailInput = document.getElementById('email');
    
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    
    const viaInput = document.getElementById('indirizzo_via');
    const civicoInput = document.getElementById('indirizzo_civico');
    const capInput = document.getElementById('indirizzo_cap');
    const cittaInput = document.getElementById('indirizzo_citta');
    const provinciaInput = document.getElementById('indirizzo_provincia');
    
    const privacyCheckbox = document.getElementById('privacy');
    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    if (!form || !nomeInput || !cognomeInput || !cfInput || !emailInput || 
        !passwordInput || !confirmPasswordInput || !viaInput || !civicoInput || 
        !capInput || !cittaInput || !provinciaInput || !privacyCheckbox || 
        !errorMessageDiv) {
        console.warn('Register validation: missing form elements, aborting initialization.');
        return;
    }

    function validateEmailRegex(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePasswordStrength(password) {
        const minLength = 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumber;
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

    function validateNome() {
        const val = nomeInput.value.trim();
        if (val === '') { showFieldError(nomeInput, 'Il nome è obbligatorio'); return false; }
        if (val.length < 2) { showFieldError(nomeInput, 'Minimo 2 caratteri'); return false; }
        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(val)) { showFieldError(nomeInput, 'Caratteri non validi'); return false; }
        clearFieldError(nomeInput); return true;
    }

    function validateCognome() {
        const val = cognomeInput.value.trim();
        if (val === '') { showFieldError(cognomeInput, 'Il cognome è obbligatorio'); return false; }
        if (val.length < 2) { showFieldError(cognomeInput, 'Minimo 2 caratteri'); return false; }
        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(val)) { showFieldError(cognomeInput, 'Caratteri non validi'); return false; }
        clearFieldError(cognomeInput); return true;
    }

    function validateCF() {
        const val = cfInput.value.trim().toUpperCase();
        if (val === '') { showFieldError(cfInput, 'Il Codice Fiscale è obbligatorio'); return false; }
        if (!/^[A-Z0-9]{16}$/.test(val)) {
            showFieldError(cfInput, 'Il Codice Fiscale deve essere di 16 caratteri alfanumerici');
            return false;
        }
        clearFieldError(cfInput); return true;
    }

    function validateEmailField() {
        const val = emailInput.value.trim();
        if (val === '') { showFieldError(emailInput, 'L\'email è obbligatoria'); return false; }
        if (!validateEmailRegex(val)) { showFieldError(emailInput, 'Inserisci un\'email valida'); return false; }
        clearFieldError(emailInput); return true;
    }

    function validatePasswordField() {
        const val = passwordInput.value;
        if (val === '') { showFieldError(passwordInput, 'La password è obbligatoria'); return false; }
        if (!validatePasswordStrength(val)) {
            showFieldError(passwordInput, 'Minimo 8 caratteri, una maiuscola, una minuscola e un numero');
            return false;
        }
        clearFieldError(passwordInput); return true;
    }

    function validateConfirmPassword() {
        const pass = passwordInput.value;
        const confirm = confirmPasswordInput.value;
        if (confirm === '') { showFieldError(confirmPasswordInput, 'Conferma la password'); return false; }
        if (pass !== confirm) { showFieldError(confirmPasswordInput, 'Le password non corrispondono'); return false; }
        clearFieldError(confirmPasswordInput); return true;
    }

    function validateVia() {
        const val = viaInput.value.trim();
        if (val === '') { showFieldError(viaInput, 'Inserisci via o piazza'); return false; }
        if (val.length < 3) { showFieldError(viaInput, 'Indirizzo troppo breve'); return false; }
        clearFieldError(viaInput); return true;
    }

    function validateCivico() {
        const val = civicoInput.value.trim();
        if (val === '') { showFieldError(civicoInput, 'Richiesto'); return false; }
        clearFieldError(civicoInput); return true;
    }

    function validateCap() {
        const val = capInput.value.trim();
        if (val === '') { showFieldError(capInput, 'Inserisci CAP'); return false; }
        if (!/^\d{5}$/.test(val)) { showFieldError(capInput, 'Il CAP deve essere di 5 cifre'); return false; }
        clearFieldError(capInput); return true;
    }

    function validateCitta() {
        const val = cittaInput.value.trim();
        if (val === '') { showFieldError(cittaInput, 'Inserisci la città'); return false; }
        clearFieldError(cittaInput); return true;
    }

    function validateProvincia() {
        const val = provinciaInput.value.trim().toUpperCase();
        if (val === '') { showFieldError(provinciaInput, 'Inserisci provincia'); return false; }
        if (!/^[A-Z]{2}$/.test(val)) { showFieldError(provinciaInput, 'Usa 2 lettere (es. NA)'); return false; }
        clearFieldError(provinciaInput); return true;
    }

    function validatePrivacy() {
        if (!privacyCheckbox.checked) {
            showFieldError(privacyCheckbox, 'Devi accettare la Privacy Policy');
            return false;
        }
        clearFieldError(privacyCheckbox); return true;
    }

    function validateForm() {
        hideGlobalMessages();

        const v1 = validateNome();
        const v2 = validateCognome();
        const v3 = validateCF();
        const v4 = validateEmailField();
        const v5 = validatePasswordField();
        const v6 = validateConfirmPassword();
        const v7 = validateVia();
        const v8 = validateCivico();
        const v9 = validateCap();
        const v10 = validateCitta();
        const v11 = validateProvincia();
        const v12 = validatePrivacy();

        return v1 && v2 && v3 && v4 && v5 && v6 && v7 && v8 && v9 && v10 && v11 && v12;
    }

    nomeInput.addEventListener('blur', validateNome);
    cognomeInput.addEventListener('blur', validateCognome);
    cfInput.addEventListener('blur', validateCF);
    emailInput.addEventListener('blur', validateEmailField);
    passwordInput.addEventListener('blur', validatePasswordField);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    
    viaInput.addEventListener('blur', validateVia);
    civicoInput.addEventListener('blur', validateCivico);
    capInput.addEventListener('blur', validateCap);
    cittaInput.addEventListener('blur', validateCitta);
    provinciaInput.addEventListener('blur', validateProvincia);
    
    privacyCheckbox.addEventListener('change', validatePrivacy);

    passwordInput.addEventListener('input', function () {
        if (confirmPasswordInput.value !== '') validateConfirmPassword();
    });

    cfInput.addEventListener('input', function () { this.value = this.value.toUpperCase(); });
    provinciaInput.addEventListener('input', function () { this.value = this.value.toUpperCase(); });

    const inputs = [
        nomeInput, cognomeInput, cfInput, emailInput,
        passwordInput, confirmPasswordInput,
        viaInput, civicoInput, capInput, cittaInput, provinciaInput
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