(function () {
    'use strict';

    const form = document.getElementById('register-form');
    const nomeInput = document.getElementById('nome');
    const cognomeInput = document.getElementById('cognome');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const noteTextarea = document.getElementById('note');
    const privacyCheckbox = document.getElementById('privacy');
    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePhone(phone) {
        const re = /^(\+39)?[\s]?[0-9]{9,10}$/;
        return re.test(phone.replace(/\s/g, ''));
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
        errorSpan.textContent = message;
        input.classList.add('error');
        input.classList.remove('valid');
        input.setAttribute('aria-invalid', 'true');
    }

    function clearFieldError(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        errorSpan.textContent = '';
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
        messageDiv.focus();
    }

    function hideGlobalMessages() {
        errorMessageDiv.classList.add('hidden');
        successMessageDiv.classList.add('hidden');
    }

    function validateNome() {
        const nome = nomeInput.value.trim();

        if (nome === '') {
            showFieldError(nomeInput, 'Il nome è obbligatorio');
            return false;
        }

        if (nome.length < 2) {
            showFieldError(nomeInput, 'Il nome deve contenere almeno 2 caratteri');
            return false;
        }

        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(nome)) {
            showFieldError(nomeInput, 'Il nome contiene caratteri non validi');
            return false;
        }

        clearFieldError(nomeInput);
        return true;
    }

    function validateCognome() {
        const cognome = cognomeInput.value.trim();

        if (cognome === '') {
            showFieldError(cognomeInput, 'Il cognome è obbligatorio');
            return false;
        }

        if (cognome.length < 2) {
            showFieldError(cognomeInput, 'Il cognome deve contenere almeno 2 caratteri');
            return false;
        }

        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(cognome)) {
            showFieldError(cognomeInput, 'Il cognome contiene caratteri non validi');
            return false;
        }

        clearFieldError(cognomeInput);
        return true;
    }

    function validateEmailField() {
        const email = emailInput.value.trim();

        if (email === '') {
            showFieldError(emailInput, 'L\'email è obbligatoria');
            return false;
        }

        if (!validateEmail(email)) {
            showFieldError(emailInput, 'Inserisci un\'email valida');
            return false;
        }

        clearFieldError(emailInput);
        return true;
    }

    function validateTelefono() {
        const telefono = telefonoInput.value.trim();

        if (telefono === '') {
            showFieldError(telefonoInput, 'Il telefono è obbligatorio');
            return false;
        }

        if (!validatePhone(telefono)) {
            showFieldError(telefonoInput, 'Inserisci un numero di telefono valido (es. +39 123 456 7890)');
            return false;
        }

        clearFieldError(telefonoInput);
        return true;
    }

    function validatePasswordField() {
        const password = passwordInput.value;

        if (password === '') {
            showFieldError(passwordInput, 'La password è obbligatoria');
            return false;
        }

        if (!validatePasswordStrength(password)) {
            showFieldError(passwordInput, 'La password deve contenere almeno 8 caratteri, una maiuscola, una minuscola e un numero');
            return false;
        }

        clearFieldError(passwordInput);
        return true;
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword === '') {
            showFieldError(confirmPasswordInput, 'Conferma la password');
            return false;
        }

        if (password !== confirmPassword) {
            showFieldError(confirmPasswordInput, 'Le password non corrispondono');
            return false;
        }

        clearFieldError(confirmPasswordInput);
        return true;
    }

    function validateNote() {
        const note = noteTextarea.value.trim();

        if (note.length > 500) {
            showFieldError(noteTextarea, 'Le note non possono superare i 500 caratteri');
            return false;
        }

        clearFieldError(noteTextarea);
        return true;
    }

    function validatePrivacy() {
        if (!privacyCheckbox.checked) {
            showFieldError(privacyCheckbox, 'Devi accettare la Privacy Policy per continuare');
            return false;
        }

        clearFieldError(privacyCheckbox);
        return true;
    }

    function validateForm() {
        hideGlobalMessages();

        const isNomeValid = validateNome();
        const isCognomeValid = validateCognome();
        const isEmailValid = validateEmailField();
        const isTelefonoValid = validateTelefono();
        const isPasswordValid = validatePasswordField();
        const isConfirmPasswordValid = validateConfirmPassword();
        const isNoteValid = validateNote();
        const isPrivacyValid = validatePrivacy();

        return isNomeValid && isCognomeValid && isEmailValid && isTelefonoValid &&
            isPasswordValid && isConfirmPasswordValid && isNoteValid && isPrivacyValid;
    }

    nomeInput.addEventListener('blur', validateNome);
    cognomeInput.addEventListener('blur', validateCognome);
    emailInput.addEventListener('blur', validateEmailField);
    telefonoInput.addEventListener('blur', validateTelefono);
    passwordInput.addEventListener('blur', validatePasswordField);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    noteTextarea.addEventListener('blur', validateNote);
    privacyCheckbox.addEventListener('change', validatePrivacy);

    passwordInput.addEventListener('input', function () {
        if (confirmPasswordInput.value !== '') {
            validateConfirmPassword();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            form.submit();
        } else {
            showGlobalMessage('Correggi gli errori nel form prima di continuare', 'error');

            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    const inputs = [nomeInput, cognomeInput, emailInput, telefonoInput, passwordInput, confirmPasswordInput];
    inputs.forEach(function (input) {
        input.addEventListener('input', hideGlobalMessages);
    });

})();