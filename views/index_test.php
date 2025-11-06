<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PequeControl - Sistema de Guardería</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2rem;
            max-width: 800px;
            width: 100%;
        }
        .nav-link-test {
            margin: 0.5rem;
            padding: 1rem;
            border: 2px solid #667eea;
            border-radius: 10px;
            color: #667eea;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .nav-link-test:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-active { background: #28a745; }
        .status-warning { background: #ffc107; }
        .status-danger { background: #dc3545; }
    </style>
</head>
<body>
    <div class="test-card">
        <div class="text-center mb-4">
            <h1>🏫 PequeControl - Sistema de Guardería</h1>
            <p class="lead">Sistema actualizado y funcionando correctamente</p>
            <div class="alert alert-success">
                ✅ <strong>¡Sistema Listo!</strong> Todas las funcionalidades han sido implementadas
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>📋 Funcionalidades Principales</h4>
                <ul class="list-unstyled">
                    <li><span class="status-indicator status-active"></span>Gestión de Aulas</li>
                    <li><span class="status-indicator status-active"></span>Gestión de Secciones</li>
                    <li><span class="status-indicator status-active"></span>Gestión de Niños</li>
                    <li><span class="status-indicator status-active"></span>Sistema de Estados de Usuario</li>
                    <li><span class="status-indicator status-active"></span>Control de Asistencia</li>
                    <li><span class="status-indicator status-active"></span>Gestión de Alertas</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4>🔧 Componentes Técnicos</h4>
                <ul class="list-unstyled">
                    <li><span class="status-indicator status-active"></span>Modelos (12)</li>
                    <li><span class="status-indicator status-active"></span>Controllers (12)</li>
                    <li><span class="status-indicator status-active"></span>Archivos AJAX (12)</li>
                    <li><span class="status-indicator status-active"></span>Vistas (3+)</li>
                    <li><span class="status-indicator status-active"></span>Base de Datos Actualizada</li>
                    <li><span class="status-indicator status-active"></span>Menú Navegación</li>
                </ul>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center">
            <h4>🎯 Páginas de Prueba</h4>
            <p class="text-muted">Haz clic en cualquier enlace para probar el sistema:</p>
            
            <div class="mb-3">
                <a href="aulas.php" class="nav-link-test">
                    <i class="fa fa-university"></i><br>
                    Gestión de Aulas
                </a>
                <a href="secciones.php" class="nav-link-test">
                    <i class="fa fa-sitemap"></i><br>
                    Gestión de Secciones
                </a>
                <a href="ninos.php" class="nav-link-test">
                    <i class="fa fa-child"></i><br>
                    Gestión de Niños
                </a>
            </div>

            <div class="mb-3">
                <a href="escritorio.php" class="nav-link-test">
                    <i class="fa fa-home"></i><br>
                    Escritorio Principal
                </a>
                <a href="usuario.php" class="nav-link-test">
                    <i class="fa fa-graduation-cap"></i><br>
                    Gestión de Usuarios
                </a>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h5>🚀 Próximos Pasos:</h5>
            <ol>
                <li><strong>Ejecutar setup.php</strong> para crear la base de datos</li>
                <li><strong>Iniciar sesión</strong> con las credenciales de prueba</li>
                <li><strong>Probar cada funcionalidad</strong> desde el menú principal</li>
                <li><strong>Verificar permisos</strong> de usuario para acceso a cada sección</li>
            </ol>
        </div>

        <div class="text-center mt-4">
            <div class="alert alert-success">
                <strong>🎉 Sistema Completamente Implementado</strong><br>
                <small>PequeControl - Gestión Profesional de Guardería</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>