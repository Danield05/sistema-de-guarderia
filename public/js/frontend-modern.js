// ===========================================
// SISTEMA DE GUARDERÍA - JAVASCRIPT MODERNO
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades
    initModernFrontend();
    updateClock(); // Inicializar el reloj al cargar la página
    setInterval(updateClock, 1000); // Actualizar el reloj cada segundo
});

function initModernFrontend() {
    // Animaciones de entrada
    initEntranceAnimations();
    // Efectos hover y transiciones
    initHoverEffects();
    // Animaciones de scroll
    initScrollAnimations();
    // Efectos de carga
    initLoadingEffects();
    // Tooltips y popovers modernos
    initTooltips();
    // Notificaciones toast
    initToastNotifications();
}

// ===========================================
// RELOJ EN TIEMPO REAL
// ===========================================

function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    const clockElement = document.getElementById('current-time');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// ===========================================
// ANIMACIONES DE ENTRADA
// ===========================================

function initEntranceAnimations() {
    const animatedElements = document.querySelectorAll('.content-wrapper > *');
    animatedElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// ===========================================
// EFECTOS HOVER Y TRANSICIONES
// ===========================================

function initHoverEffects() {
    const buttons = document.querySelectorAll('.btn, button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    const cards = document.querySelectorAll('.card, .box, .info-box');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.2)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
}

// ===========================================
// ANIMACIONES DE SCROLL
// ===========================================

function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    const animateElements = document.querySelectorAll('.content-wrapper .row > div, .box, .info-box');
    animateElements.forEach(element => {
        element.classList.add('animate-on-scroll');
        observer.observe(element);
    });
}

// ===========================================
// EFECTOS DE CARGA
// ===========================================

function initLoadingEffects() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Procesando...';
                submitBtn.classList.add('loading');
            }
        });
    });
}

// ===========================================
// TOOLTIPS Y POPOVERS MODERNOS
// ===========================================

function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            showTooltip(this, this.getAttribute('data-tooltip'));
        });
        element.addEventListener('mouseleave', function() {
            hideTooltip();
        });
    });
}

function showTooltip(element, text) {
    hideTooltip();
    const tooltip = document.createElement('div');
    tooltip.className = 'modern-tooltip';
    tooltip.textContent = text;
    document.body.appendChild(tooltip);

    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';

    setTimeout(() => tooltip.classList.add('visible'), 10);
}

function hideTooltip() {
    const tooltip = document.querySelector('.modern-tooltip');
    if (tooltip) {
        tooltip.classList.remove('visible');
        setTimeout(() => tooltip.remove(), 300);
    }
}

// ===========================================
// NOTIFICACIONES TOAST
// ===========================================

function initToastNotifications() {
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);
}

function showToast(message, type = 'info', duration = 3000) {
    const toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const iconMap = {
        success: 'check-circle',
        error: 'times-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };

    toast.innerHTML = `
        <i class="fa fa-${iconMap[type]}"></i>
        <span>${message}</span>
        <button class="toast-close">&times;</button>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);

    const removeToast = () => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    };

    toast.querySelector('.toast-close').addEventListener('click', removeToast);
    setTimeout(removeToast, duration);
}

window.showToast = showToast;