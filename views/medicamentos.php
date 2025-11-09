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
          <h1 class="welcome-title">💊 Gestión de Medicamentos</h1>
          <p class="welcome-subtitle">Administra los medicamentos y tratamientos de los niños</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">💊 Lista de Medicamentos</h3>
          <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nuevo Medicamento
          </button>
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #776bffff 0%, #3370ccff 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-pills"></i> Medicamento</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-syringe"></i> Dosis</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-clock"></i> Frecuencia</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-file-text"></i> Observaciones</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="medicamentosTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición -->
    <div class="modal fade" id="modalMedicamento" tabindex="-1" role="dialog" aria-labelledby="modalMedicamentoLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #281dc6ff 0%, #0f2a98ff 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalMedicamentoLabel" style="font-weight: 600; font-size: 1.5rem;">
              <i class="fa fa-plus-circle"></i> Nuevo Medicamento
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
                    <label for="nombre_medicamento" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-pills"></i> Nombre del Medicamento *
                    </label>
                    <input class="form-control" type="text" name="nombre_medicamento" id="nombre_medicamento" maxlength="100" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Paracetamol, Ibuprofeno, etc.">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dosis" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-syringe"></i> Dosis
                    </label>
                    <input class="form-control" type="text" name="dosis" id="dosis" maxlength="100" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: 5ml, 1 tableta, 2 gotas">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="frecuencia" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-clock"></i> Frecuencia
                    </label>
                    <input class="form-control" type="text" name="frecuencia" id="frecuencia" maxlength="100" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Cada 8 horas, 3 veces al día">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="observaciones" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                      <i class="fa fa-file-text"></i> Observaciones
                    </label>
                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Instrucciones especiales, efectos secundarios, etc."></textarea>
                    <input type="hidden" name="id_medicamento" id="id_medicamento">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" id="btnGuardar" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #030364ff 0%, #1b43c6ff 100%); box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);">
                <i class="fa fa-save"></i> Guardar Medicamento
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
<script src="scripts/medicamentos.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
?>