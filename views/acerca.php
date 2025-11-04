<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
?>
<main class="container-fluid py-5 px-3 main-dashboard" style="padding-top: 3rem; padding-bottom: 3rem;">
      <!-- Header de la página -->
      <div class="welcome-card">
        <div class="welcome-content">
          <h1 class="welcome-title">ℹ️ Acerca del Sistema</h1>
          <p class="welcome-subtitle">Información sobre el Sistema de Guardería</p>
        </div>
      </div>

      <!-- Contenedor principal -->
      <div class="activity-feed">
        <div class="about-content">
          <div class="about-section">
            <h3 class="section-title">🏫 Sobre el Sistema de Guardería</h3>
            <p>El <strong>Sistema de Guardería</strong> es una plataforma integral diseñada para facilitar la gestión administrativa y educativa de guarderías infantiles. Este sistema permite a los administradores y profesores llevar un control eficiente de los grupos, alumnos, asistencia, conducta, calificaciones y cursos.</p>
          </div>

          <div class="about-section">
            <h3 class="section-title">✨ Características Principales</h3>
            <div class="features-grid">
              <div class="feature-item">
                <i class="fa fa-users feature-icon"></i>
                <div class="feature-content">
                  <h4>Gestión de Grupos</h4>
                  <p>Crear y administrar grupos de estudiantes con facilidad.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-child feature-icon"></i>
                <div class="feature-content">
                  <h4>Control de Alumnos</h4>
                  <p>Registrar y mantener información detallada de cada alumno.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-calendar-check-o feature-icon"></i>
                <div class="feature-content">
                  <h4>Asistencia</h4>
                  <p>Registrar y monitorear la asistencia diaria de los estudiantes.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-smile-o feature-icon"></i>
                <div class="feature-content">
                  <h4>Conducta</h4>
                  <p>Evaluar y registrar el comportamiento de los alumnos.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-graduation-cap feature-icon"></i>
                <div class="feature-content">
                  <h4>Calificaciones</h4>
                  <p>Gestionar calificaciones y evaluaciones académicas.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-book feature-icon"></i>
                <div class="feature-content">
                  <h4>Cursos</h4>
                  <p>Organizar y asignar cursos a los diferentes grupos.</p>
                </div>
              </div>
              <div class="feature-item">
                <i class="fa fa-key feature-icon"></i>
                <div class="feature-content">
                  <h4>Permisos y Usuarios</h4>
                  <p>Control de acceso basado en roles para profesores y administradores.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="about-section">
            <h3 class="section-title">🛠️ Tecnologías Utilizadas</h3>
            <p>El sistema está desarrollado utilizando tecnologías web modernas como PHP, MySQL, HTML, CSS y JavaScript, con frameworks como AdminLTE para una interfaz de usuario intuitiva y responsive.</p>
          </div>

          <div class="about-section">
            <h3 class="section-title">🎯 Objetivo</h3>
            <p>Nuestro objetivo es proporcionar una herramienta eficiente y fácil de usar que ayude a mejorar la calidad educativa en las guarderías, permitiendo un seguimiento detallado del desarrollo de los niños y facilitando la comunicación entre padres, profesores y administradores.</p>
          </div>

          <div class="about-section">
            <h3 class="section-title">📞 Soporte</h3>
            <p>Para más información o soporte, contacta al administrador del sistema.</p>
          </div>
        </div>
      </div>
    </main>
<?php
require 'footer.php';
}
ob_end_flush();
?>