(function () {
    'use strict';

    const form = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorMessageDiv = document.getElementById('error-message');

    if (!form || !emailInput || !passwordInput || !errorMessageDiv) {
        console.warn('Login validation: missing form elements, aborting initialization.');
        return;
    }

    function validateEmail(email) {
        // Accetta qualsiasi email (basta che non sia vuota) per fini didattici
        return true;
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

    function showGlobalError(message) {
        errorMessageDiv.textContent = message;
        errorMessageDiv.classList.remove('hidden');
        if (!errorMessageDiv.hasAttribute('tabindex')) {
            errorMessageDiv.setAttribute('tabindex', '-1');
        }
        errorMessageDiv.focus();
    }

    function hideGlobalError() {
        errorMessageDiv.textContent = '';
        errorMessageDiv.classList.add('hidden');
    }


    function validateEmailField() {
        const email = emailInput.value.trim();

        if (email === '') {
            showFieldError(emailInput, 'L\'email è obbligatoria');
            return false;
        }
        clearFieldError(emailInput);
        return true;
    }

    // Accetta qualsiasi password (basta che non sia vuota) per fini didattici
    function validatePasswordField() {
        const password = passwordInput.value;

        if (password === '') {
            showFieldError(passwordInput, 'La password è obbligatoria');
            return false;
        }
        clearFieldError(passwordInput);
        return true;
    }

    function validateForm() {
        hideGlobalError();

        const isEmailValid = validateEmailField();
        const isPasswordValid = validatePasswordField();

        return isEmailValid && isPasswordValid;
    }

    emailInput.addEventListener('blur', validateEmailField);
    passwordInput.addEventListener('blur', validatePasswordField);

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            form.submit();
        } else {
            showGlobalError('Il form non è stato compilato correttamente!\n Correggere prima di continuare');
        }
    });
})();