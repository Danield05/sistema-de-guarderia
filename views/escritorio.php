<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
} else {
  require 'header.php';

  if ((isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro')) {
    $user_id = $_SESSION["idusuario"];
    require_once "../models/Consultas.php";
    $consulta = new Consultas();

    // Para maestros, contar solo sus niños asignados
    if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
      $regv = $consulta->cantidad_ninos($user_id);
    } else {
      $regv = $consulta->cantidad_ninos(); // Sin user_id para contar todos los niños activos
    }

    $totalestudiantes = isset($regv['total_ninos']) ? $regv['total_ninos'] : 0;
    
    // Obtener total de secciones
    $regsecciones = $consulta->cantidad_secciones();
    $totalsecciones = isset($regsecciones['total_secciones']) ? $regsecciones['total_secciones'] : 0;
?>

    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Tarjeta de bienvenida -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">🏥 Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
          <p class="welcome-subtitle">
            <?php
            if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') {
              echo 'Gestiona la salud y bienestar de los niños';
            } else {
              echo 'Gestiona tu guardería de manera más eficiente';
            }
            ?>
          </p>
        </div>
      </div>

      <!-- Estadísticas principales -->
      <div class="dashboard-grid">
        <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
          <!-- Estadísticas para Médico/Enfermería -->
          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">🏥</div>
            <div class="metric-value">
              <?php
              $alertasSalud = $consulta->contar_alertas_por_estado();
              echo isset($alertasSalud['total']) ? $alertasSalud['total'] : 0;
              ?>
            </div>
            <div class="metric-label">Alertas de Salud</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">⚠️</div>
            <div class="metric-value">
              <?php echo isset($alertasSalud['pendientes']) ? $alertasSalud['pendientes'] : 0; ?>
            </div>
            <div class="metric-label">Alertas Pendientes</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">👨‍🎓</div>
            <div class="metric-value"><?php echo $totalestudiantes; ?></div>
            <div class="metric-label">Niños a Cargo</div>
          </div>
        <?php elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro'): ?>
          <!-- Estadísticas para Maestros -->
          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">👨‍🎓</div>
            <div class="metric-value"><?php echo $totalestudiantes; ?></div>
            <div class="metric-label">Niños Asignados</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">📅</div>
            <div class="metric-value">
              <?php
              // Contar asistencias de hoy para los niños del maestro
              require_once "../models/Asistencia.php";
              $asistencia = new Asistencia();
              $estadisticas = $asistencia->obtenerEstadisticasParaMaestro(date('Y-m-d'), $_SESSION['idusuario']);
              echo $estadisticas['asistieron'];
              ?>
            </div>
            <div class="metric-label">Asistencias Hoy</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">🔔</div>
            <div class="metric-value">
              <?php
              // Contar alertas pendientes de los niños del maestro
              $alertasPendientes = $consulta->contar_alertas_por_estado();
              echo isset($alertasPendientes['pendientes']) ? $alertasPendientes['pendientes'] : 0;
              ?>
            </div>
            <div class="metric-label">Alertas Pendientes</div>
          </div>
        <?php else: ?>
          <!-- Estadísticas para Administradores -->
          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">🏫</div>
            <div class="metric-value">
              <?php
              $rspta = $consulta->resumen_aulas();
              echo $rspta->rowCount();
              ?>
            </div>
            <div class="metric-label">Aulas Registradas</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">👨‍🎓</div>
            <div class="metric-value"><?php echo $totalestudiantes; ?></div>
            <div class="metric-label">Niños Totales</div>
          </div>

          <div class="metric-card metric-card-light-bg">
            <div class="metric-icon">📊</div>
            <div class="metric-value"><?php echo $totalsecciones; ?></div>
            <div class="metric-label">Secciones Totales</div>
          </div>
        <?php endif; ?>
      </div>
      <!-- Aulas disponibles -->
      <?php if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Médico/Enfermería'): ?>
      <div class="activity-feed">
        <h3 class="activity-title">🏫 Aulas Disponibles</h3>
        <?php
        // Para maestros, mostrar solo aulas donde tienen niños asignados
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
          $rspta = $consulta->resumen_aulas_para_maestro($_SESSION['idusuario']);
        } else {
          $rspta = $consulta->resumen_aulas();
        }

        $colores = array("bg-success", "bg-primary", "bg-warning", "bg-danger");
        $colorIndex = 0;

        if ($rspta->rowCount() > 0) {
          echo '<div class="row">';
          while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $id_aula = $reg->id_aula;
            $nombre_aula = $reg->nombre_aula;
            $total_ninos = $reg->total_ninos;
            $total_secciones = $reg->total_secciones;
            $colorClass = $colores[$colorIndex % count($colores)];
            $colorIndex++;
        ?>
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="metric-card <?php echo $colorClass; ?> text-white">
                <div class="metric-icon">🏫</div>
                <h4 class="metric-value"><?php echo $nombre_aula; ?></h4>
                <div class="metric-label">
                  <?php echo $total_ninos . ' niños • ' . $total_secciones . ' secciones'; ?>
                </div>
                <div class="mt-3">
                  <a href="aulas.php" class="btn btn-light btn-sm">
                    <i class="fa fa-eye"></i> Ver Aula
                  </a>
                </div>
              </div>
            </div>
        <?php
          }
          echo '</div>';
        } else {
        ?>
          <div class="empty-state">
            <div class="empty-icon">🏫</div>
            <p>No tienes aulas asignadas aún.</p>
          </div>
        <?php } ?>
      </div>
      <?php endif; ?>

      <!-- Alertas recientes -->
      <div class="activity-feed">
        <h3 class="activity-title">🔔 Alertas Recientes</h3>
        <?php
        // Filtrar alertas por rol del usuario
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') {
          // Para médicos, mostrar solo alertas de salud recientes
          $rsptalertas = $consulta->alertas_recientes(10); // Obtener más para filtrar
          $alertasFiltradas = array();
          while ($alerta = $rsptalertas->fetch(PDO::FETCH_OBJ)) {
            if ($alerta->tipo == 'Salud') {
              $alertasFiltradas[] = $alerta;
              if (count($alertasFiltradas) >= 5) break; // Limitar a 5
            }
          }
        } elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
          // Para maestros, mostrar solo alertas de sus niños asignados
          $rsptalertas = $consulta->alertas_recientes_para_maestro($_SESSION['idusuario'], 10);
          $alertasFiltradas = array();
          while ($alerta = $rsptalertas->fetch(PDO::FETCH_OBJ)) {
            $alertasFiltradas[] = $alerta;
            if (count($alertasFiltradas) >= 5) break; // Limitar a 5
          }
        } else {
          // Para administradores, mostrar todas las alertas recientes
          $rsptalertas = $consulta->alertas_recientes(5);
          $alertasFiltradas = array();
          while ($alerta = $rsptalertas->fetch(PDO::FETCH_OBJ)) {
            $alertasFiltradas[] = $alerta;
          }
        }

        if (count($alertasFiltradas) > 0) {
          foreach ($alertasFiltradas as $regalerta) {

            $tipo_class = "";
            $tipo_icon = "🔔";
            switch($regalerta->tipo) {
              case "Inasistencia":
                $tipo_class = "alert-danger";
                $tipo_icon = "❌";
                break;
              case "Conducta":
                $tipo_class = "alert-warning";
                $tipo_icon = "⚠️";
                break;
              case "Desarrollo":
                $tipo_class = "alert-success";
                $tipo_icon = "🌟";
                break;
              case "Salud":
                $tipo_class = "alert-info";
                $tipo_icon = "🏥";
                break;
              default:
                $tipo_class = "alert-info";
            }
            $estado_class = ($regalerta->estado == "Pendiente") ? "font-weight-bold" : "text-muted";
        ?>
            <div class="alert <?php echo $tipo_class; ?>" role="alert">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <strong><?php echo $tipo_icon; ?> <?php echo $regalerta->nombre_completo; ?></strong>
                  <small class="text-muted ml-2"><?php echo date('d/m/Y H:i', strtotime($regalerta->fecha_alerta)); ?></small>
                </div>
                <small class="<?php echo $estado_class; ?>"><?php echo $regalerta->estado; ?></small>
              </div>
              <p class="mb-1 mt-2"><?php echo $regalerta->mensaje; ?></p>
            </div>
          <?php
          }
        } else {
        ?>
          <div class="empty-state">
            <div class="empty-icon">✅</div>
            <p>No hay alertas registradas.</p>
          </div>
        <?php } ?>
      </div>

      <!-- Acciones rápidas -->
      <div class="quick-actions">
        <h3 class="activity-title">⚡ Acciones Rápidas</h3>
        <div class="row">
          <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
            <!-- Acciones para Médico/Enfermería -->
            <div class="col-md-4 mb-3">
              <a href="alertas.php" class="action-button">🔔 Gestionar Alertas de Salud</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="enfermedades.php" class="action-button">🏥 Información Médica</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="medicamentos.php" class="action-button">💊 Medicamentos</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="alergias.php" class="action-button">🤧 Alergias</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="ninos.php" class="action-button">👶 Ver Niños</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="acerca.php" class="action-button">ℹ️ Acerca del Sistema</a>
            </div>
          <?php elseif (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro'): ?>
            <!-- Acciones para Maestros -->
            <div class="col-md-4 mb-3">
              <a href="ninos.php" class="action-button">👶 Ver Mis Niños</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="asistencia.php" class="action-button">📅 Registrar Asistencia</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="alertas.php" class="action-button">🔔 Ver Alertas</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="enfermedades.php" class="action-button">🏥 Información Médica</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="medicamentos.php" class="action-button">💊 Medicamentos</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="alergias.php" class="action-button">🤧 Alergias</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="permisos_ausencia.php" class="action-button">📋 Permisos de Ausencia</a>
            </div>
            <div class="col-md-4 mb-3">
              <a href="acerca.php" class="action-button">ℹ️ Acerca del Sistema</a>
            </div>
          <?php else: ?>
            <!-- Acciones para Administradores -->
            <div class="col-md-3 mb-3">
              <a href="aulas.php" class="action-button">🏫 Gestionar Aulas</a>
            </div>
            <div class="col-md-3 mb-3">
              <a href="ninos.php" class="action-button">👶 Gestionar Niños</a>
            </div>
            <div class="col-md-3 mb-3">
              <a href="asistencia.php" class="action-button">📅 Control de Asistencia</a>
            </div>
            <div class="col-md-3 mb-3">
              <a href="alertas.php" class="action-button">🔔 Gestionar Alertas</a>
            </div>
            <?php if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1): ?>
              <div class="col-md-3 mb-3">
                <a href="usuario.php" class="action-button">👨‍🏫 Gestionar Usuarios</a>
              </div>
              <div class="col-md-3 mb-3">
                <a href="enfermedades.php" class="action-button">🏥 Información Médica</a>
              </div>
              <div class="col-md-3 mb-3">
                <a href="permisos_ausencia.php" class="action-button">📋 Permisos</a>
              </div>
            <?php endif; ?>
            <div class="col-md-3 mb-3">
              <a href="acerca.php" class="action-button">ℹ️ Acerca del Sistema</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>
<?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
}
ob_end_flush();
?>
