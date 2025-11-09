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
            // Permisos para Maestros
            elseif ($fetch->nombre_rol == 'Maestro') {
                $_SESSION['escritorio'] = 1;
                $_SESSION['aulas'] = 1;
                $_SESSION['secciones'] = 1;
                $_SESSION['ninos'] = 1;
                $_SESSION['grupos'] = 1;
                $_SESSION['acceso'] = 0;
                $_SESSION['enfermedades'] = 1;
                $_SESSION['medicamentos'] = 1;
                $_SESSION['alergias'] = 1;
                $_SESSION['usuarios'] = 0;
                $_SESSION['asistencias'] = 1;
                $_SESSION['reportes'] = 1;
            }
            // Permisos para Padres/Tutores
            elseif ($fetch->nombre_rol == 'Padre/Tutor') {
                $_SESSION['escritorio'] = 1;
                $_SESSION['aulas'] = 0;
                $_SESSION['secciones'] = 0;
                $_SESSION['ninos'] = 1; // Solo ver su niño
                $_SESSION['grupos'] = 0;
                $_SESSION['acceso'] = 0;
                $_SESSION['enfermedades'] = 1; // Solo información médica de su niño
                $_SESSION['medicamentos'] = 1;
                $_SESSION['alergias'] = 1;
                $_SESSION['usuarios'] = 0;
                $_SESSION['asistencias'] = 0;
                $_SESSION['reportes'] = 0;
                $_SESSION['horarios'] = 1; // Ver horarios de su niño
                $_SESSION['alertas'] = 1; // Ver alertas de su niño
                $_SESSION['permisos_ausencia'] = 1; // Gestionar permisos de ausencia de su niño
                $_SESSION['responsables_retiro'] = 1; // Gestionar responsables de retiro de su niño
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