<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
if ((isset($_SESSION['escritorio']) && $_SESSION['escritorio']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería')) {
?>
<main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">🏥 Gestión de Diagnósticos</h1>
          <p class="welcome-subtitle">Administra los diagnósticos de los niños</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">🏥 Lista de Diagnósticos</h3>
          <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Diagnóstico
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-stethoscope"></i> Diagnóstico</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-file-text"></i> Descripción</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar"></i> Fecha Diagnóstico</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="enfermedadesTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición -->
    <div class="modal fade" id="modalEnfermedad" tabindex="-1" role="dialog" aria-labelledby="modalEnfermedadLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalEnfermedadLabel" style="font-weight: 600; font-size: 1.5rem;">
              <i class="fa fa-plus-circle"></i> Nuevo Diagnóstico
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
              <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
            </button>
          </div>
          <form id="formulario">
            <div class="modal-body" style="padding: 2.5rem;">
              <!-- Información de la Enfermedad -->
              <div class="card mb-4" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                  <h5 class="mb-0"><i class="fa fa-stethoscope"></i> Información del Diagnóstico</h5>
                </div>
                <div class="card-body" style="padding: 2rem;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="id_nino" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-child"></i> Niño *
                        </label>
                        <select class="form-control" name="id_nino" id="id_nino" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto; background: white;">
                          <option value="">Seleccionar niño</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nombre_enfermedad" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-stethoscope"></i> Nombre del Diagnóstico *
                        </label>
                        <input class="form-control" type="text" name="nombre_enfermedad" id="nombre_enfermedad" maxlength="100" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;" placeholder="Ej: Asma, Diabetes, etc.">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="descripcion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-file-text"></i> Descripción
                        </label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;" placeholder="Describe los síntomas, tratamiento, etc."></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Fecha de Diagnóstico -->
              <div class="card mb-4" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                  <h5 class="mb-0"><i class="fa fa-calendar"></i> Fecha de Diagnóstico</h5>
                </div>
                <div class="card-body" style="padding: 2rem;">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="fecha_diagnostico" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-calendar"></i> Fecha de Diagnóstico
                        </label>
                        <input class="form-control" type="date" name="fecha_diagnostico" id="fecha_diagnostico" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;">
                        <input type="hidden" name="id_enfermedad" id="id_enfermedad">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <i class="fa fa-save"></i> Guardar Diagnóstico
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
<script src="../public/js/custom-tables.js"></script>
<script src="scripts/enfermedades.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
?>