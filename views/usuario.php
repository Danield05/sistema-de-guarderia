<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
if ((isset($_SESSION['acceso']) && $_SESSION['acceso']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')) {
?>
<main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">👥 Gestión de Usuarios</h1>
          <p class="welcome-subtitle">Administra los usuarios del sistema de guardería</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">👥 Lista de Usuarios</h3>
          <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Usuario
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user"></i> Nombre Completo</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-id-card"></i> DUI</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-phone"></i> Teléfono</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-envelope"></i> Email</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user-tag"></i> Rol</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-camera"></i> Foto</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-toggle-on"></i> Estado</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="usuariosTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición (movido al final del body) -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalUsuarioLabel" style="font-weight: 600; font-size: 1.5rem;">
              <i class="fa fa-user-plus"></i> Nuevo Usuario
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
              <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
            </button>
          </div>
          <form id="formulario">
            <div class="modal-body" style="padding: 2.5rem;">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nombre" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-user"></i> Nombre Completo *
                    </label>
                    <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                    <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Juan Pérez García">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dui" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-id-card"></i> DUI
                    </label>
                    <input class="form-control" type="text" name="dui" id="dui" maxlength="10" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="00000000-0" pattern="[0-9]{8}-[0-9]">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="direccion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-map-marker"></i> Dirección
                    </label>
                    <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Dirección completa">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="telefono" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-phone"></i> Teléfono
                    </label>
                    <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Número de teléfono">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-envelope"></i> Email
                    </label>
                    <input class="form-control" type="email" name="email" id="email" maxlength="70" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="correo@ejemplo.com">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="rol" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-user-tag"></i> Rol
                    </label>
                    <select class="form-control" name="rol" id="rol" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto;">
                      <option value="">Seleccionar rol</option>
                      <option value="Padre/Tutor">Padre/Tutor</option>
                      <option value="Maestro">Maestro</option>
                      <option value="Administrador">Administrador</option>
                      <option value="Médico/Enfermería">Médico/Enfermería</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6" id="claves">
                  <div class="form-group">
                    <label for="clave" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-lock"></i> Contraseña *
                    </label>
                    <input class="form-control" type="password" name="clave" id="clave" maxlength="64" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Contraseña segura">
                    <small class="text-muted" id="claveHelp" style="display: none;">Deja vacío para mantener la contraseña actual</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-camera"></i> Imagen de perfil
                    </label>
                    <div class="custom-file" style="margin-bottom: 1rem;">
                      <input type="file" class="custom-file-input" name="imagen" id="imagen" accept="image/*" style="display: none;">
                      <input type="hidden" name="imagenactual" id="imagenactual">
                      <label class="custom-file-label" for="imagen" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white; cursor: pointer; display: block; text-align: center;">
                        <i class="fa fa-upload"></i> Seleccionar imagen
                      </label>
                    </div>
                    <div class="text-center">
                      <div id="imagenmuestra" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #e9ecef; margin: 0 auto; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <i class="fa fa-user" style="font-size: 2.5rem; color: #ccc;"></i>
                      </div>
                      <small class="text-muted mt-2 d-block">Vista previa</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-toggle-on"></i> Estado
                    </label>
                    <input class="form-control" type="text" name="estado" id="estado" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
                <i class="fa fa-save"></i> Guardar Usuario
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php
}else{
 require 'noacceso.php';
}
?>
<!-- jQuery debe cargarse antes de usuario.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="scripts/usuario.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
  ?>
