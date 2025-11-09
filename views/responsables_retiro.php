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
if ((isset($_SESSION['escritorio']) && $_SESSION['escritorio']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')) {
?>
<main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title" style="color: #28a745;">👥 Gestión de Responsables de Retiro</h1>
          <p class="welcome-subtitle">Administra las personas autorizadas para retirar a los niños</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title" style="color: #28a745;">👥 Lista de Responsables de Retiro</h3>
          <button type="button" class="btn btn-success" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);" onclick="mostrarform(true)">
        <i class="fa fa-plus-circle"></i> Nuevo Responsable
      </button>
        </div>

        <!-- Barra de búsqueda y filtros -->
        <div class="d-flex justify-content-between align-items-center mb-4" style="background: rgba(255,255,255,0.9); padding: 1rem; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <div class="d-flex align-items-center gap-3">
            <div class="input-group" style="max-width: 300px;">
              <div class="input-group-prepend">
                <span class="input-group-text" style="border-radius: 25px 0 0 25px; border: 2px solid #e9ecef; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;"><i class="fa fa-search"></i></span>
              </div>
              <input type="text" class="form-control" id="busqueda" placeholder="Buscar responsable..." style="border-radius: 0 25px 25px 0; border: 2px solid #e9ecef; border-left: none;">
            </div>
            <select class="form-control" id="filtroEstado" style="border-radius: 25px; border: 2px solid #e9ecef; max-width: 150px; display: none;">
              <option value="">Todos los estados</option>
              <option value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
          </div>
          <div class="stats-info" style="background: rgba(39, 174, 96, 0.1); padding: 0.5rem 1rem; border-radius: 20px; border: 1px solid rgba(39, 174, 96, 0.2);">
            <i class="fa fa-users text-success"></i> <span id="totalRegistros">0</span> responsables registrados
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user"></i> Nombre Completo</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-users"></i> Parentesco</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-phone"></i> Teléfono</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar-plus"></i> Inicio</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar-minus"></i> Fin</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="responsablesTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición -->
    <div class="modal fade" id="modalResponsable" tabindex="-1" role="dialog" aria-labelledby="modalResponsableLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalResponsableLabel" style="font-weight: 600; font-size: 1.5rem;">
              <i class="fa fa-plus-circle"></i> Nuevo Responsable de Retiro
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
                    <label for="id_nino" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-child"></i> Niño *
                    </label>
                    <select class="form-control" name="id_nino" id="id_nino" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto;">
                      <option value="">Seleccionar niño</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nombre_completo" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-user"></i> Nombre Completo *
                    </label>
                    <input class="form-control" type="text" name="nombre_completo" id="nombre_completo" maxlength="100" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Nombre completo del responsable">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="parentesco" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-users"></i> Parentesco *
                    </label>
                    <select class="form-control" name="parentesco" id="parentesco" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto;">
                      <option value="">Seleccionar parentesco</option>
                      <option value="Padre">Padre</option>
                      <option value="Madre">Madre</option>
                      <option value="Abuelo">Abuelo</option>
                      <option value="Abuela">Abuela</option>
                      <option value="Tío">Tío</option>
                      <option value="Tía">Tía</option>
                      <option value="Hermano">Hermano</option>
                      <option value="Hermana">Hermana</option>
                      <option value="Otro">Otro</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="telefono" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-phone"></i> Teléfono
                    </label>
                    <input class="form-control" type="text" name="telefono" id="telefono" maxlength="15" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Número de teléfono">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="periodo_inicio" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-calendar-plus"></i> Período Inicio *
                    </label>
                    <input class="form-control" type="date" name="periodo_inicio" id="periodo_inicio" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="periodo_fin" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-calendar-minus"></i> Período Fin *
                    </label>
                    <input class="form-control" type="date" name="periodo_fin" id="periodo_fin" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
                  </div>
                </div>
              </div>
              <input class="form-control" type="hidden" name="id_responsable" id="id_responsable">
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" id="btnGuardar" class="btn btn-success" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
            <i class="fa fa-save"></i> Guardar Responsable
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
<!-- jQuery debe cargarse antes del script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="scripts/responsables_retiro.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
?>