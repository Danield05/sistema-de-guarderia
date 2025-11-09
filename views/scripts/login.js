// Script para mostrar/ocultar contraseña
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('clavea');

    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon
        this.classList.toggle('fa-lock');
        this.classList.toggle('fa-lock-open');

        // Update title
        this.setAttribute('title', type === 'password' ? 'Mostrar contraseña' : 'Ocultar contraseña');
    });
});

// Función para mostrar alerta de recuperación de contraseña
function mostrarAlertaRecuperacion() {
    // Crear overlay de alerta
    var overlay = $('<div class="alert-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;"></div>');

    // Colores para warning
    var colores = {
        bg: '#fff3cd',
        border: '#ffeeba',
        text: '#856404',
        icon: '⚠️'
    };

    // Crear contenido de la alerta
    var alertContent = $('<div class="alert-content" style="background: ' + colores.bg + '; border: 2px solid ' + colores.border + '; color: ' + colores.text + '; padding: 30px; border-radius: 15px; text-align: center; max-width: 500px; margin: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); animation: slideIn 0.3s ease;"></div>');

    // Título con icono
    alertContent.append('<h3 style="margin: 0 0 15px 0; font-size: 20px; font-weight: bold;"><span style="font-size: 24px; margin-right: 10px;">' + colores.icon + '</span>Recuperación de Contraseña</h3>');

    // Mensaje
    alertContent.append('<p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.5;">Para recuperar su contraseña, contacte al administrador del sistema. Se le proporcionarán las instrucciones necesarias para restablecer su acceso.</p>');

    // Información adicional
    alertContent.append('<div style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px; margin-bottom: 20px;"><strong>Información de Contacto:</strong><br>• Email: admin@pequecontrol.com<br>• Teléfono: (503) 1234-5678<br>• Horario: Lunes a Viernes 8:00 AM - 5:00 PM</div>');

    // Botón de cerrar
    var btnCerrar = $('<button style="background: ' + colores.text + '; color: white; border: none; padding: 10px 25px; border-radius: 8px; font-size: 16px; cursor: pointer; transition: all 0.3s ease;">Entendido</button>');
    btnCerrar.click(function() {
        overlay.fadeOut(300, function() {
            overlay.remove();
        });
    });

    // Hover effects para el botón
    btnCerrar.hover(
        function() { $(this).css('opacity', '0.8'); },
        function() { $(this).css('opacity', '1'); }
    );

    alertContent.append(btnCerrar);
    overlay.append(alertContent);

    // Agregar al body
    $('body').append(overlay);

    // Agregar estilos CSS para animación
    if (!$('#alert-styles').length) {
        $('head').append('<style id="alert-styles">@keyframes slideIn { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }</style>');
    }
}