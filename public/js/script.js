document.addEventListener('DOMContentLoaded', () => {
    
/* ---------------------------------------
   1. GESTIONE MENU MOBILE (Hamburger)
   --------------------------------------- */

    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    // Selettori per le icone dentro il bottone
    const openIcon = document.querySelector('.hamburger-icon'); // L'icona "☰"
    const closeIcon = document.querySelector('.close-icon');    // L'icona "✕"

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', () => {
            const isExpanded = hamburgerBtn.getAttribute('aria-expanded') === 'true';
            
            // 1. Toggle stato ARIA per accessibilità
            hamburgerBtn.setAttribute('aria-expanded', !isExpanded);
            
            // 2. Toggle Visibilità Menu 
            // (Nota: Assicurati che nel CSS esista la regola: #mobile-menu.active { display: block; })
            mobileMenu.classList.toggle('active');
            
            // 3. Toggle Icone usando la classe .hidden definita nel CSS
            if (!isExpanded) {
                openIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Blocca lo scroll della pagina
            } else {
                openIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                document.body.style.overflow = ''; 
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
        // Quando vengono superati 150px mostra il bottone e lo rende raggiungibile via tab
        if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
            btn.classList.add("show");
            btn.setAttribute("tabindex", "0");
        } else {
            btn.classList.remove("show");
            btn.setAttribute("tabindex", "-1");
        }
    }
});
