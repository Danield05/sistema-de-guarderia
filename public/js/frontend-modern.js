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

    // Animar elementos al cargar la página

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

// FUNCIONALIDADES DEL SIDEBAR (REMOVIDAS)

// ===========================================

// La funcionalidad del sidebar ha sido eliminada ya que el nuevo diseño no lo incluye.

// ===========================================

// EFECTOS HOVER Y TRANSICIONES

// ===========================================



function initHoverEffects() {

    // Efectos en botones

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



    // Efectos en cards

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



    // Efectos en inputs

    const inputs = document.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {

        input.addEventListener('focus', function() {

            this.style.transform = 'scale(1.02)';

            this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';

        });



        input.addEventListener('blur', function() {

            this.style.transform = 'scale(1)';

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



    // Observar elementos para animar

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

    // Agregar efectos de carga a formularios

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



    // Efectos de carga en botones de acción

    const actionButtons = document.querySelectorAll('[data-loading]');

    actionButtons.forEach(button => {

        button.addEventListener('click', function() {

            const originalText = this.innerHTML;

            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Cargando...';

            this.classList.add('loading');



            setTimeout(() => {

                this.innerHTML = originalText;

                this.classList.remove('loading');

            }, 2000);

        });

    });

}



// ===========================================

// TOOLTIPS Y POPOVERS MODERNOS

// ===========================================



function initTooltips() {

    // Crear tooltips personalizados

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

    // Remover tooltip existente

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

    // Crear contenedor para toasts

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



    // Animar entrada

    setTimeout(() => toast.classList.add('show'), 10);



    // Auto-remover

    const removeToast = () => {

        toast.classList.remove('show');

        setTimeout(() => toast.remove(), 300);

    };



    // Click en close

    toast.querySelector('.toast-close').addEventListener('click', removeToast);



    // Auto-remove después de duration

    setTimeout(removeToast, duration);

}



// Funciones globales para usar desde PHP/HTML

window.showToast = showToast;



// ===========================================

// UTILIDADES

// ===========================================



// Función para confirmar acciones

function confirmAction(message = '¿Estás seguro de realizar esta acción?') {

    return new Promise((resolve) => {

        const modal = document.createElement('div');

        modal.className = 'modern-modal-overlay';

        modal.innerHTML = `

            <div class="modern-modal">

                <div class="modern-modal-header">

                    <h4>Confirmar Acción</h4>

                </div>

                <div class="modern-modal-body">

                    <p>${message}</p>

                </div>

                <div class="modern-modal-footer">

                    <button class="btn btn-secondary-modern" onclick="this.closest('.modern-modal-overlay').remove()">Cancelar</button>

                    <button class="btn btn-primary-modern confirm-btn">Confirmar</button>

                </div>

            </div>

        `;



        document.body.appendChild(modal);



        modal.querySelector('.confirm-btn').addEventListener('click', () => {

            modal.remove();

            resolve(true);

        });



        modal.addEventListener('click', (e) => {

            if (e.target === modal) {

                modal.remove();

                resolve(false);

            }

        });

    });

}



window.confirmAction = confirmAction;



// Función para mostrar loading overlay

function showLoading(message = 'Cargando...') {

    const overlay = document.createElement('div');

    overlay.className = 'loading-overlay';

    overlay.innerHTML = `

        <div class="loading-spinner">

            <div class="spinner"></div>

            <p>${message}</p>

        </div>

    `;



    document.body.appendChild(overlay);

    return overlay;

}



function hideLoading() {

    const overlay = document.querySelector('.loading-overlay');

    if (overlay) {

        overlay.remove();

    }

}



window.showLoading = showLoading;

window.hideLoading = hideLoading;



// ===========================================

// RESPONSIVE UTILITIES

// ===========================================



function handleResponsive() {

    const width = window.innerWidth;



    if (width <= 767) {

        // Mobile

        document.body.classList.add('mobile-view');

        document.body.classList.remove('tablet-view', 'desktop-view');

    } else if (width <= 991) {

        // Tablet

        document.body.classList.add('tablet-view');

        document.body.classList.remove('mobile-view', 'desktop-view');

    } else {

        // Desktop

        document.body.classList.add('desktop-view');

        document.body.classList.remove('mobile-view', 'tablet-view');

    }

}



// Ejecutar al cargar y al redimensionar

window.addEventListener('load', handleResponsive);

window.addEventListener('resize', handleResponsive);



// ===========================================

// ANIMACIONES CSS ADICIONALES

// ===========================================



// Agregar estilos CSS para componentes JS

const style = document.createElement('style');

style.textContent = `

    /* Modern Tooltip */

    .modern-tooltip {

        position: fixed;

        background: rgba(0, 0, 0, 0.8);

        color: white;

        padding: 8px 12px;

        border-radius: 6px;

        font-size: 14px;

        z-index: 9999;

        opacity: 0;

        transform: translateY(10px);

        transition: all 0.3s ease;

        pointer-events: none;

        white-space: nowrap;

    }



    .modern-tooltip.visible {

        opacity: 1;

        transform: translateY(0);

    }



    .modern-tooltip::after {

        content: '';

        position: absolute;

        top: 100%;

        left: 50%;

        transform: translateX(-50%);

        border: 5px solid transparent;

        border-top-color: rgba(0, 0, 0, 0.8);

    }



    /* Toast Notifications */

    .toast-container {

        position: fixed;

        top: 20px;

        right: 20px;

        z-index: 9999;

        max-width: 400px;

    }



    .toast {

        background: white;

        border-radius: 8px;

        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);

        padding: 16px 20px;

        margin-bottom: 10px;

        display: flex;

        align-items: center;

        transform: translateX(400px);

        transition: all 0.3s ease;

        border-left: 4px solid;

    }



    .toast.show {

        transform: translateX(0);

    }



    .toast-success {

        border-left-color: #00d4aa;

    }



    .toast-error {

        border-left-color: #ff3b30;

    }



    .toast-warning {

        border-left-color: #ff9500;

    }



    .toast-info {

        border-left-color: #5ac8fa;

    }



    .toast i {

        margin-right: 12px;

        font-size: 18px;

    }



    .toast-success i { color: #00d4aa; }

    .toast-error i { color: #ff3b30; }

    .toast-warning i { color: #ff9500; }

    .toast-info i { color: #5ac8fa; }



    .toast-close {

        margin-left: auto;

        background: none;

        border: none;

        font-size: 20px;

        cursor: pointer;

        opacity: 0.7;

        transition: opacity 0.2s ease;

    }



    .toast-close:hover {

        opacity: 1;

    }



    /* Modern Modal */

    .modern-modal-overlay {

        position: fixed;

        top: 0;

        left: 0;

        width: 100%;

        height: 100%;

        background: rgba(0, 0, 0, 0.5);

        display: flex;

        align-items: center;

        justify-content: center;

        z-index: 10000;

        animation: fadeIn 0.3s ease;

    }



    .modern-modal {

        background: white;

        border-radius: 12px;

        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);

        max-width: 500px;

        width: 90%;

        animation: slideIn 0.3s ease;

    }



    .modern-modal-header {

        padding: 24px 24px 0 24px;

    }



    .modern-modal-header h4 {

        margin: 0;

        color: #2c3e50;

        font-weight: 600;

    }



    .modern-modal-body {

        padding: 24px;

    }



    .modern-modal-body p {

        margin: 0;

        color: #7f8c8d;

        line-height: 1.6;

    }



    .modern-modal-footer {

        padding: 0 24px 24px 24px;

        text-align: right;

    }



    .modern-modal-footer .btn {

        margin-left: 12px;

    }



    /* Loading Overlay */

    .loading-overlay {

        position: fixed;

        top: 0;

        left: 0;

        width: 100%;

        height: 100%;

        background: rgba(255, 255, 255, 0.9);

        display: flex;

        align-items: center;

        justify-content: center;

        z-index: 10000;

        animation: fadeIn 0.3s ease;

    }



    .loading-spinner {

        text-align: center;

        padding: 40px;

        background: white;

        border-radius: 12px;

        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);

    }



    .spinner {

        width: 40px;

        height: 40px;

        border: 4px solid #f3f3f3;

        border-top: 4px solid #667eea;

        border-radius: 50%;

        animation: spin 1s linear infinite;

        margin: 0 auto 16px auto;

    }



    /* Animations */

    @keyframes fadeIn {

        from { opacity: 0; }

        to { opacity: 1; }

    }



    @keyframes slideIn {

        from {

            opacity: 0;

            transform: translateY(-20px) scale(0.95);

        }

        to {

            opacity: 1;

            transform: translateY(0) scale(1);

        }

    }



    @keyframes spin {

        0% { transform: rotate(0deg); }

        100% { transform: rotate(360deg); }

    }



    /* Animate on scroll */

    .animate-on-scroll {

        opacity: 0;

        transform: translateY(30px);

        transition: all 0.6s ease;

    }



    .animate-on-scroll.animate-in {

        opacity: 1;

        transform: translateY(0);

    }



    /* Responsive adjustments */

    @media (max-width: 767px) {

        .toast-container {

            left: 10px;

            right: 10px;

            top: 10px;

        }



        .modern-modal {

            margin: 20px;

            width: calc(100% - 40px);

        }

    }

`;



document.head.appendChild(style);



// ===========================================

// INICIALIZACIÓN COMPLETA

// ===========================================