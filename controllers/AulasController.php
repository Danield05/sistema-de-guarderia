<?php
require_once "../models/Aulas.php";
if (strlen(session_id()) < 1)
    session_start();

class AulasController {

    public function guardarYEditar() {
        $aulas = new Aulas();

        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $nombre_aula = isset($_POST["nombre_aula"]) ? limpiarCadena($_POST["nombre_aula"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $aulas->insertar($nombre_aula, $descripcion);
            echo $rspta ? "Aula registrada correctamente" : "No se pudo registrar el aula";
        } else {
            $rspta = $aulas->editar($id, $nombre_aula, $descripcion);
            echo $rspta ? "Aula actualizada correctamente" : "No se pudo actualizar el aula";
        }
    }

    public function desactivar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->desactivar($id);
        echo $rspta ? "Aula desactivada correctamente" : "No se pudo desactivar el aula";
    }

    public function activar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->activar($id);
        echo $rspta ? "Aula activada correctamente" : "No se pudo activar el aula";
    }

    public function mostrar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $aulas = new Aulas();
        $rspta = $aulas->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->estado) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_aula . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id_aula . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_aula . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id_aula . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre_aula,
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
}
?>