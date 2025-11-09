<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
?>
<main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">👤 Mi Perfil</h1>
          <p class="welcome-subtitle">Administra tu información personal y configuración de cuenta</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="row">
          <!-- Información del perfil -->
          <div class="col-md-8">
            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
              <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none; padding: 2rem;">
                <h4 class="card-title" style="font-weight: 600; font-size: 1.5rem; margin: 0;">
                  <i class="fas fa-user-edit"></i> Información Personal
                </h4>
              </div>
              <div class="card-body" style="padding: 2.5rem;">
                <form id="formularioPerfil">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nombre" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-user"></i> Nombre Completo *
                        </label>
                        <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                        <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Juan Pérez García">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="dui" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-id-card"></i> DUI
                        </label>
                        <input class="form-control" type="text" name="dui" id="dui" maxlength="10" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="00000000-0" pattern="[0-9]{8}-[0-9]">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="direccion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-map-marker-alt"></i> Dirección
                        </label>
                        <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Dirección completa">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="telefono" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Número de teléfono">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-envelope"></i> Email *
                        </label>
                        <input class="form-control" type="email" name="email" id="email" maxlength="70" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="correo@ejemplo.com">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="rol" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-user-tag"></i> Rol
                        </label>
                        <input class="form-control" type="text" name="rol" id="rol" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="clave_actual" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-lock"></i> Contraseña Actual
                        </label>
                        <input class="form-control" type="password" name="clave_actual" id="clave_actual" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ingresa tu contraseña actual">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="clave_nueva" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-key"></i> Nueva Contraseña
                        </label>
                        <input class="form-control" type="password" name="clave_nueva" id="clave_nueva" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Nueva contraseña (opcional)">
                        <small class="text-muted">Deja vacío para mantener la contraseña actual</small>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fas fa-camera"></i> Imagen de perfil
                        </label>
                        <div class="custom-file" style="margin-bottom: 1rem;">
                          <input type="file" class="custom-file-input" name="imagen" id="imagen" accept="image/*" style="display: none;">
                          <input type="hidden" name="imagenactual" id="imagenactual">
                          <label class="custom-file-label" for="imagen" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white; cursor: pointer; display: block; text-align: center;">
                            <i class="fas fa-upload"></i> Seleccionar nueva imagen
                          </label>
                        </div>
                        <div class="text-center">
                          <div id="imagenmuestra" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid #e9ecef; margin: 0 auto; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
                          </div>
                          <small class="text-muted mt-2 d-block">Vista previa</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 3rem; font-weight: 600; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                        <i class="fas fa-save"></i> Actualizar Perfil
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Información adicional -->
          <div class="col-md-4">
            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 1rem;">
              <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none; padding: 1.5rem;">
                <h5 class="card-title" style="font-weight: 600; margin: 0;">
                  <i class="fas fa-info-circle"></i> Información de la Cuenta
                </h5>
              </div>
              <div class="card-body" style="padding: 1.5rem;">
                <div class="text-center mb-3">
                  <div id="imagenPerfil" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #28a745; margin: 0 auto; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <i class="fas fa-user" style="font-size: 2rem; color: #28a745;"></i>
                  </div>
                  <h5 id="nombrePerfil" class="mt-3" style="color: #28a745; font-weight: 600;">Cargando...</h5>
                  <p id="rolPerfil" class="text-muted mb-0">Cargando...</p>
                </div>
                <hr>
                <div class="small text-muted">
                  <p><i class="fas fa-calendar-alt"></i> Miembro desde: <span id="fechaRegistro">Cargando...</span></p>
                  <p><i class="fas fa-shield-alt"></i> Estado: <span id="estadoCuenta" class="badge badge-success">Activo</span></p>
                </div>
              </div>
            </div>

            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
              <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none; padding: 1.5rem;">
                <h5 class="card-title" style="font-weight: 600; margin: 0;">
                  <i class="fas fa-lightbulb"></i> Consejos de Seguridad
                </h5>
              </div>
              <div class="card-body" style="padding: 1.5rem;">
                <ul class="list-unstyled small">
                  <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Usa una contraseña fuerte con al menos 8 caracteres</li>
                  <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Combina letras, números y símbolos</li>
                  <li class="mb-2"><i class="fas fa-check-circle text-success"></i> No compartas tu contraseña con nadie</li>
                  <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Actualiza tu perfil regularmente</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

<?php
}
?>
<!-- jQuery debe cargarse antes de perfil.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="scripts/perfil.js"></script>
<?php
require 'footer.php';
ob_end_flush();
?>