<?php
require_once "../models/Alergias.php";
if (strlen(session_id()) < 1)
    session_start();

class AlergiasController {

    public function guardarYEditar() {
        $alergias = new Alergias();

        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $tipo_alergia = isset($_POST["tipo_alergia"]) ? limpiarCadena($_POST["tipo_alergia"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $alergias->insertar($id_nino, $tipo_alergia, $descripcion);
            echo $rspta ? "Alergia registrada correctamente" : "No se pudo registrar la alergia";
        } else {
            $rspta = $alergias->editar($id, $id_nino, $tipo_alergia, $descripcion);
            echo $rspta ? "Alergia actualizada correctamente" : "No se pudo actualizar la alergia";
        }
    }

    public function eliminar() {
        $alergias = new Alergias();
        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";
        $rspta = $alergias->eliminar($id);
        echo $rspta ? "Alergia eliminada correctamente" : "No se pudo eliminar la alergia";
    }

    public function mostrar() {
        $alergias = new Alergias();
        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";
        $rspta = $alergias->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $alergias = new Alergias();
        $rspta = $alergias->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_alergia . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_alergia . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->tipo_alergia,
                "3" => $reg->descripcion,
                "4" => $reg->id_alergia
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
        $alergias = new Alergias();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $alergias->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_alergia,
                "1" => $reg->tipo_alergia,
                "2" => $reg->descripcion
            );
        }
        echo json_encode($data);
    }
}
?>