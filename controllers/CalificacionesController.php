<?php
require_once "../models/Calificaciones.php";
if (strlen(session_id()) < 1)
    session_start();

class CalificacionesController {

    public function guardarYEditar() {
        $calificaciones = new Calificaciones();

        $id = isset($_POST["idcalificacion"]) ? limpiarCadena($_POST["idcalificacion"]) : "";
        $alumno_id = isset($_POST["idalumno"]) ? limpiarCadena($_POST["idalumno"]) : "";
        $curso_id = isset($_POST["idcurso"]) ? limpiarCadena($_POST["idcurso"]) : "";
        $valor = isset($_POST["valor"]) ? limpiarCadena($_POST["valor"]) : "";
        $user_id = $_SESSION["idusuario"];

        if (empty($id)) {
            $rspta = $calificaciones->insertar($alumno_id, $curso_id, $valor, $user_id);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $calificaciones->editar($id, $alumno_id, $curso_id, $valor, $user_id);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
    }

    public function desactivar() {
        $calificaciones = new Calificaciones();
        $id = isset($_POST["idcalificacion"]) ? limpiarCadena($_POST["idcalificacion"]) : "";
        $rspta = $calificaciones->desactivar($id);
        echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
    }

    public function activar() {
        $calificaciones = new Calificaciones();
        $id = isset($_POST["idcalificacion"]) ? limpiarCadena($_POST["idcalificacion"]) : "";
        $rspta = $calificaciones->activar($id);
        echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
    }

    public function mostrar() {
        $calificaciones = new Calificaciones();
        $id = isset($_POST["idcalificacion"]) ? limpiarCadena($_POST["idcalificacion"]) : "";
        $rspta = $calificaciones->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $calificaciones = new Calificaciones();
        $user_id = $_SESSION["idusuario"];
        $rspta = $calificaciones->listar($user_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->is_active) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->alumno,
                "2" => $reg->curso,
                "3" => $reg->valor,
                "4" => ($reg->is_active) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>'
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