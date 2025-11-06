<?php
require_once "../models/Asistencia.php";
if (strlen(session_id()) < 1)
    session_start();

class AsistenciaController {

    public function guardarYEditar() {
        $asistencia = new Asistencia();

        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $estado_id = isset($_POST["estado_id"]) ? limpiarCadena($_POST["estado_id"]) : "";
        $observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";

        if (empty($id)) {
            $rspta = $asistencia->insertar($id_nino, $fecha, $estado_id, $observaciones);
            echo $rspta ? "Asistencia registrada correctamente" : "No se pudo registrar la asistencia";
        } else {
            $rspta = $asistencia->editar($id, $id_nino, $fecha, $estado_id, $observaciones);
            echo $rspta ? "Asistencia actualizada correctamente" : "No se pudo actualizar la asistencia";
        }
    }

    public function eliminar() {
        $asistencia = new Asistencia();
        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $rspta = $asistencia->eliminar($id);
        echo $rspta ? "Asistencia eliminada correctamente" : "No se pudo eliminar la asistencia";
    }

    public function mostrar() {
        $asistencia = new Asistencia();
        $id = isset($_POST["idasistencia"]) ? limpiarCadena($_POST["idasistencia"]) : "";
        $rspta = $asistencia->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $asistencia = new Asistencia();
        $rspta = $asistencia->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_asistencia . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_asistencia . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->fecha,
                "3" => $reg->nombre_estado,
                "4" => $reg->observaciones
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

    public function listarPorNino() {
        $asistencia = new Asistencia();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $asistencia->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_asistencia,
                "1" => $reg->fecha,
                "2" => $reg->nombre_estado,
                "3" => $reg->observaciones
            );
        }
        echo json_encode($data);
    }

    public function listarPorFecha() {
        $asistencia = new Asistencia();
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $rspta = $asistencia->listarPorFecha($fecha);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_asistencia,
                "1" => $reg->nino,
                "2" => $reg->nombre_estado,
                "3" => $reg->observaciones
            );
        }
        echo json_encode($data);
    }

    public function verificarAsistencia() {
        $asistencia = new Asistencia();
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $estado_id = isset($_POST["estado_id"]) ? limpiarCadena($_POST["estado_id"]) : "";
        
        $rspta = $asistencia->verificarAsistencia($fecha, $id_nino, $estado_id);
        echo json_encode($rspta);
    }
}
?>