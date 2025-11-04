<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['grupos']==1) {

 ?>
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">👥 Gestión de Grupos</h1>
          <p class="welcome-subtitle">Administra los grupos de estudiantes de tu guardería</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">📋 Lista de Grupos</h3>
          <button class="action-button" id="btnagregar" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Grupo
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="panel-body table-responsive" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Usuario</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Usuario</th>
            </tfoot>
          </table>
        </div>

        <!-- Formulario de registro -->
        <div class="panel-body" style="height: 400px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Nombre del Grupo</label>
              <input class="form-control" type="hidden" name="idgrupo" id="idgrupo">
              <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" placeholder="Nombre del grupo" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" required>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-xs-12">
            </div>
            <div class="form-group col-lg-4 col-md-4 col-xs-6">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="favorito" id="favorito" value="1"> Marcar como favorito
                </label>
              </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary" type="submit" id="btnGuardar">
                <i class="fa fa-save"></i> Guardar
              </button>
              <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar">
                <i class="fa fa-arrow-circle-left"></i> Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- fin Modal-->
<?php
}else{
 require 'noacceso.php';
}

require 'footer.php';
 ?>
 <script src="scripts/grupos.js"></script>
 <?php
}

ob_end_flush();
  ?>

