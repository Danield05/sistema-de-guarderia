<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
} else {
  require 'header.php';

  if ((isset($_SESSION['horarios']) && $_SESSION['horarios'] == 1) ||
      (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') ||
      (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Tutor')) {
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">

      <!-- Título de la sección -->
      <div class="welcome-card">
        <div class="welcome-content">
          <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Tutor'): ?>
            <h1 class="welcome-title">⏰ Horarios de Mi Hijo</h1>
            <p class="welcome-subtitle">Consulta los horarios de clase de tu hijo en la guardería</p>
          <?php else: ?>
            <h1 class="welcome-title">⏰ Gestión de Horarios</h1>
            <p class="welcome-subtitle">Administra los horarios de clase de los niños en la guardería</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Filtros (solo para admin/profesor) -->
      <?php if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor'): ?>
      <div class="mb-4">
        <div class="row">
          <div class="col-md-3">
            <input type="text" class="form-control" id="filtro_nombre" placeholder="Buscar por nombre del niño..." style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
          </div>
          <div class="col-md-3">
            <select class="form-control" id="filtro_aula" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
              <option value="">Todas las aulas</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" id="filtro_seccion" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
              <option value="">Todas las secciones</option>
            </select>
          </div>
          <div class="col-md-3">
            <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="filtrarHorarios()">
              <i class="fa fa-search"></i> Filtrar
            </button>
            <button type="button" class="btn btn-secondary ml-2" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600;" onclick="limpiarFiltros()">
              <i class="fa fa-times"></i> Limpiar
            </button>
          </div>
        </div>
      </div>

      <!-- Botón para abrir modal de registro -->
      <div class="mb-4">
        <button type="button" class="btn btn-success" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);" onclick="mostrarFormulario(0)">
          <i class="fa fa-plus-circle"></i> Agregar Nuevo Horario
        </button>
      </div>
      <?php endif; ?>

      <!-- Tabla de horarios -->
      <div class="activity-feed">
        <h3 class="activity-title">⏰ Lista de Horarios</h3>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
              <tr>
                <?php if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor'): ?>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Acciones</th>
                <?php endif; ?>
                <th style="border: none; padding: 1rem;"><i class="fa fa-user"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar"></i> Día de la Semana</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-clock-o"></i> Hora Entrada</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-clock-o"></i> Hora Salida</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-info-circle"></i> Descripción</th>
              </tr>
            </thead>
            <tbody style="background: rgba(255, 255, 255, 0.9);">
              <?php
              require_once "../models/Horarios.php";
              $horarios = new Horarios();

              // Verificar si es tutor para mostrar solo sus hijos
              if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Tutor') {
                $rspta = $horarios->listarPorTutor($_SESSION['id_usuario']);
              } else {
                // Obtener filtros de la URL
                $nombre_nino = isset($_GET['nombre_nino']) ? $_GET['nombre_nino'] : '';
                $aula = isset($_GET['aula']) ? $_GET['aula'] : '';
                $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '';

                if (!empty($nombre_nino) || !empty($aula) || !empty($seccion)) {
                  $rspta = $horarios->listarConFiltros($nombre_nino, $aula, $seccion);
                } else {
                  $rspta = $horarios->listar();
                }
              }

              while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                echo '<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;">';

                // Solo mostrar columna de acciones si no es tutor
                if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor') {
                  echo '<td style="padding: 1rem;">';
                  echo '<button class="btn btn-warning btn-sm" style="margin-right: 0.5rem; border-radius: 20px;" onclick="mostrar(' . $reg->id_horario . ')"><i class="fa fa-pencil"></i> Editar</button>';
                  echo '<button class="btn btn-danger btn-sm" style="border-radius: 20px;" onclick="desactivar(' . $reg->id_horario . ')"><i class="fa fa-trash"></i> Eliminar</button>';
                  echo '</td>';
                }

                echo '<td style="padding: 1rem; font-weight: 600; color: #3c8dbc;">' . $reg->nombre_completo . '</td>';
                echo '<td style="padding: 1rem;">' . $reg->dia_semana . '</td>';
                echo '<td style="padding: 1rem;">' . $reg->hora_entrada . '</td>';
                echo '<td style="padding: 1rem;">' . $reg->hora_salida . '</td>';
                echo '<td style="padding: 1rem; color: #666;">' . $reg->descripcion . '</td>';

                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para registro y edición (solo para admin/profesor) -->
      <?php if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor'): ?>
      <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
              <h4 class="modal-title" id="modalLabel" style="font-weight: 600; font-size: 1.5rem;">
                <i class="fa fa-clock-o"></i> Agregar Nuevo Horario
              </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
              </button>
            </div>
            <form id="formulario">
              <div class="modal-body" style="padding: 2.5rem;">
                <div class="row">
                  <div class="col-md-6" id="idField" style="display: none;">
                    <div class="form-group">
                      <label for="idhorario" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-id-card"></i> ID
                      </label>
                      <input type="text" class="form-control" id="idhorario" name="idhorario" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="id_nino" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-user"></i> Niño *
                      </label>
                      <select class="form-control" id="id_nino" name="id_nino" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar niño...</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="aula_info" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-university"></i> Aula
                      </label>
                      <input type="text" class="form-control" id="aula_info" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="seccion_info" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-sitemap"></i> Sección
                      </label>
                      <input type="text" class="form-control" id="seccion_info" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                    </div>
                  </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="dia_semana" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-calendar"></i> Día de la Semana *
                      </label>
                      <select class="form-control" id="dia_semana" name="dia_semana" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                        <option value="">Seleccionar día...</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_entrada" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-clock-o"></i> Hora de Entrada *
                      </label>
                      <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_salida" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-clock-o"></i> Hora de Salida *
                      </label>
                      <input type="time" class="form-control" id="hora_salida" name="hora_salida" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="descripcion" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                        <i class="fa fa-info-circle"></i> Descripción
                      </label>
                      <input type="text" class="form-control" id="descripcion" name="descripcion" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Ej: Horario regular de prekínder">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
                  <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
                  <i class="fa fa-save"></i> Guardar Horario
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </main>

<?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
}
?>

<!-- jQuery debe cargarse antes de horarios.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<?php if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor'): ?>
<script src="scripts/horarios.js"></script>
<?php endif; ?>