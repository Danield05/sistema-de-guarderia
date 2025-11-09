<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

// Desactivar el layout AdminLTE para esta vista moderna
$_SESSION['modern_layout'] = true;

require 'header.php';
?>
<link rel="stylesheet" href="../views/styles/acerca.css">

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
          <div class="about-grid">
            <div class="about-card">
              <div class="card-icon">
                <i class="fas fa-school"></i>
              </div>
              <h3>Sobre el Sistema de Guardería</h3>
              <p>El <strong>Sistema de Guardería</strong> es una plataforma integral diseñada para facilitar la gestión administrativa y educativa de guarderías infantiles. Este sistema permite a los administradores y profesores llevar un control eficiente de los grupos, alumnos, asistencia, conducta, calificaciones y cursos.</p>
            </div>

            <div class="about-card">
              <div class="card-icon">
                <i class="fas fa-star"></i>
              </div>
              <h3>Características Principales</h3>
              <div class="features-list">
                <div class="feature-item">
                  <i class="fas fa-users"></i>
                  <span>Gestión de Grupos</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-baby"></i>
                  <span>Control de Alumnos</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-calendar-check"></i>
                  <span>Asistencia</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-smile"></i>
                  <span>Conducta</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-graduation-cap"></i>
                  <span>Calificaciones</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-book"></i>
                  <span>Cursos</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-shield-alt"></i>
                  <span>Permisos y Usuarios</span>
                </div>
                <div class="feature-item">
                  <i class="fas fa-stethoscope"></i>
                  <span>Información Médica</span>
                </div>
              </div>
            </div>

            <div class="about-card">
              <div class="card-icon">
                <i class="fas fa-code"></i>
              </div>
              <h3>Tecnologías Utilizadas</h3>
              <div class="tech-grid">
                <div class="tech-badge">
                  <i class="fab fa-php"></i>
                  <span>PHP 7+</span>
                </div>
                <div class="tech-badge">
                  <i class="fas fa-database"></i>
                  <span>MySQL</span>
                </div>
                <div class="tech-badge">
                  <i class="fab fa-html5"></i>
                  <span>HTML5</span>
                </div>
                <div class="tech-badge">
                  <i class="fab fa-css3-alt"></i>
                  <span>CSS3</span>
                </div>
                <div class="tech-badge">
                  <i class="fab fa-js-square"></i>
                  <span>JavaScript</span>
                </div>
                <div class="tech-badge">
                  <i class="fab fa-bootstrap"></i>
                  <span>Bootstrap 5</span>
                </div>
              </div>
              <p>El sistema está desarrollado utilizando tecnologías web modernas con frameworks como AdminLTE para una interfaz de usuario intuitiva y responsive.</p>
            </div>

            <div class="about-card">
              <div class="card-icon">
                <i class="fas fa-bullseye"></i>
              </div>
              <h3>Objetivo</h3>
              <p>Nuestro objetivo es proporcionar una herramienta eficiente y fácil de usar que ayude a mejorar la calidad educativa en las guarderías, permitiendo un seguimiento detallado del desarrollo de los niños y facilitando la comunicación entre padres, profesores y administradores.</p>
            </div>

            <div class="about-card full-width">
              <div class="card-icon">
                <i class="fas fa-phone"></i>
              </div>
              <h3>Soporte</h3>
              <p>Para más información o soporte técnico, contacta al administrador del sistema.</p>
              <div class="contact-grid">
                <div class="contact-info">
                  <i class="fas fa-envelope"></i>
                  <span>admin@pequecontrol.com</span>
                </div>
                <div class="contact-info">
                  <i class="fas fa-phone"></i>
                  <span>(503) 1234-5678</span>
                </div>
                <div class="contact-info">
                  <i class="fas fa-clock"></i>
                  <span>Lunes a Viernes 8:00 AM - 5:00 PM</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
<?php
require 'footer.php';
}
ob_end_flush();
?>