// JavaScript personalizado para dropdown del navbar
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.custom-dropdown');

    // Configurar cada dropdown
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        const items = dropdown.querySelectorAll('.dropdown-item');

        if (!toggle || !menu) return;

        // Ocultar menú inicialmente
        menu.style.display = 'none';

        // Toggle dropdown al hacer clic
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = menu.style.display === 'block';

            // Cerrar todos los otros dropdowns
            dropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                    const otherToggle = otherDropdown.querySelector('.dropdown-toggle');
                    if (otherMenu && otherToggle) {
                        otherMenu.style.display = 'none';
                        otherDropdown.classList.remove('show');
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Toggle este dropdown
            if (isOpen) {
                menu.style.display = 'none';
                dropdown.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            } else {
                menu.style.display = 'block';
                dropdown.classList.add('show');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });

        // Cerrar al hacer clic en un elemento
        items.forEach(item => {
            item.addEventListener('click', function() {
                menu.style.display = 'none';
                dropdown.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    });

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(e) {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                const menu = dropdown.querySelector('.dropdown-menu');
                const toggle = dropdown.querySelector('.dropdown-toggle');
                if (menu && toggle) {
                    menu.style.display = 'none';
                    dropdown.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
});