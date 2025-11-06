<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['escritorio'] == 1) {
    $user_id = $_SESSION["idusuario"];
    require_once "../models/Consultas.php";
    $consulta = new Consultas();
    $regv = $consulta->cantidad_ninos($user_id);
    $totalestudiantes = isset($regv['total_ninos']) ? $regv['total_ninos'] : 0;
    $cap_almacen = 3000;
?>
    <!-- 🔧 Quitamos padding lateral con clases personalizadas -->
    <main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Tarjeta de bienvenida -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">🎓 Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
          <p class="welcome-subtitle">Gestiona tu guardería de manera más eficiente</p>
        </div>
      </div>

      <!-- Estadísticas principales -->
      <div class="dashboard-grid">
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
          <div class="metric-value"><?php echo $cap_almacen; ?></div>
          <div class="metric-label">Capacidad Máxima</div>
        </div>
      </div>

      <!-- Aulas disponibles -->
      <div class="activity-feed">
        <h3 class="activity-title">🏫 Aulas Disponibles</h3>
        <?php
        $rspta = $consulta->resumen_aulas();
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
            <p>No tienes aulas registradas aún.</p>
            <a href="aulas.php" class="action-button">➕ Crear Primera Aula</a>
          </div>
        <?php } ?>
      </div>

      <!-- Alertas recientes -->
      <div class="activity-feed">
        <h3 class="activity-title">🔔 Alertas Recientes</h3>
        <?php
        $rsptalertas = $consulta->ninos_con_mas_alertas(date('Y-m-01'), date('Y-m-d'), 5);
        if ($rsptalertas->rowCount() > 0) {
          while ($regalerta = $rsptalertas->fetch(PDO::FETCH_OBJ)) {
        ?>
            <div class="alert alert-warning" role="alert">
              <strong><?php echo $regalerta->nombre_completo; ?></strong> tiene <?php echo $regalerta->total_alertas; ?> alertas pendientes
            </div>
        <?php
          }
        } else {
        ?>
          <div class="empty-state">
            <div class="empty-icon">✅</div>
            <p>No hay alertas pendientes.</p>
          </div>
        <?php } ?>
      </div>

      <!-- Acciones rápidas -->
      <div class="quick-actions">
        <h3 class="activity-title">⚡ Acciones Rápidas</h3>
        <div class="row">
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
