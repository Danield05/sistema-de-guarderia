<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SISTEMA DE GUARDERIA</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1" name="viewport">

    <!-- Bootstrap 4.6.2 (compatible con el sistema) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Favicon Moderno -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏠</text></svg>">

    <!-- Estilos personalizados para login moderno -->
    <style>
    body {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        overflow-x: hidden;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        overflow: hidden;
        max-width: 400px;
        width: 100%;
        margin: 20px;
    }

    .login-header {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
    }

    .login-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .login-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .login-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
        position: relative;
        z-index: 1;
    }

    .login-body {
        padding: 2.5rem;
    }

    .login-message {
        text-align: center;
        color: #666;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 2rem;
        padding: 0.5rem;
        background: rgba(231, 76, 60, 0.1);
        border-radius: 10px;
        border-left: 4px solid #e74c3c;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.875rem 3rem 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
    }

    .form-control.has-icon {
        padding-right: 3rem;
    }

    .form-control:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        background: white;
    }

    .form-control::placeholder {
        color: #adb5bd;
        font-weight: 400;
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #e74c3c;
        font-size: 1.1rem;
        z-index: 5;
    }

    .btn-login {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .login-footer {
        text-align: center;
        padding: 1.5rem 2.5rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .login-footer a {
        color: #e74c3c;
        text-decoration: none;
        font-weight: 500;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    /* Animaciones */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-container {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-container {
            margin: 10px;
            max-width: none;
        }

        .login-header {
            padding: 2rem 1.5rem;
        }

        .login-title {
            font-size: 1.8rem;
        }

        .login-body {
            padding: 2rem 1.5rem;
        }
    }

    /* Loading state */
    .btn-login.loading {
        position: relative;
        color: transparent;
    }

    .btn-login.loading::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="login-header">
        <h1 class="login-title">PEQUE CONTROL</h1>
        <p class="login-subtitle">Sistema de Guardería</p>
      </div>

      <div class="login-body">
        <p class="login-message">Ingrese sus datos de Acceso</p>

        <form method="post" id="frmAcceso">
          <div class="form-group">
            <input type="text" id="logina" name="logina" class="form-control" placeholder="Usuario" required>
            <i class="fas fa-user input-icon"></i>
          </div>

          <div class="form-group">
            <input type="password" id="clavea" name="clavea" class="form-control has-icon" placeholder="Contraseña" required>
            <i class="fas fa-lock input-icon" id="togglePassword" style="cursor: pointer;" title="Mostrar/ocultar contraseña"></i>
          </div>

          <button type="submit" class="btn btn-login" id="btnIngresar">
            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
          </button>
        </form>
      </div>

      <div class="login-footer">
        <p>&copy; 2025 PEQUE CONTROL. Todos los derechos reservados.</p>
        <p><a href="#" onclick="mostrarAlertaRecuperacion()">¿Olvidaste tu contraseña?</a></p>
      </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
     <!-- Bootbox -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/login_simple.js"></script>

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
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
    </script>

  </body>
</html>