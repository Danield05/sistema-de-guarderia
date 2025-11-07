<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ((isset($_SESSION['grupos']) && $_SESSION['grupos']==1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')) {

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

      <!-- Botones de reporte -->
      <div class="quick-actions">
        <h3 class="activity-title">📁 Exportar Reportes</h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <button class="action-button" onclick="exportarReporte('csv')">
              📊 Exportar CSV
            </button>
          </div>
          <div class="col-md-6 mb-3">
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

  <!--Modal para registrar/editar asistencia-->
  <div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modalTitulo">Registrar Asistencia</h4>
        </div>
        <div class="modal-body">
          <form action="" name="formulario_asis" id="formulario_asis" method="POST">
            <div class="form-group">
              <label for="nino_seleccionado">
                <i class="fa fa-child"></i> Niño
              </label>
              <input type="hidden" id="idasistencia" name="idasistencia">
              <input type="hidden" id="id_nino" name="id_nino">
              <input type="hidden" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>">
              <input type="text" class="form-control" id="nino_seleccionado" name="nino_seleccionado" readonly placeholder="Seleccione un niño">
            </div>
            <div class="form-group">
              <label for="estado_id">
                <i class="fa fa-check-circle"></i> Estado de Asistencia
              </label>
              <select class="form-control" id="estado_id" name="estado_id" required>        
                <option value="">Seleccionar estado</option>
                <option value="1">Asistió</option>
                <option value="2">Inasistencia</option>
                <option value="3">Permiso</option>
                <option value="4">Tardanza</option>
                <option value="5">Salida temprana</option>
              </select>
            </div>
            <div class="form-group">
              <label for="observaciones">
                <i class="fa fa-comment"></i> Observaciones
              </label>
              <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <div class="form-group">
              <button class="action-button" type="submit" id="btnGuardar_asis">
                <i class="fa fa-save"></i> Guardar
              </button>
              <button class="action-button pull-right" data-dismiss="modal" type="button">
                <i class="fa fa-arrow-circle-left"></i> Cancelar
              </button>
            </div>
          </form>
        </div>
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