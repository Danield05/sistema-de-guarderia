<?php
require_once "../models/Cursos.php";
if (strlen(session_id()) < 1)
    session_start();

class CursosController {

    public function guardarYEditar() {
        $cursos = new Cursos();

        $id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : "";
        $nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
        $grupo_id = isset($_POST["idgrupo"]) ? limpiarCadena($_POST["idgrupo"]) : "";
        $user_id = $_SESSION["idusuario"];

        if (empty($id)) {
            $rspta = $cursos->insertar($nombre, $grupo_id, $user_id);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $cursos->editar($id, $nombre, $grupo_id, $user_id);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
    }

    public function desactivar() {
        $cursos = new Cursos();
        $id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : "";
        $rspta = $cursos->desactivar($id);
        echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
    }

    public function activar() {
        $cursos = new Cursos();
        $id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : "";
        $rspta = $cursos->activar($id);
        echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
    }

    public function mostrar() {
        $cursos = new Cursos();
        $id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : "";
        $rspta = $cursos->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $cursos = new Cursos();
        $grupo_id = isset($_REQUEST["idgrupo"]) ? $_REQUEST["idgrupo"] : "";
        $rspta = $cursos->listar($grupo_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->is_active) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->name,
                "2" => $reg->grupo,
                "3" => ($reg->is_active) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    }
}
?>