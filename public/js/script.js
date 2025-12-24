document.addEventListener('DOMContentLoaded', () => {
    
/* ---------------------------------------
   1. GESTIONE MENU MOBILE (Hamburger)
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
    2. GESTIONE TEMA (Chiaro / Scuro)
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

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }
});

/* ---------------------------------------
   3. Gestione del bottone top
   --------------------------------------- */

   document.addEventListener("DOMContentLoaded", function() {
    var btn = document.getElementById("btnTop");

    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
            btn.classList.add("show");
            btn.setAttribute("tabindex", "0");
        } else {
            btn.classList.remove("show");
            btn.setAttribute("tabindex", "-1");
        }
    }
});

/* ---------------------------------------
   4. GESTIONE FILTRI PRENOTAZIONI
   --------------------------------------- */
   document.addEventListener('DOMContentLoaded', () => {
    // Eseguiamo solo se siamo nella pagina prenotazioni
    const bookingsTable = document.getElementById('bookings-table');
    if (!bookingsTable) return;

    const rows = document.querySelectorAll('.booking-row');
    const badgeAll = document.getElementById('badge-all');
    const badgeActive = document.getElementById('badge-active');
    const badgeCompleted = document.getElementById('badge-completed');
    const noBookingsMsg = document.getElementById('no-bookings-message');

    function updateCounters() {
        const total = rows.length;
        const activeCount = Array.from(rows).filter(r => r.getAttribute('data-status') === 'active').length;
        const completedCount = Array.from(rows).filter(r => r.getAttribute('data-status') === 'completed').length;

        if(badgeAll) badgeAll.textContent = total;
        if(badgeActive) badgeActive.textContent = activeCount;
        if(badgeCompleted) badgeCompleted.textContent = completedCount;
    }

    // Inizializza i contatori
    updateCounters();

    window.filterBookings = function(status) {
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Se non ci sono risultati
        if (visibleCount === 0) {
            bookingsTable.parentElement.classList.add('hidden'); // Nasconde il contenitore tabella e mostra messaggio vuoto
            noBookingsMsg.classList.remove('hidden');
        } else {
            bookingsTable.parentElement.classList.remove('hidden');
            noBookingsMsg.classList.add('hidden');
        }

        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
            
            if (btn.id === `tab-${status}`) {
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            }
        });
    };
});

/* ---------------------------------------
   5. FILTRI CATALOGO NOLEGGIO
   --------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const filtersForm = document.getElementById('noleggio-filters-form');
    if (!filtersForm) return;

    const sortSelect = document.getElementById('sort');
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
});
