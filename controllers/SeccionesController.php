<?php
require_once "../models/Secciones.php";
if (strlen(session_id()) < 1)
    session_start();

class SeccionesController {

    public function guardarYEditar() {
        $secciones = new Secciones();

        $id = isset($_POST["idseccion"]) ? limpiarCadena($_POST["idseccion"]) : "";
        $nombre_seccion = isset($_POST["nombre_seccion"]) ? limpiarCadena($_POST["nombre_seccion"]) : "";
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";

        if (empty($id)) {
            $rspta = $secciones->insertar($nombre_seccion, $aula_id);
            echo $rspta ? "Sección registrada correctamente" : "No se pudo registrar la sección";
        } else {
            $rspta = $secciones->editar($id, $nombre_seccion, $aula_id);
            echo $rspta ? "Sección actualizada correctamente" : "No se pudo actualizar la sección";
        }
    }

    public function desactivar() {
        $secciones = new Secciones();
        $id = isset($_POST["idseccion"]) ? limpiarCadena($_POST["idseccion"]) : "";
        $rspta = $secciones->desactivar($id);
        echo $rspta ? "Sección desactivada correctamente" : "No se pudo desactivar la sección";
    }

    public function activar() {
        $secciones = new Secciones();
        $id = isset($_POST["idseccion"]) ? limpiarCadena($_POST["idseccion"]) : "";
        $rspta = $secciones->activar($id);
        echo $rspta ? "Sección activada correctamente" : "No se pudo activar la sección";
    }

    public function mostrar() {
        $secciones = new Secciones();
        $id = isset($_POST["idseccion"]) ? limpiarCadena($_POST["idseccion"]) : "";
        $rspta = $secciones->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $secciones = new Secciones();
        $rspta = $secciones->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->estado) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_seccion . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id_seccion . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_seccion . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id_seccion . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre_seccion,
                "2" => $reg->nombre_aula,
                "3" => $reg->aula_id
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

    public function listarPorAula() {
        $secciones = new Secciones();
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";
        $rspta = $secciones->listarPorAula($aula_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_seccion,
                "1" => $reg->nombre_seccion
            );
        }
        echo json_encode($data);
    }
}
?>