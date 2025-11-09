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
          <h1 class="welcome-title">🤧 Gestión de Alergias</h1>
          <p class="welcome-subtitle">Administra las alergias y sensibilidades de los niños</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">🤧 Lista de Alergias</h3>
          <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nueva Alergia
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-allergies"></i> Tipo de Alergia</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-file-text"></i> Descripción</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="alergiasTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición -->
    <div class="modal fade" id="modalAlergia" tabindex="-1" role="dialog" aria-labelledby="modalAlergiaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalAlergiaLabel" style="font-weight: 600; font-size: 1.5rem;">
              <i class="fa fa-plus-circle"></i> Nueva Alergia
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
                    <label for="tipo_alergia" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-allergies"></i> Tipo de Alergia *
                    </label>
                    <select class="form-control" name="tipo_alergia" id="tipo_alergia" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto;">
                      <option value="">Seleccionar tipo</option>
                      <option value="Alimentaria">Alimentaria</option>
                      <option value="Medicamentos">Medicamentos</option>
                      <option value="Ambiental">Ambiental</option>
                      <option value="Polvo">Polvo</option>
                      <option value="Polen">Polen</option>
                      <option value="Animales">Animales</option>
                      <option value="Insectos">Insectos</option>
                      <option value="Látex">Látex</option>
                      <option value="Otra">Otra</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="descripcion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-file-text"></i> Descripción
                    </label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="4" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Describe los síntomas, gravedad, tratamientos, etc."></textarea>
                    <input type="hidden" name="id_alergia" id="id_alergia">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <i class="fa fa-save"></i> Guardar Alergia
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
<script src="scripts/alergias.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
?>