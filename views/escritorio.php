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
    $rsptav = $consulta->cantidadalumnos($user_id);
    $regv = $rsptav->fetch(PDO::FETCH_OBJ);
    $totalestudiantes = $regv->total_alumnos;
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
          <div class="metric-icon">👥</div>
          <div class="metric-value">
            <?php
            $rspta = $consulta->cantidadgrupos($user_id);
            echo $rspta->rowCount();
            ?>
          </div>
          <div class="metric-label">Grupos Activos</div>
        </div>

        <div class="metric-card metric-card-light-bg">
          <div class="metric-icon">👨‍🎓</div>
          <div class="metric-value"><?php echo $totalestudiantes; ?></div>
          <div class="metric-label">Estudiantes Totales</div>
        </div>

        <div class="metric-card metric-card-light-bg">
          <div class="metric-icon">📊</div>
          <div class="metric-value"><?php echo $cap_almacen; ?></div>
          <div class="metric-label">Capacidad Máxima</div>
        </div>
      </div>

      <!-- Grupos disponibles -->
      <div class="activity-feed">
        <h3 class="activity-title">👥 Grupos Disponibles</h3>
        <?php
        $rspta = $consulta->cantidadgrupos($user_id);
        $colores = array("bg-success", "bg-primary", "bg-warning", "bg-danger");
        $colorIndex = 0;

        if ($rspta->rowCount() > 0) {
          echo '<div class="row">';
          $rspta = $consulta->cantidadgrupos($user_id);
          while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $idgrupo = $reg->idgrupo;
            $nombre_grupo = $reg->nombre;
            $colorClass = $colores[$colorIndex % count($colores)];
            $colorIndex++;
        ?>
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="metric-card <?php echo $colorClass; ?> text-white">
                <div class="metric-icon">👥</div>
                <h4 class="metric-value"><?php echo $nombre_grupo; ?></h4>
                <div class="metric-label">
                  <?php
                  $rsptag = $consulta->cantidadg($user_id, $idgrupo);
                  $regrupo = $rsptag->fetch(PDO::FETCH_OBJ);
                  echo $regrupo->total_alumnos . ' estudiantes';
                  ?>
                </div>
                <div class="mt-3">
                  <a href="escritorio.php?idgrupo=<?php echo $idgrupo; ?>" class="btn btn-light btn-sm">
                    <i class="fa fa-eye"></i> Ver Grupo
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
            <div class="empty-icon">👥</div>
            <p>No tienes grupos registrados aún.</p>
            <a href="grupos.php" class="action-button">➕ Crear Primer Grupo</a>
          </div>
        <?php } ?>
      </div>

      <!-- Acciones rápidas -->
      <div class="quick-actions">
        <h3 class="activity-title">⚡ Acciones Rápidas</h3>
        <div class="row">
          <div class="col-md-3 mb-3">
            <a href="grupos.php" class="action-button">👥 Gestionar Grupos</a>
          </div>
          <?php if ($_SESSION['acceso'] == 1): ?>
            <div class="col-md-3 mb-3">
              <a href="usuario.php" class="action-button">👨‍🏫 Gestionar Profesores</a>
            </div>
            <div class="col-md-3 mb-3">
              <a href="permiso.php" class="action-button">🔐 Gestionar Permisos</a>
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
