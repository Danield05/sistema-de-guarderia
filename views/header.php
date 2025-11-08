<?php
if (strlen(session_id()) < 1) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema de Guardería</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Estilos Principales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    
    <!-- Favicon Moderno -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏠</text></svg>">

    <!-- DATATABLES -->
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">

    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="../public/css/master-styles.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">
    <link rel="stylesheet" href="../public/css/frontend-modern.css">
    <link rel="stylesheet" href="../public/css/custom-tables.css">
    
    <!-- Estilos CSS para los dropdowns personalizados -->
    <style>
    .custom-caret {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 8px;
        vertical-align: middle;
        border-top: 4px solid;
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;
    }
    
    .custom-dropdown.show .custom-caret {
        transform: rotate(180deg);
    }
    
    /* Estilos para el dropdown del usuario */
    .user-profile-widget {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .user-profile-widget:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }
    
    .user-image {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    
    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }
    
    .user-role {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* Estilos para el menú del dropdown del usuario */
    .user-dropdown-menu {
        min-width: 180px;
        padding: 8px 0;
        margin-top: 5px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .user-dropdown-menu .dropdown-item {
        padding: 10px 20px;
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .user-dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }
    
    .user-dropdown-menu .dropdown-item i {
        margin-right: 10px;
        width: 16px;
        text-align: center;
    }
    
    .user-dropdown-menu .divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 5px 0;
    }
    
    .user-dropdown-menu .text-danger {
        color: #dc3545 !important;
    }
    
    .user-dropdown-menu .text-danger:hover {
        background-color: #f8d7da;
        color: #721c24 !important;
    }
    
    /* Ocultar el caret automático de Bootstrap */
    .dropdown-toggle::after {
        display: none !important;
    }
    </style>
    
    <!-- JavaScript personalizado para dropdown del navbar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.custom-dropdown');
        
        // Configurar cada dropdown
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');
            const items = dropdown.querySelectorAll('.dropdown-item');

            if (!toggle || !menu) return;

            // Ocultar menú inicialmente
            menu.style.display = 'none';

            // Toggle dropdown al hacer clic
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isOpen = menu.style.display === 'block';
                
                // Cerrar todos los otros dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                        const otherToggle = otherDropdown.querySelector('.dropdown-toggle');
                        if (otherMenu && otherToggle) {
                            otherMenu.style.display = 'none';
                            otherDropdown.classList.remove('show');
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Toggle este dropdown
                if (isOpen) {
                    menu.style.display = 'none';
                    dropdown.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    menu.style.display = 'block';
                    dropdown.classList.add('show');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            // Cerrar al hacer clic en un elemento
            items.forEach(item => {
                item.addEventListener('click', function() {
                    menu.style.display = 'none';
                    dropdown.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        });
        
        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target)) {
                    const menu = dropdown.querySelector('.dropdown-menu');
                    const toggle = dropdown.querySelector('.dropdown-toggle');
                    if (menu && toggle) {
                        menu.style.display = 'none';
                        dropdown.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });
    });
    </script>
</head>

<body class="hold-transition">
    <header class="main-header modern-navbar">
        <div class="navbar-left">
            <div class="dropdown custom-dropdown">
                <a href="#" class="user-profile-widget dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="../files/usuarios/1535417472.jpg" class="user-image" alt="User Image">
                    <div class="user-info">
                        <span class="user-name"><?php echo $_SESSION['nombre']; ?></span>
                        <small class="user-role"><?php echo $_SESSION['cargo']; ?></small>
                    </div>
                    <span class="custom-caret"></span>
                </a>
                <ul class="dropdown-menu user-dropdown-menu">
                    <li><a href="perfil.php" class="dropdown-item"><i class="fa fa-user-edit"></i> Editar Perfil</a></li>
                    <li class="divider"></li>
                    <li><a href="../ajax/usuario.php?op=salir" class="dropdown-item text-danger"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>

        <div class="navbar-center">
            <nav class="main-nav">
                <ul>
                    <!-- Panel de Control -->
                    <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1): ?>
                        <li><a href="escritorio.php" class="nav-button"><i class="fa fa-home"></i> Inicio</a></li>
                    <?php endif; ?>
                    
                    <!-- Gestión Académica (Dropdown) -->
                    <?php
                    $mostrar_gestion_academica = (isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1) ||
                                                (isset($_SESSION['secciones']) && $_SESSION['secciones'] == 1) ||
                                                (isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1) ||
                                                (isset($_SESSION['asistencia']) && $_SESSION['asistencia'] == 1);
                    ?>

                    <?php if ($mostrar_gestion_academica): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-graduation-cap"></i> Gestión Académica <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1): ?>
                                    <li><a href="aulas.php" class="dropdown-item"><i class="fa fa-university"></i> Aulas</a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['secciones']) && $_SESSION['secciones'] == 1): ?>
                                    <li><a href="secciones.php" class="dropdown-item"><i class="fa fa-sitemap"></i> Secciones</a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1): ?>
                                    <li><a href="ninos.php" class="dropdown-item"><i class="fa fa-child"></i> Niños</a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['asistencia']) && $_SESSION['asistencia'] == 1): ?>
                                    <li><a href="asistencia.php" class="dropdown-item"><i class="fa fa-calendar-check"></i> Control de Asistencias</a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1): ?>
                                    <li><a href="responsables_retiro.php" class="dropdown-item"><i class="fa fa-users"></i> Responsables de Retiro</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Administración y Alertas (Dropdown) -->
                    <?php
                    $mostrar_administracion = (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) ||
                                            (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1);
                    ?>

                    <?php if ($mostrar_administracion): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cogs"></i> Administración <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1): ?>
                                    <li><a href="usuario.php" class="dropdown-item"><i class="fa fa-users"></i> Usuarios</a></li>
                                    <li><a href="permiso.php" class="dropdown-item"><i class="fa fa-key"></i> Permisos</a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1): ?>
                                    <li><a href="alertas.php" class="dropdown-item"><i class="fa fa-bell"></i> Alertas</a></li>
                                    <li><a href="permisos_ausencia.php" class="dropdown-item"><i class="fa fa-calendar-times"></i> Permisos de Ausencia</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Información Médica (Dropdown) -->
                    <?php
                    $mostrar_info_medica = (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) ||
                                         (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería');
                    ?>

                    <?php if ($mostrar_info_medica): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-medkit"></i> Información Médica <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="enfermedades.php" class="dropdown-item"><i class="fa fa-stethoscope"></i> Enfermedades</a></li>
                                <li><a href="medicamentos.php" class="dropdown-item"><i class="fa fa-pills"></i> Medicamentos</a></li>
                                <li><a href="alergias.php" class="dropdown-item"><i class="fa fa-allergies"></i> Alergias</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <li><a href="acerca.php" class="nav-button"><i class="fa fa-info-circle"></i> Acerca de</a></li>
                </ul>
            </nav>
        </div>

        <div class="navbar-right">
            <div class="real-time-clock">
                <i class="fa fa-clock-o"></i> <span id="current-time"></span>
            </div>
        </div>
    </header>



    <div class="content-wrapper">
        <section class="content">