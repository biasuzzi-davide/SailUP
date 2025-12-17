(function () {
    'use strict';

    const form = document.getElementById('payment-form');
    
    const inputs = {
        cardHolder: document.getElementById('card-holder'),
        cardNumber: document.getElementById('card-number'),
        expiryDate: document.getElementById('expiry-date'),
        cvv: document.getElementById('cvv'),
        terms: document.getElementById('terms')
    };

    const errorMessageDiv = document.getElementById('error-message');
    const successMessageDiv = document.getElementById('success-message');

    if (!form) return;

    function showFieldError(input, message) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) errorSpan.textContent = message;
        input.classList.add('error');
        input.setAttribute('aria-invalid', 'true');
    }

    function clearFieldError(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        if (errorSpan) errorSpan.textContent = '';
        input.classList.remove('error');
        input.setAttribute('aria-invalid', 'false');
    }

    function showGlobalMessage(message, type) {
        const activeDiv = type === 'error' ? errorMessageDiv : successMessageDiv;
        const inactiveDiv = type === 'error' ? successMessageDiv : errorMessageDiv;

        if (activeDiv) {
            activeDiv.textContent = message;
            activeDiv.classList.remove('hidden');
            activeDiv.focus();
        }
        if (inactiveDiv) {
            inactiveDiv.classList.add('hidden');
            inactiveDiv.textContent = '';
        }
    }

    function hideGlobalMessages() {
        if (errorMessageDiv) errorMessageDiv.classList.add('hidden');
        if (successMessageDiv) successMessageDiv.classList.add('hidden');
    }

    function validateCardHolder() {
        const input = inputs.cardHolder;
        if (!input) return true;
        const value = input.value.trim();
        if (value.length < 3) {
            showFieldError(input, 'Inserisci nome e cognome');
            return false;
        }
        if (!/^[A-Za-zÀ-ÿ\s'\-]+$/.test(value)) {
            showFieldError(input, 'Caratteri non validi nel nome');
            return false;
        }
        clearFieldError(input);
        return true;
    }

    function validateCardNumber() {
        const input = inputs.cardNumber;
        if (!input) return true;
        const value = input.value.replace(/\s/g, '');
        if (!/^\d+$/.test(value)) {
            showFieldError(input, 'Solo numeri consentiti');
            return false;
        }
        if (value.length < 13 || value.length > 19) {
            showFieldError(input, 'Numero carta non valido (13-19 cifre)');
            return false;
        }
        clearFieldError(input);
        return true;
    }

    function validateExpiryDate() {
        const input = inputs.expiryDate;
        if (!input) return true;
        const value = input.value.trim();
        if (!/^\d{2}\/\d{2}$/.test(value)) {
            showFieldError(input, 'Formato richiesto: MM/AA');
            return false;
        }
        const [month, year] = value.split('/').map(Number);
        if (month < 1 || month > 12) {
            showFieldError(input, 'Mese non valido');
            return false;
        }
        const currentYearShort = new Date().getFullYear() % 100; 
        if (year < currentYearShort) {
            showFieldError(input, 'Carta scaduta');
            return false;
        }
        clearFieldError(input);
        return true;
    }

    function validateCVV() {
        const input = inputs.cvv;
        if (!input) return true;
        const value = input.value.trim();
        if (!/^\d{3,4}$/.test(value)) {
            showFieldError(input, 'CVV deve essere di 3 o 4 cifre');
            return false;
        }
        clearFieldError(input);
        return true;
    }

    function validateTerms() {
        const input = inputs.terms;
        if (!input) return true;
        if (!input.checked) {
            showFieldError(input, 'Devi accettare i termini e condizioni');
            return false;
        }
        clearFieldError(input);
        return true;
    }

    // --- FORMATTAZIONE AUTOMATICA ---
    if (inputs.cardNumber) {
        inputs.cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 19);
            let formatted = value.match(/.{1,4}/g);
            e.target.value = formatted ? formatted.join(' ') : value;
            hideGlobalMessages();
        });
    }

    if (inputs.expiryDate) {
        inputs.expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            hideGlobalMessages();
        });
    }

    if (inputs.cvv) {
        inputs.cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
            hideGlobalMessages();
        });
    }

    inputs.cardHolder?.addEventListener('blur', validateCardHolder);
    inputs.cardNumber?.addEventListener('blur', validateCardNumber);
    inputs.expiryDate?.addEventListener('blur', validateExpiryDate);
    inputs.cvv?.addEventListener('blur', validateCVV);
    inputs.terms?.addEventListener('change', validateTerms);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        hideGlobalMessages();

        const validations = [
            validateCardHolder(),
            validateCardNumber(),
            validateExpiryDate(),
            validateCVV(),
            validateTerms()
        ];

        const isFormValid = validations.every(result => result === true);

        if (isFormValid) {
            showGlobalMessage('Pagamento confermato! Reindirizzamento...', 'success');
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if(submitBtn) submitBtn.disabled = true;

            setTimeout(function() {
                window.location.href = 'conferma_prenotazione.html';
            }, 1500);

        } else {
            showGlobalMessage('Per favore correggi gli errori evidenziati.', 'error');
            
            const firstError = form.querySelector('[aria-invalid="true"]');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

})();