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
    <style>
        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="header-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23header-pattern)"/></svg>');
            opacity: 0.3;
        }

        .header .container {
            position: relative;
            z-index: 2;
        }

        .header h1 {
            margin: 0;
            font-size: 2.8em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header h1 i {
            color: #f39c12;
            font-size: 0.9em;
            filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 1.1em;
            opacity: 0.9;
            font-weight: 300;
        }
        /* Landing Page Styles */
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hero-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23hero-pattern)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem 1.5rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-item i {
            font-size: 1.2rem;
            color: #f39c12;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(231, 76, 60, 0.4);
            background: linear-gradient(45deg, #c0392b, #a93226);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #f39c12;
            color: #f39c12;
            transform: translateY(-2px);
        }

        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 60px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #e74c3c, #f39c12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 0.5rem;
        }

        .stat-title {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .services-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .contact-section .section-title {
            color: white;
        }

        .section-title i {
            color: #e74c3c;
            margin-right: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-align: center;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #e74c3c, #f39c12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .service-title {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .service-description {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .service-features {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .feature-badge {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .contact-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 60px 0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .contact-title {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .contact-info {
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Footer Styles */
        .landing-footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px 0;
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }

        .landing-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="footer-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23footer-pattern)"/></svg>');
            opacity: 0.3;
        }

        .landing-footer .footer-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .landing-footer .footer-left p,
        .landing-footer .footer-right p {
            margin: 0;
            font-size: 0.9em;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-features {
                gap: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
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