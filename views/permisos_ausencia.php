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
          <h1 class="welcome-title">📋 Gestión de Permisos de Ausencia</h1>
          <p class="welcome-subtitle">Administra los permisos de ausencia de los niños</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="activity-title">📋 Lista de Permisos de Ausencia</h3>
          <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
            <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarform(true)">
              <i class="fa fa-plus-circle"></i> Nuevo Permiso Médico
            </button>
          <?php elseif ((isset($_SESSION['escritorio']) && $_SESSION['escritorio']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')): ?>
            <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarform(true)">
              <i class="fa fa-plus-circle"></i>
              <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
                Nuevo Permiso Médico
              <?php else: ?>
                Nuevo Permiso
              <?php endif; ?>
            </button>
          <?php endif; ?>
        </div>

        <!-- Tabla de registros -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar"></i> Tipo</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-file-text"></i> Descripción</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar-plus"></i> Fecha Inicio</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar-minus"></i> Fecha Fin</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-clock"></i> Hora Inicio</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-clock"></i> Hora Fin</th>
                <?php if ((isset($_SESSION['escritorio']) && $_SESSION['escritorio']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')): ?>
                <th style="border: none; padding: 1rem;"><i class="fa fa-file"></i> Archivo</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);" id="permisosTableBody">
              <!-- Los datos se cargarán aquí dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>

    </main>

    </div>

    <!-- Modal para registro y edición -->
    <div class="modal fade" id="modalPermiso" tabindex="-1" role="dialog" aria-labelledby="modalPermisoLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
            <h4 class="modal-title" id="modalPermisoLabel" style="font-weight: 600; font-size: 1.5rem;">
                <i class="fa fa-calendar-times"></i>
                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
                  Nuevo Permiso Médico
                <?php else: ?>
                  Nuevo Permiso de Ausencia
                <?php endif; ?>
              </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
              <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
            </button>
          </div>
          <form id="formulario">
            <div class="modal-body" style="padding: 2.5rem;">
              <!-- Información del Permiso -->
              <div class="card mb-4" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                  <h5 class="mb-0"><i class="fa fa-info-circle"></i> Información del Permiso</h5>
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
                        <label for="tipo_permiso" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-tag"></i> Tipo de Permiso *
                        </label>
                        <select class="form-control" name="tipo_permiso" id="tipo_permiso" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; font-weight: 400; height: auto; background: white;">
                          <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
                            <option value="Médico">🏥 Médico</option>
                          <?php else: ?>
                            <option value="Médico">🏥 Médico</option>
                            <option value="Personal">👤 Personal</option>
                            <option value="Otro">📝 Otro</option>
                          <?php endif; ?>
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
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;" placeholder="Describe el motivo de la ausencia"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Fechas y Horarios -->
              <div class="card mb-4" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                  <h5 class="mb-0"><i class="fa fa-calendar"></i> Fechas y Horarios</h5>
                </div>
                <div class="card-body" style="padding: 2rem;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="fecha_inicio" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-calendar-plus"></i> Fecha Inicio *
                        </label>
                        <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="fecha_fin" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-calendar-minus"></i> Fecha Fin *
                        </label>
                        <input class="form-control" type="date" name="fecha_fin" id="fecha_fin" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="hora_inicio" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-clock"></i> Hora Inicio
                        </label>
                        <input class="form-control" type="time" name="hora_inicio" id="hora_inicio" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="hora_fin" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                          <i class="fa fa-clock"></i> Hora Fin
                        </label>
                        <input class="form-control" type="time" name="hora_fin" id="hora_fin" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: white;">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Archivo del Permiso -->
              <div class="card mb-4" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">
                <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">
                  <h5 class="mb-0"><i class="fa fa-file-upload"></i> Archivo del Permiso</h5>
                </div>
                <div class="card-body" style="padding: 2rem;">
                  <input class="form-control" type="hidden" name="id_permiso" id="id_permiso">
                  <input type="hidden" name="archivo_actual" id="archivo_actual">
                  <div class="text-center">
                    <div class="upload-area" style="border: 2px dashed #17a2b8; border-radius: 15px; padding: 2rem; background: rgba(23, 162, 184, 0.05); margin-bottom: 1rem; transition: all 0.3s ease;">
                      <i class="fa fa-cloud-upload-alt" style="font-size: 3rem; color: #17a2b8; margin-bottom: 1rem;"></i>
                      <p style="margin-bottom: 1rem; color: #666;">Arrastra y suelta tu archivo aquí o haz clic para seleccionar</p>
                      <input type="file" class="form-control-file d-none" name="archivo_permiso" id="archivo_permiso" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                      <button type="button" class="btn btn-info" style="border-radius: 25px; padding: 0.5rem 2rem;" onclick="document.getElementById('archivo_permiso').click()">
                        <i class="fa fa-folder-open"></i> Seleccionar Archivo
                      </button>
                    </div>
                    <small class="text-muted">
                      <i class="fa fa-info-circle"></i> Formatos permitidos: PDF, JPG, PNG, DOC, DOCX (máx. 5MB)
                    </small>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                <i class="fa fa-times"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <i class="fa fa-save"></i> Guardar Permiso
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
<script>
    // Variable global para determinar si es médico
    var isMedicoGlobal = '<?php echo (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') ? 'true' : 'false'; ?>';
</script>
<script src="scripts/permisos_ausencia.js"></script>
<?php
require 'footer.php';
}

ob_end_flush();
?>