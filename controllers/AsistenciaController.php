<?php
require_once "../models/Asistencia.php";
if (strlen(session_id()) < 1)
    session_start();

class AsistenciaController {

    public function guardarYEditar() {
        $asistencia = new Asistencia();

        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $alumno_id = isset($_POST["idalumno"]) ? limpiarCadena($_POST["idalumno"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $tipo_id = isset($_POST["tipo_id"]) ? limpiarCadena($_POST["tipo_id"]) : "";
        $user_id = $_SESSION["idusuario"];

        if (empty($id)) {
            $rspta = $asistencia->insertar($alumno_id, $fecha, $tipo_id, $user_id);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $asistencia->editar($id, $alumno_id, $fecha, $tipo_id, $user_id);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
    }

    public function desactivar() {
        $asistencia = new Asistencia();
        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $rspta = $asistencia->desactivar($id);
        echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
    }

    public function activar() {
        $asistencia = new Asistencia();
        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $rspta = $asistencia->activar($id);
        echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
    }

    public function mostrar() {
        $asistencia = new Asistencia();
        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $rspta = $asistencia->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $asistencia = new Asistencia();
        $user_id = $_SESSION["idusuario"];
        $rspta = $asistencia->listar($user_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->is_active) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->alumno,
                "2" => $reg->fecha,
                "3" => $reg->tipo,
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