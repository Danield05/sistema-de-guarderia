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
</head>

<body class="hold-transition">
    <header class="main-header modern-navbar">
        <div class="navbar-left">
            <div class="user-profile-widget">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                <div class="user-info">
                    <span class="user-name"><?php echo $_SESSION['nombre']; ?></span>
                    <small class="user-role"><?php echo $_SESSION['cargo']; ?></small>
                </div>
            </div>
        </div>

        <div class="navbar-center">
            <nav class="main-nav">
                <ul>
                    <?php if ($_SESSION['escritorio'] == 1): ?>
                        <li><a href="escritorio.php" class="nav-button"><i class="fa fa-home"></i> Inicio</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['grupos'] == 1): ?>
                        <li><a href="grupos.php" class="nav-button"><i class="fa fa-users"></i> Grupos</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['acceso'] == 1): ?>
                        <li><a href="usuario.php" class="nav-button"><i class="fa fa-graduation-cap"></i> Profesores</a></li>
                        <li><a href="permiso.php" class="nav-button"><i class="fa fa-key"></i> Permisos</a></li>
                    <?php endif; ?>
                    <li><a href="acerca.php" class="nav-button"><i class="fa fa-info-circle"></i> Acerca de</a></li>
                </ul>
            </nav>
        </div>

        <div class="navbar-right">
            <div class="real-time-clock">
                <i class="fa fa-clock-o"></i> <span id="current-time"></span>
            </div>
            <a href="../ajax/usuario.php?op=salir" class="btn btn-danger btn-flat logout-button">
                <i class="fa fa-sign-out"></i> Cerrar Sesión
            </a>
        </div>
    </header>



    <div class="content-wrapper">
        <section class="content">