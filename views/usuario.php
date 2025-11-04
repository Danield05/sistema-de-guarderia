<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
if ($_SESSION['acceso']==1) {
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
          <h3 class="activity-title">📋 Lista de Usuarios</h3>
          <button class="action-button" id="btnagregar" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Usuario
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="panel-body table-responsive" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Numero Documento</th>
              <th>Telefono</th>
              <th>Email</th>
              <th>Login</th>
              <th>Foto</th>
              <th>Estado</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Numero Documento</th>
              <th>Telefono</th>
              <th>Email</th>
              <th>Login</th>
              <th>Foto</th>
              <th>Estado</th>
            </tfoot>
          </table>
        </div>
        <!-- Formulario de registro -->
        <div class="panel-body" style="height: 600px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="nombre">Nombre(*):</label>
                  <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                  <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre completo" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="tipo_documento">Tipo Documento(*):</label>
                  <select name="tipo_documento" id="tipo_documento" class="form-control select-picker" required>
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="CEDULA">CEDULA</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="num_documento">Numero de Documento(*):</label>
                  <input type="text" class="form-control" name="num_documento" id="num_documento" placeholder="Número de documento" maxlength="20" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="direccion">Dirección</label>
                  <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" placeholder="Dirección completa">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="telefono">Teléfono</label>
                  <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Número de teléfono">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" type="email" name="email" id="email" maxlength="70" placeholder="correo@ejemplo.com">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="cargo">Cargo</label>
                  <input class="form-control" type="text" name="cargo" id="cargo" maxlength="20" placeholder="Cargo o puesto">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="login">Login(*):</label>
                  <input class="form-control" type="text" name="login" id="login" maxlength="20" placeholder="Nombre de usuario" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12" id="claves">
                <div class="form-group">
                  <label for="clave">Clave(*):</label>
                  <input class="form-control" type="password" name="clave" id="clave" maxlength="64" placeholder="Contraseña segura">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label>Permisos</label>
                  <ul id="permisos" style="list-style: none; padding: 0;">
                  </ul>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="imagen">Imagen de perfil:</label>
                  <input class="form-control" type="file" name="imagen" id="imagen" accept="image/*">
                  <input type="hidden" name="imagenactual" id="imagenactual">
                  <div class="mt-2">
                    <img src="" alt="Vista previa" width="120px" height="120px" id="imagenmuestra" style="border-radius: 8px; border: 2px solid #ddd; object-fit: cover;">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4">
              <button class="btn btn-primary btn-sm" type="submit" id="btnGuardar">
                <i class="fa fa-save"></i> Guardar Usuario
              </button>
              <button class="btn btn-danger btn-sm" onclick="cancelarform()" type="button" id="btnCancelar">
                <i class="fa fa-arrow-circle-left"></i> Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
<?php
}else{
 require 'noacceso.php';
}
?>
<script src="scripts/usuario.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
  ?>
