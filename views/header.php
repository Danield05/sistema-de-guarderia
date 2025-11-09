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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon Moderno -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏠</text></svg>">

    <!-- DATATABLES -->
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">

    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="../public/css/master-styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/frontend-modern.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/custom-tables.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../public/css/navbar-custom.css?v=<?php echo time(); ?>">
    
    <!-- JavaScript personalizado para dropdown del navbar -->
    <script src="../views/scripts/navbar.js?v=<?php echo time(); ?>"></script>
    <!-- Script para actualizar imagen del navbar -->
    <script>
        // Función para actualizar la imagen del navbar desde cualquier página
        function actualizarImagenNavbar() {
            fetch('../ajax/usuario.php?op=mostrar_perfil')
                .then(response => response.json())
                .then(data => {
                    // Mostrar imagen en el navbar
                    if (data.fotografia) {
                        document.getElementById('navbarUserImage').innerHTML = '<img src="../files/usuarios/' + data.fotografia + '" style="width: 100%; height: 100%; object-fit: cover;">';
                    } else {
                        document.getElementById('navbarUserImage').innerHTML = '<i class="fas fa-user" style="font-size: 1.2rem; color: #666;"></i>';
                    }
                })
                .catch(error => {
                    console.error("Error actualizando imagen del navbar:", error);
                });
        }

        // Actualizar imagen del navbar cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            actualizarImagenNavbar();
        });
    </script>
</head>

<body class="hold-transition">
    <header class="main-header navbar">
        <div class="navbar-left">
            <div class="dropdown custom-dropdown">
                <a href="#" class="user-profile-widget dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <div id="navbarUserImage" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.3); margin-right: 10px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <i class="fas fa-user" style="font-size: 1.2rem; color: #666;"></i>
                </div>
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
                    $mostrar_gestion_academica = ((isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1) ||
                                                (isset($_SESSION['secciones']) && $_SESSION['secciones'] == 1) ||
                                                (isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1) ||
                                                (isset($_SESSION['horarios']) && $_SESSION['horarios'] == 1) ||
                                                (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) ||
                                                (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') ||
                                                (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro')) &&
                                                $_SESSION['cargo'] != 'Médico/Enfermería';
                    ?>

                    <?php if ($mostrar_gestion_academica): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-graduation-cap"></i> Gestión Académica <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['aulas']) && $_SESSION['aulas'] == 1): ?>
                                    <li><a href="aulas.php" class="dropdown-item"><i class="fas fa-school"></i> <span>Aulas</span></a></li>
                                <?php endif; ?>

                                <?php if ((isset($_SESSION['secciones']) && $_SESSION['secciones'] == 1) && $_SESSION['cargo'] != 'Maestro'): ?>
                                    <li><a href="secciones.php" class="dropdown-item"><i class="fas fa-layer-group"></i> <span>Secciones</span></a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['ninos']) && $_SESSION['ninos'] == 1): ?>
                                    <li><a href="ninos.php" class="dropdown-item"><i class="fas fa-baby"></i> <span>Niños</span></a></li>
                                <?php endif; ?>

                                <?php if ((isset($_SESSION['horarios']) && $_SESSION['horarios'] == 1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador')): ?>
                                    <li><a href="horarios.php" class="dropdown-item"><i class="fas fa-clock"></i> <span>Horarios de Estudio</span></a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1): ?>
                                    <li><a href="responsables_retiro.php" class="dropdown-item"><i class="fas fa-user-friends"></i> <span>Responsables de Retiro</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Administración y Alertas (Dropdown) -->
                    <?php
                    $mostrar_administracion = (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) ||
                                            (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) ||
                                            (isset($_SESSION['grupos']) && $_SESSION['grupos'] == 1) ||
                                            (isset($_SESSION['asistencias']) && $_SESSION['asistencias'] == 1) ||
                                            (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') ||
                                            (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro');
                    ?>

                    <?php if ($mostrar_administracion): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cogs"></i> Administración <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1): ?>
                                    <li><a href="usuario.php" class="dropdown-item"><i class="fas fa-user-shield"></i> <span>Usuarios</span></a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1): ?>
                                    <li><a href="alertas.php" class="dropdown-item"><i class="fas fa-bell"></i> <span>Alertas</span></a></li>
                                    <li><a href="permisos_ausencia.php" class="dropdown-item"><i class="fas fa-calendar-times"></i> <span>Permisos de Ausencia</span></a></li>
                                <?php endif; ?>

                                <?php if ((isset($_SESSION['asistencia']) && $_SESSION['asistencia'] == 1) || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Administrador') || (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro')): ?>
                                    <li><a href="asistencia.php" class="dropdown-item"><i class="fas fa-calendar-check"></i> <span>Control de Asistencias</span></a></li>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería'): ?>
                                    <li><a href="ninos.php" class="dropdown-item"><i class="fas fa-baby"></i> <span>Ver Niños</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Información Médica (Dropdown) -->
                    <?php
                    $mostrar_info_medica = (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) ||
                                         (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Médico/Enfermería') ||
                                         (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') ||
                                         (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Padre/Tutor');
                    ?>

                    <?php if ($mostrar_info_medica): ?>
                        <li class="dropdown custom-dropdown">
                            <a href="#" class="nav-button dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-medkit"></i> Información Médica <span class="custom-caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="enfermedades.php" class="dropdown-item"><i class="fas fa-stethoscope"></i> <span>Enfermedades</span></a></li>
                                <li><a href="medicamentos.php" class="dropdown-item"><i class="fas fa-pills"></i> <span>Medicamentos</span></a></li>
                                <li><a href="alergias.php" class="dropdown-item"><i class="fas fa-allergies"></i> <span>Alergias</span></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <li><a href="acerca.php" class="nav-button"><i class="fa fa-info-circle"></i> Acerca de</a></li>
                </ul>
            </nav>
        </div>

        <div class="navbar-right">
            <div class="real-time-clock">
                <i class="fas fa-clock"></i> <span id="current-time"></span>
            </div>
        </div>
    </header>
    </header>



    <div class="content-wrapper">
        <section class="content">