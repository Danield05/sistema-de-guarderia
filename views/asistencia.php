<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

require 'header.php';
if ((isset($_SESSION['grupos']) && $_SESSION['grupos']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro')) {

  require_once "../models/Aulas.php";
  require_once "../models/Secciones.php";
  
  $aulas = new Aulas();
  $secciones = new Secciones();
  $rspta_aulas = $aulas->listar();
  $rspta_secciones = $secciones->listar();

 ?>
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      
      <!-- Tarjeta de bienvenida -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">📅 Control de Asistencia</h1>
          <p class="welcome-subtitle">Gestiona y monitorea la asistencia de los niños</p>
        </div>
      </div>

      <!-- Estadísticas principales -->
      <div class="container-fluid">
        <div class="dashboard-stats-grid">
          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">👥</div>
            <div class="metric-value" id="totalEstudiantes">0</div>
            <div class="metric-label">Total Registros</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">✅</div>
            <div class="metric-value" id="estudiantesAsistieron">0</div>
            <div class="metric-label">Total Asistencias</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">❌</div>
            <div class="metric-value" id="estudiantesFaltaron">0</div>
            <div class="metric-label">Total Faltas</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">🕐</div>
            <div class="metric-value" id="estudiantesTarde">0</div>
            <div class="metric-label">Total Tardanzas</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">📋</div>
            <div class="metric-value" id="estudiantesPermiso">0</div>
            <div class="metric-label">Total Permisos</div>
          </div>
        </div>
      </div>

      <!-- Filtros de búsqueda -->
      <div class="activity-feed">
        <h3 class="activity-title">🔍 Filtros de Búsqueda</h3>
        <div class="metric-card">
          <div class="row" style="padding: 1.5rem;">
            <!-- Fila 1: Filtros principales -->
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="filtro_aula" class="metric-label" style="color: #333; margin-bottom: 0.5rem;">
                <i class="fa fa-home"></i> Aula:
              </label>
              <select class="form-control" id="filtro_aula" name="filtro_aula">
                <option value="">Todas las aulas</option>
                <?php
                // Resetear el puntero para poder usar los datos de aulas
                $rspta_aulas = $aulas->listar();
                while($reg_aula = $rspta_aulas->fetch(PDO::FETCH_OBJ)): ?>
                  <option value="<?php echo $reg_aula->id_aula; ?>"><?php echo $reg_aula->nombre_aula; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="filtro_seccion" class="metric-label" style="color: #333; margin-bottom: 0.5rem;">
                <i class="fa fa-users"></i> Sección:
              </label>
              <select class="form-control" id="filtro_seccion" name="filtro_seccion">
                <option value="">Todas las secciones</option>
                <?php
                // Resetear el puntero para usar los datos de secciones
                $rspta_secciones = $secciones->listar();
                while($reg_seccion = $rspta_secciones->fetch(PDO::FETCH_OBJ)): ?>
                  <option value="<?php echo $reg_seccion->id_seccion; ?>" data-aula="<?php echo $reg_seccion->aula_id; ?>">
                    <?php echo $reg_seccion->nombre_seccion; ?> - <?php echo $reg_seccion->nombre_aula; ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="filtro_estado" class="metric-label" style="color: #333; margin-bottom: 0.5rem;">
                <i class="fa fa-check-circle"></i> Estado:
              </label>
              <select class="form-control" id="filtro_estado" name="filtro_estado">
                <option value="">Todos los estados</option>
                <option value="1">Asistió</option>
                <option value="2">Inasistencia</option>
                <option value="3">Permiso</option>
                <option value="4">Tardanza</option>
                <option value="5">Salida temprana</option>
              </select>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
              <label class="metric-label" style="color: #333; margin-bottom: 0.5rem;">
                <i class="fa fa-calendar"></i> Rango de Fechas:
              </label>
              <div class="row">
                <div class="col-6">
                  <input type="date" class="form-control form-control-sm" id="filtro_fecha_inicio" name="filtro_fecha_inicio" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>" placeholder="Inicio">
                </div>
                <div class="col-6">
                  <input type="date" class="form-control form-control-sm" id="filtro_fecha_fin" name="filtro_fecha_fin" value="<?php echo date('Y-m-d'); ?>" placeholder="Fin">
                </div>
              </div>
            </div>
          </div>
          
          <!-- Fila 2: Botones de acción -->
          <div class="row" style="padding: 0 1.5rem 1rem 1.5rem;">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-primary" onclick="aplicarFiltros()" style="margin-right: 1rem;">
                <i class="fa fa-search"></i> Buscar
              </button>
              <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                <i class="fa fa-eraser"></i> Limpiar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Botones de acción -->
      <div class="quick-actions">
        <h3 class="activity-title">⚡ Acciones Rápidas</h3>
        <div class="row">
          <div class="col-md-4 mb-3">
            <button type="button" class="btn btn-primary" style="border-radius: 25px; padding: 0.75rem 2rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);" onclick="mostrarFormulario(0)">
              <i class="fa fa-plus-circle"></i> Registrar Asistencia
            </button>
          </div>
          <div class="col-md-4 mb-3">
            <button class="action-button" onclick="exportarReporte('csv')">
              📊 Exportar CSV
            </button>
          </div>
          <div class="col-md-4 mb-3">
            <button class="action-button" onclick="exportarReporte('xls')">
              📈 Exportar Excel
            </button>
          </div>
        </div>
      </div>

      <!-- Tabla de asistencia -->
      <div class="activity-feed">
        <h3 class="activity-title">📋 Registros de Asistencia</h3>
        <div class="table-responsive">
          <table id="tbllistado" class="table table-hover" style="border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
              <tr>
                <th style="border: none; padding: 1rem;"><i class="fa fa-cogs"></i> Opciones</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-child"></i> Niño</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-calendar"></i> Fecha</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-check-circle"></i> Estado</th>
                <th style="border: none; padding: 1rem;"><i class="fa fa-comment"></i> Observaciones</th>
              </tr>
            </thead>
            <tbody id="tbody-asistencia" style="background: rgba(255, 255, 255, 0.95);">
              <tr>
                <td colspan="5" class="text-center" style="padding: 2rem; color: #666; font-style: italic;">
                  <i class="fa fa-spinner fa-spin fa-2x mb-3 d-block"></i>
                  Cargando datos de asistencia...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>

  <!-- Modal para registrar/editar asistencia -->
  <div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 20px 20px 0 0; border-bottom: none; padding: 2rem;">
          <h4 class="modal-title" id="modalTitulo" style="font-weight: 600; font-size: 1.5rem;">
            <i class="fa fa-calendar-check"></i> Registrar Asistencia
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
            <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
          </button>
        </div>
        <form action="" name="formulario_asis" id="formulario_asis" method="POST">
          <div class="modal-body" style="padding: 2.5rem;">
            <div class="row">
              <div class="col-md-6" id="idFieldAsis" style="display: none;">
                <div class="form-group">
                  <label for="idasistencia" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-id-card"></i> ID
                  </label>
                  <input type="text" class="form-control" id="idasistencia" name="idasistencia" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="nino_seleccionado" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-child"></i> Niño *
                  </label>
                  <input type="hidden" id="id_nino" name="id_nino">
                  <input type="hidden" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                  <select class="form-control" id="nino_seleccionado" name="nino_seleccionado" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                    <option value="">Seleccione un niño...</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="estado_id" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-check-circle"></i> Estado de Asistencia *
                  </label>
                  <select class="form-control" id="estado_id" name="estado_id" required style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; min-height: 45px;">
                    <option value="">Seleccionar estado...</option>
                    <option value="1">Asistió</option>
                    <option value="2">Inasistencia</option>
                    <option value="3">Permiso</option>
                    <option value="4">Tardanza</option>
                    <option value="5">Salida temprana</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="observaciones" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-comment"></i> Observaciones
                  </label>
                  <textarea class="form-control" id="observaciones" name="observaciones" rows="3" style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem;" placeholder="Observaciones adicionales..."></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="aula_info" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-university"></i> Aula
                  </label>
                  <input type="text" class="form-control" id="aula_info" name="aula_info" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="seccion_info" style="font-weight: 600; color: #3c8dbc; margin-bottom: 0.5rem;">
                    <i class="fa fa-sitemap"></i> Sección
                  </label>
                  <input type="text" class="form-control" id="seccion_info" name="seccion_info" readonly style="border-radius: 10px; border: 2px solid #e9ecef; padding: 0.75rem; font-size: 0.95rem; background: #f8f9fa;">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="border-top: none; padding: 2rem; background: #f8f9fa; border-radius: 0 0 20px 20px;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: #6c757d;">
              <i class="fa fa-times"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="btnGuardar_asis" style="border-radius: 25px; padding: 0.5rem 2rem; font-weight: 600; border: none; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);">
              <i class="fa fa-save"></i> Guardar Asistencia
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
require 'footer.php'
 ?>
 <script src="scripts/asistencia.js"></script>

 <?php 
}

ob_end_flush();
  ?>