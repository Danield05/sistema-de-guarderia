<?php
require_once "../models/Enfermedades.php";
if (strlen(session_id()) < 1)
    session_start();

class EnfermedadesController {

    public function guardarYEditar() {
        $enfermedades = new Enfermedades();

        $id = isset($_POST["idenfermedad"]) ? limpiarCadena($_POST["idenfermedad"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $nombre_enfermedad = isset($_POST["nombre_enfermedad"]) ? limpiarCadena($_POST["nombre_enfermedad"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
        $fecha_diagnostico = isset($_POST["fecha_diagnostico"]) ? limpiarCadena($_POST["fecha_diagnostico"]) : "";

        if (empty($id)) {
            $rspta = $enfermedades->insertar($id_nino, $nombre_enfermedad, $descripcion, $fecha_diagnostico);
            echo $rspta ? "Enfermedad registrada correctamente" : "No se pudo registrar la enfermedad";
        } else {
            $rspta = $enfermedades->editar($id, $id_nino, $nombre_enfermedad, $descripcion, $fecha_diagnostico);
            echo $rspta ? "Enfermedad actualizada correctamente" : "No se pudo actualizar la enfermedad";
        }
    }

    public function eliminar() {
        $enfermedades = new Enfermedades();
        $id = isset($_POST["idenfermedad"]) ? limpiarCadena($_POST["idenfermedad"]) : "";
        $rspta = $enfermedades->eliminar($id);
        echo $rspta ? "Enfermedad eliminada correctamente" : "No se pudo eliminar la enfermedad";
    }

    public function mostrar() {
        $enfermedades = new Enfermedades();
        $id = isset($_POST["idenfermedad"]) ? limpiarCadena($_POST["idenfermedad"]) : "";
        $rspta = $enfermedades->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $enfermedades = new Enfermedades();
        $rspta = $enfermedades->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_enfermedad . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_enfermedad . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->nombre_enfermedad,
                "3" => $reg->descripcion,
                "4" => $reg->fecha_diagnostico
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
        $enfermedades = new Enfermedades();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $enfermedades->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_enfermedad,
                "1" => $reg->nombre_enfermedad,
                "2" => $reg->descripcion,
                "3" => $reg->fecha_diagnostico
            );
        }
        echo json_encode($data);
    }
}
?>