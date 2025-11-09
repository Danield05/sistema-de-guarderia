<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
} else {

require 'header.php';

if ((isset($_SESSION['escritorio']) && $_SESSION['escritorio']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor')) {

?>
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor'): ?>
            <h1 class="welcome-title">🔔 Alertas de Mi Niño</h1>
            <p class="welcome-subtitle">Consulta las alertas relacionadas con tu niño</p>
          <?php else: ?>
            <h1 class="welcome-title">🔔 Gestión de Alertas</h1>
            <p class="welcome-subtitle">Administra y gestiona las alertas del sistema de guardería</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">🔔 Lista de Alertas</h3>
          <?php if($_SESSION['cargo'] == 'Maestro' || $_SESSION['cargo'] != 'Padre/Tutor'): ?>
          <button class="action-button" id="btnagregar" onclick="mostrarform(true)">
            <i class="fa fa-plus-circle"></i> Nueva Alerta
          </button>
          <?php endif; ?>
        </div>

        <!-- Tabla de alertas con el mismo estilo que aulas -->
        <div class="table-responsive">
          <table id="tbllistado" class="table table-hover">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            </thead>
            <tbody id="tbody-alertas" style="background: rgba(255, 255, 255, 0.9);">
            </tbody>
          </table>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="stats-row mt-4">
          <div class="row">
            <div class="col-md-4">
              <div class="stat-card">
                <h4 id="total-alertas">0</h4>
                <p>Total Alertas</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-card pending">
                <h4 id="alertas-pendientes">0</h4>
                <p>Pendientes</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-card responded">
                <h4 id="alertas-respondidas">0</h4>
                <p>Respondidas</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulario de registro -->
        <div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog" aria-labelledby="modalAlertaLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
              <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
                <h4 class="modal-title" id="modalAlertaLabel" style="font-weight: 600; font-size: 1.5rem;">
                  <i class="fa fa-plus-circle"></i> Nueva Alerta
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                  <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                </button>
              </div>
              <form id="formulario">
                <div class="modal-body" style="padding: 2.5rem;">
                  <div class="panel-body" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="id_nino"><i class="fa fa-user"></i> Niño</label>
                <input class="form-control" type="hidden" name="idalerta" id="idalerta">
                <select class="form-control" name="id_nino" id="id_nino" required>
                  <option value="">Seleccione un niño</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="tipo"><i class="fa fa-tag"></i> Tipo de Alerta</label>
                <select class="form-control" name="tipo" id="tipo" required>
                  <option value="">Seleccione el tipo</option>
                  <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
                    <option value="Salud">Salud</option>
                  <?php else: ?>
                    <option value="Inasistencia">Inasistencia</option>
                    <option value="Conducta">Conducta</option>
                    <option value="Desarrollo">Desarrollo</option>
                    <option value="Salud">Salud</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="estado"><i class="fa fa-flag"></i> Estado</label>
                <select class="form-control" name="estado" id="estado" required>
                  <option value="Pendiente">Pendiente</option>
                  <option value="Respondida">Respondida</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="mensaje"><i class="fa fa-comment"></i> Mensaje</label>
                <textarea class="form-control" name="mensaje" id="mensaje" rows="4" placeholder="Descripción de la alerta" required></textarea>
              </div>
            </div>
            <div class="row" id="botonesForm" style="display: none;">
              <div class="form-group col-md-12">
                <div class="text-center">
                  <button class="btn btn-primary" type="submit" id="btnGuardar" style="margin-right: 10px; border-radius: 25px; padding: 0.75rem 2rem;">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                  <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar" style="border-radius: 25px; padding: 0.75rem 2rem;">
                    <i class="fa fa-arrow-circle-left"></i> Cancelar
                  </button>
                </div>
              </div>
            </div>
                  </div>
                </div>
                <div class="modal-footer" id="modalFooter" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px; justify-content: center;">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                    <i class="fa fa-times"></i> Cerrar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal para ver detalles de alerta -->
    <div class="modal fade" id="verAlertaModal" tabindex="-1" role="dialog" aria-labelledby="verAlertaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verAlertaLabel">Detalles de la Alerta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="alerta-detalles">
              <!-- Los detalles se cargarán aquí via JavaScript -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btn-responder-alerta">Marcar como Respondida</button>
          </div>
        </div>
      </div>
    </div>

    <!-- fin Modal-->
<?php
} else {
    require 'noacceso.php';
}

require 'footer.php';
?>
<script src="scripts/alertas.js"></script>
<?php
}

ob_end_flush();