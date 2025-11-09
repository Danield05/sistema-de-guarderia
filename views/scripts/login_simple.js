$("#frmAcceso").on('submit',function(e)
{
    e.preventDefault();
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    // Validar campos vacíos
    if(logina.trim() === "" || clavea.trim() === "") {
        mostrarAlerta("⚠️ Campos Incompletos", "Por favor, complete todos los campos antes de continuar.", "warning");
        return false;
    }

    // Mostrar indicador de carga
    var $btn = $('button[type="submit"]');
    var textoOriginal = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Verificando...').prop('disabled', true);

    $.post("../ajax/usuario.php?op=verificar",
       {"logina":logina,"clavea":clavea},
       function(data)
   {
       $btn.html(textoOriginal).prop('disabled', false);
       
       if (data!="null")
       {
           mostrarAlerta(" ¡Bienvenido al Sistema!", "Credenciales correctas. Redirigiendo al escritorio...", "success");

           // Redirección después de mostrar la alerta
           setTimeout(function() {
               $(location).attr("href","escritorio.php");
           }, 2000);
       }
       else
       {
           mostrarAlerta(" Credenciales Incorrectas", "La contraseña o el usuario son incorrectos. Verifique que haya escrito correctamente sus datos de acceso e inténtelo nuevamente.", "error");
       }
    }).fail(function() {
        $btn.html(textoOriginal).prop('disabled', false);
        mostrarAlerta("🌐 Error de Conexión", "No se pudo conectar con el servidor. Verifique su conexión a internet e inténtelo nuevamente.", "warning");
    });
});

// Función personalizada para mostrar alertas
function mostrarAlerta(titulo, mensaje, tipo) {
    // Crear overlay de alerta
    var overlay = $('<div class="alert-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;"></div>');
    
    // Determinar colores según tipo
    var colores = {
        success: {bg: '#d4edda', border: '#c3e6cb', text: '#155724', icon: '✅'},
        error: {bg: '#f8d7da', border: '#f5c6cb', text: '#721c24', icon: '❌'},
        warning: {bg: '#fff3cd', border: '#ffeeba', text: '#856404', icon: '⚠️'}
    };
    
    var color = colores[tipo] || colores.warning;
    
    // Crear contenido de la alerta
    var alertContent = $('<div class="alert-content" style="background: ' + color.bg + '; border: 2px solid ' + color.border + '; color: ' + color.text + '; padding: 30px; border-radius: 15px; text-align: center; max-width: 500px; margin: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); animation: slideIn 0.3s ease;"></div>');
    
    // Título con icono
    alertContent.append('<h3 style="margin: 0 0 15px 0; font-size: 20px; font-weight: bold;"><span style="font-size: 24px; margin-right: 10px;">' + color.icon + '</span>' + titulo + '</h3>');
    
    // Mensaje
    alertContent.append('<p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.5;">' + mensaje + '</p>');
    
    // Botón de cerrar
    var btnCerrar = $('<button style="background: ' + color.text + '; color: white; border: none; padding: 10px 25px; border-radius: 8px; font-size: 16px; cursor: pointer; transition: all 0.3s ease;">Entendido</button>');
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
    
    // Auto-cerrar después de 5 segundos para alertas de éxito
    if (tipo === 'success') {
        setTimeout(function() {
            overlay.fadeOut(300, function() {
                overlay.remove();
            });
        }, 5000);
    }
}