<?php
if (strlen(session_id()) < 1)
    session_start();
require_once "../models/Usuario.php";

class UsuarioController {

    public function verificar($logina, $clavea) {
        $usuario = new Usuario();
        $clavehash = hash("SHA256", $clavea);
        $rspta = $usuario->verificar($logina, $clavehash);

        $fetch = $rspta->fetch(PDO::FETCH_OBJ);

        if (isset($fetch) && $fetch !== false) {
            $_SESSION['idusuario'] = $fetch->id_usuario;
            $_SESSION['nombre'] = $fetch->nombre_completo;
            $_SESSION['email'] = $fetch->email;
            $_SESSION['login'] = $fetch->email; // usar email como login
            $_SESSION['cargo'] = $fetch->nombre_rol; // usar el nombre del rol como cargo
            $_SESSION['rol_id'] = $fetch->rol_id;
            $_SESSION['fotografia'] = $fetch->fotografia; // agregar imagen de perfil a la sesión

            // Configurar permisos específicos para el sistema de guardería
            // Todos los administradores tienen acceso completo
            if ($fetch->nombre_rol == 'Administrador') {
                $_SESSION['escritorio'] = 1;
                $_SESSION['aulas'] = 1;
                $_SESSION['secciones'] = 1;
                $_SESSION['ninos'] = 1;
                $_SESSION['grupos'] = 1;
                $_SESSION['acceso'] = 1;
                $_SESSION['enfermedades'] = 1;
                $_SESSION['medicamentos'] = 1;
                $_SESSION['alergias'] = 1;
                $_SESSION['usuarios'] = 1;
                $_SESSION['asistencias'] = 1;
                $_SESSION['reportes'] = 1;
            }
            // Otros roles tienen permisos básicos
            else {
                $_SESSION['escritorio'] = 1;
                $_SESSION['aulas'] = 0;
                $_SESSION['secciones'] = 0;
                $_SESSION['ninos'] = 0;
                $_SESSION['grupos'] = 0;
                $_SESSION['acceso'] = 0;
                $_SESSION['enfermedades'] = 0;
                $_SESSION['medicamentos'] = 0;
                $_SESSION['alergias'] = 0;
                $_SESSION['usuarios'] = 0;
                $_SESSION['asistencias'] = 0;
                $_SESSION['reportes'] = 0;
            }

            return json_encode($fetch);
        } else {
            return "null";
        }
    }

    public function salir() {
        session_unset();
        session_destroy();
        header("Location: ../views/landing.php");
    }
}
?>