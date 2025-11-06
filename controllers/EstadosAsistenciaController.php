<?php
require_once "../models/EstadosAsistencia.php";
if (strlen(session_id()) < 1)
    session_start();

class EstadosAsistenciaController {

    public function guardarYEditar() {
        $estados_asistencia = new EstadosAsistencia();

        $id = isset($_POST["idestado"]) ? limpiarCadena($_POST["idestado"]) : "";
        $nombre_estado = isset($_POST["nombre_estado"]) ? limpiarCadena($_POST["nombre_estado"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $estados_asistencia->insertar($nombre_estado, $descripcion);
            echo $rspta ? "Estado de asistencia registrado correctamente" : "No se pudo registrar el estado";
        } else {
            $rspta = $estados_asistencia->editar($id, $nombre_estado, $descripcion);
            echo $rspta ? "Estado de asistencia actualizado correctamente" : "No se pudo actualizar el estado";
        }
    }

    public function eliminar() {
        $estados_asistencia = new EstadosAsistencia();
        $id = isset($_POST["idestado"]) ? limpiarCadena($_POST["idestado"]) : "";
        $rspta = $estados_asistencia->eliminar($id);
        echo $rspta ? "Estado de asistencia eliminado correctamente" : "No se pudo eliminar el estado";
    }

    public function mostrar() {
        $estados_asistencia = new EstadosAsistencia();
        $id = isset($_POST["idestado"]) ? limpiarCadena($_POST["idestado"]) : "";
        $rspta = $estados_asistencia->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $estados_asistencia = new EstadosAsistencia();
        $rspta = $estados_asistencia->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_estado . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_estado . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nombre_estado,
                "2" => $reg->descripcion
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

    public function listarActivos() {
        $estados_asistencia = new EstadosAsistencia();
        $rspta = $estados_asistencia->listarActivos();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_estado,
                "1" => $reg->nombre_estado
            );
        }
        echo json_encode($data);
    }
}
?>