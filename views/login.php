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
    <link rel="stylesheet" href="styles/login.css?v=<?php echo time(); ?>">
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

    <script type="text/javascript" src="scripts/login_simple.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="scripts/login.js?v=<?php echo time(); ?>"></script>

  </body>
</html>