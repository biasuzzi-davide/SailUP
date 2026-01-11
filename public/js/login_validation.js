(function () {
    'use strict';

    const form = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorMessageDiv = document.getElementById('error-message');

    if (!form || !emailInput || !passwordInput || !errorMessageDiv) {
        return;
    }

    // Funzione helper per gestire aria-describedby senza sovrascrivere gli hint
    function updateAriaDescribedBy(input, errorId, isError) {
        // Salviamo l'aria-describedby originale (quello dell'hint) se non l'abbiamo già fatto
        if (!input.getAttribute('data-original-describedby')) {
            const original = input.getAttribute('aria-describedby') || '';
            input.setAttribute('data-original-describedby', original);
        }

        const originalDescribedBy = input.getAttribute('data-original-describedby');
        
        if (isError) {
            // Aggiungiamo l'ID dell'errore alla descrizione
            input.setAttribute('aria-describedby', `${originalDescribedBy} ${errorId}`.trim());
        } else {
            // Ripristiniamo solo l'hint originale
            if (originalDescribedBy) {
                input.setAttribute('aria-describedby', originalDescribedBy);
            } else {
                input.removeAttribute('aria-describedby');
            }
        }
    }

    function showFieldError(input, message) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = message;
            // Assicura che lo screen reader lo rilevi anche se nascosto via CSS
            errorSpan.style.display = 'block'; 
        }
        
        input.classList.add('error');
        input.classList.remove('valid');
        input.setAttribute('aria-invalid', 'true');
        
        // Collega semanticamente l'errore all'input
        updateAriaDescribedBy(input, input.id + '-error', true);
    }

    function clearFieldError(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) {
            errorSpan.textContent = '';
            // Nascondi visivamente per pulizia (dipende dal tuo CSS, ma questo è sicuro)
            errorSpan.style.display = ''; 
        }
        
        input.classList.remove('error');
        input.classList.add('valid');
        input.setAttribute('aria-invalid', 'false');
        
        // Rimuove il collegamento all'errore
        updateAriaDescribedBy(input, input.id + '-error', false);
    }

    function showGlobalError(message) {
        errorMessageDiv.innerHTML = `<p>${message}</p>`; // Usa paragrafo per struttura
        errorMessageDiv.classList.remove('hidden');
        errorMessageDiv.focus();
    }

    function hideGlobalError() {
        errorMessageDiv.textContent = '';
        errorMessageDiv.classList.add('hidden');
    }

    function validateEmailField() {
        const email = emailInput.value.trim();
        if (email === '') {
            showFieldError(emailInput, 'L\'indirizzo email è obbligatorio.');
            return false;
        }
        // Nota: rimuoviamo validazione regex complessa lato client per login (UX pattern comune), 
        // lasciamo che sia il server a decidere se esiste o no, ma controlliamo che ci sia qualcosa.
        clearFieldError(emailInput);
        return true;
    }

    function validatePasswordField() {
        const password = passwordInput.value;
        if (password === '') {
            showFieldError(passwordInput, 'La password è obbligatoria.');
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

    // Event Listeners
    emailInput.addEventListener('blur', validateEmailField);
    passwordInput.addEventListener('blur', validatePasswordField);
    
    // Pulisce l'errore mentre l'utente digita
    emailInput.addEventListener('input', () => clearFieldError(emailInput));
    passwordInput.addEventListener('input', () => clearFieldError(passwordInput));

    form.addEventListener('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault();
            
            showGlobalError('Impossibile accedere. Verifica i campi evidenziati in rosso.');
            
            // FOCUS MANAGEMENT: Porta l'utente al primo errore
            const firstError = form.querySelector('[aria-invalid="true"]');
            if (firstError) {
                firstError.focus();
            }
        }
        // Se valido, lascia procedere il submit nativo
    });
})();