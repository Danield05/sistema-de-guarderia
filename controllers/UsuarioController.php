<?php
session_start();
require_once "../models/Usuario.php";

class UsuarioController {

    public function verificar($logina, $clavea) {
        $usuario = new Usuario();
        $clavehash = hash("SHA256", $clavea);
        $rspta = $usuario->verificar($logina, $clavehash);

        $fetch = $rspta->fetch(PDO::FETCH_OBJ);

        if (isset($fetch)) {
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            $_SESSION['cargo'] = $fetch->cargo;

            $marcados = $usuario->listarmarcados($fetch->idusuario);
            $valores = array();

            while ($per = $marcados->fetch(PDO::FETCH_OBJ)) {
                array_push($valores, $per->idpermiso);
            }

            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['grupos'] = 1 : $_SESSION['grupos'] = 0;
            in_array(3, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
        }

        return json_encode($fetch);
    }

    public function salir() {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    }
}
?>