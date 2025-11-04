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
          <h1 class="welcome-title">🔐 Gestión de Permisos</h1>
          <p class="welcome-subtitle">Administra los permisos del sistema de guardería</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">📋 Lista de Permisos</h3>
          <button class="action-button" id="btnagregar" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Permiso
          </button>
        </div>
<!--box-header-->
        <!-- Tabla de registros -->
        <div class="panel-body table-responsive" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Estado</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Estado</th>
            </tfoot>
          </table>
        </div>

        <!-- Formulario de registro -->
        <div class="panel-body" style="height: 300px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="form-group">
                  <label for="nombre">Nombre del Permiso(*):</label>
                  <input class="form-control" type="hidden" name="idpermiso" id="idpermiso">
                  <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" placeholder="Ingrese el nombre del permiso" required>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4">
              <button class="btn btn-primary" type="submit" id="btnGuardar">
                <i class="fa fa-save"></i> Guardar Permiso
              </button>
              <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar">
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
<script src="scripts/permiso.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
  ?>
