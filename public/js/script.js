document.addEventListener('DOMContentLoaded', () => {

    /* ---------------------------------------
       GESTIONE NAVIGAZIONE BUTTON CON DATA-HREF
       --------------------------------------- */
    document.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-href]');
        if (button) {
            const href = button.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        }
    });

    /* ---------------------------------------
       GESTIONE MENU MOBILE (Hamburger)
       --------------------------------------- */

    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    const openIcon = document.querySelector('.hamburger-icon'); // L'icona "☰"
    const closeIcon = document.querySelector('.close-icon');    // L'icona "✕"

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', () => {
            const isExpanded = hamburgerBtn.getAttribute('aria-expanded') === 'true';

            hamburgerBtn.setAttribute('aria-expanded', !isExpanded);

            mobileMenu.classList.toggle('active');

            if (!isExpanded) {
                openIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                openIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
    }

    /* ---------------------------------------
        GESTIONE TEMA (Chiaro / Scuro)
       --------------------------------------- */
    const themeToggle = document.querySelector('.theme-toggle');
    const htmlElement = document.documentElement;
    const sunIcon = document.querySelector('.sun');
    const moonIcon = document.querySelector('.moon');

    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    const applyTheme = (theme) => {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        if (theme === 'dark') {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
            themeToggle.setAttribute('aria-label', 'Passa al tema chiaro');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
            themeToggle.setAttribute('aria-label', 'Passa al tema scuro');
        }
    };

    if (savedTheme) {
        applyTheme(savedTheme);
    } else if (systemPrefersDark) {
        applyTheme('dark');
    } else {
        applyTheme('light');
    }

    document.addEventListener('click', (event) => {
        const button = event.target.closest('.password-toggle');
        if (!button) {
            return;
        }

        event.preventDefault();

        const targetId = button.getAttribute('data-target');
        const targetInput = targetId ? document.getElementById(targetId) : null;
        if (!targetInput) {
            return;
        }

        const labelShow = button.getAttribute('data-label-show') || 'Mostra password';
        const labelHide = button.getAttribute('data-label-hide') || 'Nascondi password';

        const isHidden = targetInput.type === 'password';
        targetInput.type = isHidden ? 'text' : 'password';
        button.classList.toggle('is-visible', isHidden);
        button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        button.setAttribute('aria-label', isHidden ? labelHide : labelShow);
    });

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }
});

/* ---------------------------------------
   Gestione del bottone top
   --------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btnTop");

    if (btn) {
        btn.classList.add("hidden");
        btn.setAttribute("tabindex", "-1");
        
        window.addEventListener('scroll', function () {
            if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
                btn.classList.remove("hidden");
                btn.setAttribute("tabindex", "0");
            } else {
                btn.classList.add("hidden");
                btn.setAttribute("tabindex", "-1");
            }
        });
    }
});

/* ---------------------------------------
   GESTIONE FILTRI PRENOTAZIONI
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    // Eseguiamo solo se siamo nella pagina prenotazioni
    const bookingsTable = document.getElementById('bookings-table');
    if (!bookingsTable) return;

    const rows = document.querySelectorAll('.booking-row');
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    const badgeAll = document.getElementById('badge-all');
    const badgeActive = document.getElementById('badge-active');
    const badgeCompleted = document.getElementById('badge-completed');
    
    const noBookingsMsg = document.getElementById('no-bookings-message');
    const tableContainer = bookingsTable.closest('.table-container') || bookingsTable.parentElement;

    function updateCounters() {
        const total = rows.length;
        const activeCount = Array.from(rows).filter(r => r.getAttribute('data-status') === 'active').length;
        const completedCount = Array.from(rows).filter(r => r.getAttribute('data-status') === 'completed').length;

        if (badgeAll) badgeAll.textContent = total;
        if (badgeActive) badgeActive.textContent = activeCount;
        if (badgeCompleted) badgeCompleted.textContent = completedCount;
    }

    function applyFilter(status) {
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            
            if (status === 'all' || rowStatus === status) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            if(tableContainer) tableContainer.classList.add('hidden');
            if(noBookingsMsg) noBookingsMsg.classList.remove('hidden');
        } else {
            if(tableContainer) tableContainer.classList.remove('hidden');
            if(noBookingsMsg) noBookingsMsg.classList.add('hidden');
        }

        filterTabs.forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-pressed', 'false');

            if (btn.getAttribute('data-filter') === status) {
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');
            }
        });
    }

    updateCounters();
    applyFilter('all');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filterValue = this.getAttribute('data-filter');
            if (filterValue) {
                applyFilter(filterValue);
            }
        });
    });
});

/* ---------------------------------------
   FILTRI CATALOGO (Noleggio + Esperienze)
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const setupCatalogFilters = (formId) => {
        const filtersForm = document.getElementById(formId);
        if (!filtersForm) return;

        const sortSelect = document.querySelector(`select#sort[form="${formId}"]`) || filtersForm.querySelector('select[name="sort"]');

        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                if (typeof filtersForm.requestSubmit === 'function') {
                    filtersForm.requestSubmit();
                } else {
                    filtersForm.submit();
                }
            });
        }

        const handleReset = () => {
            if (sortSelect) {
                sortSelect.value = sortSelect.options[0]?.value || '';
            }
            setTimeout(() => {
                window.location.href = window.location.pathname;
            }, 0);
        };

        filtersForm.addEventListener('reset', () => {
            handleReset();
        });
    };

    setupCatalogFilters('noleggio-filters-form');
    setupCatalogFilters('esperienze-filters-form');
});

/* ---------------------------------------
   Date persistenti dai cataloghi
   --------------------------------------- */

document.addEventListener('DOMContentLoaded', () => {
    let storage;
    try {
        storage = window.sessionStorage;
    } catch {
        storage = null;
    }
    if (!storage) return;

    const catalogDateKeys = {
        experience: 'catalog_experience_date',
        rentalStart: 'catalog_rental_start_date',
        rentalEnd: 'catalog_rental_end_date'
    };

    const persistInputValue = (selector, key) => {
        const input = document.querySelector(selector);
        if (!input) return;

        const saveValue = () => {
            const value = input.value.trim();
            if (value) {
                storage.setItem(key, value);
            } else {
                storage.removeItem(key);
            }
        };

        input.addEventListener('change', saveValue);
        saveValue();
    };

    persistInputValue('#data_esperienza', catalogDateKeys.experience);
    persistInputValue('#data_inizio', catalogDateKeys.rentalStart);
    persistInputValue('#data_fine', catalogDateKeys.rentalEnd);

    document.addEventListener('reset', (event) => {
        if (!event.target) return;
        if (event.target.id === 'esperienze-filters-form') {
            storage.removeItem(catalogDateKeys.experience);
        } else if (event.target.id === 'noleggio-filters-form') {
            storage.removeItem(catalogDateKeys.rentalStart);
            storage.removeItem(catalogDateKeys.rentalEnd);
        }
    });

    const hydrateBookingInput = (selector, key) => {
        const input = document.querySelector(selector);
        const value = storage.getItem(key);
        if (input && value) {
            input.value = value;
        }
    };

    hydrateBookingInput('#booking-date', catalogDateKeys.experience);
    hydrateBookingInput('#start-date', catalogDateKeys.rentalStart);
    hydrateBookingInput('#end-date', catalogDateKeys.rentalEnd);
});

/* ---------------------------------------
   GESTIONE EXTRA (Blog e Prodotti Admin)
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const renumberExtras = (container) => {
        const rows = container.querySelectorAll('.extra-row');
        rows.forEach((row, i) => {
            const num = i + 1;
            const legend = row.querySelector('legend');
            if (legend) {
                legend.textContent = 'Extra ' + num;
            }
            const removeBtn = row.querySelector('.remove-extra');
            if (removeBtn) {
                removeBtn.setAttribute('aria-label', 'Rimuovi extra ' + num);
            }
        });
    };

    const setupExtrasManager = (addBtnId, containerId, templateId) => {
        const addBtn = document.getElementById(addBtnId);
        const container = document.getElementById(containerId);
        const template = document.getElementById(templateId);

        if (!container || !addBtn || !template || !('content' in template)) return;

        container.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) return;
            if (!target.classList.contains('remove-extra')) return;
            const row = target.closest('.extra-row');
            if (row) {
                row.remove();
                renumberExtras(container);
            }
        });

        addBtn.addEventListener('click', () => {
            const fragment = template.content.cloneNode(true);
            container.appendChild(fragment);
            renumberExtras(container);
        });
    };

    setupExtrasManager('add-extra', 'extras-container', 'extra-row-template');
    setupExtrasManager('add-product-extra', 'product-extras-container', 'product-extra-template');
});

/* ---------------------------------------
   7. Calcolo prezzi form prenotazione
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {

    const bookingForm = document.querySelector('.booking-form');
    const allPriceDisplays = document.querySelectorAll('.price-amount');

    if (!bookingForm || allPriceDisplays.length === 0) return;

    let rawBaseText = allPriceDisplays[0].innerText;
    let cleanBase = rawBaseText.replace(/[^0-9,-]/g, '').replace(',', '.');
    const BASE_PRICE = parseFloat(cleanBase) || 0;

    function updateBookingPrice() {
        let duration = 1;
        const startInput = bookingForm.querySelector('input[name="booking_date"], input[name="start_date"]');
        const endInput = bookingForm.querySelector('input[name="end_date"]');

        if (startInput && endInput && startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays >= 0) {
                duration = diffDays + 1;
            }
        }

        let currentTotal = BASE_PRICE * duration;

        const checkedInputs = bookingForm.querySelectorAll('input[type="checkbox"]:checked');
        let fixedSum = 0;
        let percentSum = 0;

        checkedInputs.forEach(input => {
            if (!input.id) return;
            const label = bookingForm.querySelector(`label[for="${input.id}"]`);
            if (!label) return;

            const priceEl = label.querySelector('.text-accent') || label;
            let text = priceEl.innerText.trim();
            const isPercent = text.includes('%');

            let cleanNum = text.replace(/[^0-9,-]/g, '');
            let val = parseFloat(cleanNum.replace(',', '.'));

            if (!isNaN(val) && val > 0) {
                if (isPercent) {
                    percentSum += currentTotal * (val / 100);
                } else {
                    fixedSum += val;
                }
            }
        });

        let finalPrice = currentTotal + fixedSum + percentSum;

        let formattedPrice = finalPrice.toLocaleString('it-IT', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }) + " €";

        const isChanged = Math.abs(finalPrice - BASE_PRICE) > 0.01;

        allPriceDisplays.forEach(display => {
            display.innerText = formattedPrice;

            const container = display.closest('.price-label') || display.parentElement;

            if (container) {
                const prefix = container.querySelector('.price-prefix');
                const suffix = container.querySelector('.price-suffix');

                if (prefix) {
                    if (!prefix.hasAttribute('data-original')) {
                        prefix.setAttribute('data-original', prefix.innerText);
                    }

                    if (isChanged) {
                        prefix.innerText = 'Prezzo totale';
                    } else {
                        let original = prefix.getAttribute('data-original');
                        prefix.innerText = original.replace('€', '').trim();
                    }
                }

                if (suffix) {
                    if (isChanged) {
                        suffix.classList.add('hidden');
                    } else {
                        suffix.classList.remove('hidden');
                    }
                }
            }
        });
    }

    bookingForm.addEventListener('change', updateBookingPrice);

    bookingForm.addEventListener('input', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
            updateBookingPrice();
        }
    });

    bookingForm.addEventListener('reset', function () {
        setTimeout(updateBookingPrice, 10);
    });

    updateBookingPrice();
});

/* ---------------------------------------
   CONFERMA ELIMINAZIONE ACCOUNT
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {
    const deleteAccountForm = document.getElementById('delete-account-form');
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener('submit', (e) => {
            const confirmed = confirm('Sei sicuro di voler eliminare il tuo account? Questa azione è irreversibile e tutti i tuoi dati saranno cancellati definitivamente.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    }
});

/* ---------------------------------------
   GESTIONE DIMENSIONE CARICAMENTO IMMAGINI
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {
    const changePhotoBtn = document.getElementById('change-photo-btn');
    const fileInput = document.getElementById('profile-image-input');
    const avatarImg = document.getElementById('profile-picture');
    const hint = document.getElementById('profile-image-hint');
    let previewUrl = null; 

    if (!changePhotoBtn || !fileInput || !avatarImg) return;

    const originalHintText = "Seleziona un'immagine dal tuo dispositivo (max 1MB)";

    changePhotoBtn.addEventListener("click", () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const [file] = fileInput.files;

        if (!file) return;

        const MAX_DIM = 1024 * 1024;

        if (hint) {
            hint.textContent = originalHintText;
            hint.classList.remove('field-error', 'field-success');
            hint.classList.add('field-hint');
        }

        if (file.size > MAX_DIM) {
            if (hint) {
                hint.textContent = "Errore: l'immagine supera il limite di 1MB";
                hint.classList.remove('field-hint');
                hint.classList.add('field-error');
            }
            fileInput.value = "";
            return;
        }

        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            if (hint) {
                hint.textContent = "Errore: usa formati JPG, PNG o WebP";
                hint.classList.remove('field-hint');
                hint.classList.add('field-error');
            }
            fileInput.value = "";
            return;
        }

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }
        
        previewUrl = URL.createObjectURL(file);
        avatarImg.src = previewUrl;
        avatarImg.alt = "Anteprima nuova immagine selezionata";

        if (hint) {
            hint.textContent = "Immagine valida. Ricorda di salvare le modifiche.";
            hint.classList.remove('field-hint');
            hint.classList.add('field-success');
        }
    });
});

/* ---------------------------------------
   GESTIONE CONFERME PER AZIONI ADMIN
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {
    // Messaggi di conferma in base al tipo di azione
    const confirmMessages = {
        'delete-user': 'Eliminare definitivamente questo utente? Questa azione cancellerà anche tutte le prenotazioni, articoli blog e media associati.',
        'delete-article': 'Eliminare questo articolo?',
        'delete-product': 'Eliminare definitivamente questo prodotto?',
        'toggle-product': 'Disattivare questo prodotto?',
        'cancel-booking': 'Cancellare questa prenotazione?',
        'cancel-user-booking': 'Annullare questa prenotazione?'
    };

    // Gestione submit dei form con data-confirm-type
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const confirmType = form.getAttribute('data-confirm-type');
        
        if (confirmType && confirmMessages[confirmType]) {
            const confirmed = confirm(confirmMessages[confirmType]);
            if (!confirmed) {
                e.preventDefault();
            }
        }
    });
});

/* ---------------------------------------
   SINCRONIZZAZIONE STATISTICHE
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const elTot = document.querySelector('[data-stat="tot"]');
    const elAtt = document.querySelector('[data-stat="attive"]');

    if (!elTot || !elAtt) return;

    const fixStats = () => {
        if (elTot.dataset.value) elTot.textContent = elTot.dataset.value;
        if (elAtt.dataset.value) elAtt.textContent = elAtt.dataset.value;
    };

    elTot.dataset.value = elTot.textContent;
    elAtt.dataset.value = elAtt.textContent;

    const observer = new MutationObserver(fixStats);
    [elTot, elAtt].forEach(el => {
        observer.observe(el, { childList: true, characterData: true, subtree: true });
    });

    setTimeout(() => observer.disconnect(), 5000);
});

/* ---------------------------------------
   SCROLL AI MESSAGGI DEL SERVER
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const serverMessage = document.getElementById('server-message');
    if (serverMessage && !serverMessage.classList.contains('hidden') && serverMessage.textContent.trim() !== '') {
        setTimeout(() => {
            serverMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            serverMessage.focus();
        }, 100);
    }

    const serverMessages = document.getElementById('server-messages');
    if (serverMessages && !serverMessages.classList.contains('hidden') && serverMessages.textContent.trim() !== '') {
        setTimeout(() => {
            serverMessages.scrollIntoView({ behavior: 'smooth', block: 'center' });
            serverMessages.focus();
        }, 100);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const reloadBtn = document.getElementById('reload-page-btn');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', () => {
            location.reload();
        });
    }
});