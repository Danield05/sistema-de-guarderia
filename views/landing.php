<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEQUE CONTROL - Sistema de Guardería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/master-styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/navbar-custom.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/landing.css?v=<?php echo time(); ?>">
</head>
<body>

   
    <section class="hero-section">
        <div class="container">


            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-baby"></i>
                    PEQUE CONTROL
                </h1>
                <p class="hero-subtitle">
                    Sistema integral para la gestión eficiente de guarderías infantiles. Controla asistencia, salud y desarrollo de los niños con tecnología moderna.
                </p>

                <div class="hero-features">
                    <a href="#servicios" class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Seguridad Total</span>
                    </a>
                    <a href="#estadisticas" class="feature-item">
                        <i class="fas fa-clock"></i>
                        <span>Control en Tiempo Real</span>
                    </a>
                    <a href="#servicios" class="feature-item">
                        <i class="fas fa-heartbeat"></i>
                        <span>Seguimiento Médico</span>
                    </a>
                    <a href="#contacto" class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>Gestión Completa</span>
                    </a>
                </div>

                <div class="hero-buttons">
                    <a href="login.php" class="btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Acceder al Sistema
                    </a>
                    <a href="#servicios" class="btn-secondary">
                        <i class="fas fa-info-circle"></i>
                        Conocer Más
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- Stats Section -->
    <section id="estadisticas" class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-number">500+</div>
                    <div class="stat-title">Niños Registrados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">98%</div>
                    <div class="stat-title">Asistencia Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">50+</div>
                    <div class="stat-title">Personal Activo</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">5</div>
                    <div class="stat-title">Años de Excelencia</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios" class="services-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-child"></i>
                Servicios Especializados
            </h2>
            <p class="section-subtitle">
                Ofrecemos una gestión completa para el cuidado y desarrollo de los niños
            </p>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="service-title">Control de Asistencia</h3>
                    <p class="service-description">
                        Registro preciso de entradas y salidas de los niños con sistema de alertas.
                    </p>
                    <div class="service-features">
                        <span class="feature-badge">
                            <i class="fas fa-bell"></i> Alertas
                        </span>
                        <span class="feature-badge">
                            <i class="fas fa-clock"></i> Seguimiento
                        </span>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3 class="service-title">Información Médica</h3>
                    <p class="service-description">
                        Control de enfermedades, medicamentos, alergias y seguimiento médico completo de cada niño.
                    </p>
                    <div class="service-features">
                        <span class="feature-badge">
                            <i class="fas fa-file-medical"></i> Historial
                        </span>
                        <span class="feature-badge">
                            <i class="fas fa-exclamation-triangle"></i> Alertas Salud
                        </span>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="service-title">Gestión de Personal</h3>
                    <p class="service-description">
                        Administración completa del personal docente y administrativo con control de permisos y roles.
                    </p>
                    <div class="service-features">
                        <span class="feature-badge">
                            <i class="fas fa-user-shield"></i> Permisos
                        </span>
                        <span class="feature-badge">
                            <i class="fas fa-cogs"></i> Roles
                        </span>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="service-title">Reportes y Estadísticas</h3>
                    <p class="service-description">
                        Informes detallados sobre asistencia, desarrollo y rendimiento.
                    </p>
                    <div class="service-features">
                        <span class="feature-badge">
                            <i class="fas fa-chart-line"></i> rendimiento
                        </span>
                        <span class="feature-badge">
                            <i class="fas fa-download"></i> Exportación
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="contact-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-phone"></i>
                Información del Sistema
            </h2>
            <p class="section-subtitle">
                Detalles técnicos y soporte del sistema PEQUE CONTROL
            </p>

            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="contact-title">Tecnología</h3>
                    <div class="contact-info">
                        <strong>PHP 7+</strong><br>
                        <strong>MySQL</strong><br>
                        <strong>Bootstrap 5</strong><br>
                        <strong>Font Awesome 6</strong>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="contact-title">Seguridad</h3>
                    <div class="contact-info">
                        <strong>Encriptación SHA256</strong><br>
                        <strong>Control de Sesiones</strong><br>
                        <strong>Validación de Datos</strong><br>
                        <strong>Protección XSS/SQL</strong>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="contact-title">Compatibilidad</h3>
                    <div class="contact-info">
                        <strong>Responsive Design</strong><br>
                        <strong>Móviles y Tablets</strong><br>
                        <strong>Navegadores Modernos</strong><br>
                        <strong>Acceso 24/7</strong>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="contact-title">Desarrollador</h3>
                    <div class="contact-info">
                        <strong>Equipo 1</strong><br>
                        <strong>Grupo teorico 2</strong><br>
                        <strong>Proyecto Final </strong><br>
                    </div>
                </div>
            </div>
        </div>
    </section>

 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>