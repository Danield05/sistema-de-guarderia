<?php
require_once "../models/Grupos.php";
if (strlen(session_id()) < 1)
    session_start();

class GruposController {

    public function guardarYEditar() {
        $grupos = new Grupos();

        $idgrupo = isset($_POST["idgrupo"]) ? limpiarCadena($_POST["idgrupo"]) : "";
        $idusuario = $_SESSION["idusuario"];
        $nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
        $favorito = isset($_POST["favorito"]) ? 1 : 0;

        if (empty($idgrupo)) {
            $rspta = $grupos->insertar($nombre, $favorito, $idusuario);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $grupos->editar($idgrupo, $nombre, $favorito, $idusuario);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
    }

    public function anular() {
        $grupos = new Grupos();
        $idgrupo = isset($_POST["idgrupo"]) ? limpiarCadena($_POST["idgrupo"]) : "";
        $rspta = $grupos->anular($idgrupo);
        echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
    }

    public function mostrar() {
        $grupos = new Grupos();
        $idgrupo = isset($_POST["idgrupo"]) ? limpiarCadena($_POST["idgrupo"]) : "";
        $rspta = $grupos->mostrar($idgrupo);
        echo json_encode($rspta);
    }

    public function listar() {
        $grupos = new Grupos();
        $rspta = $grupos->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idgrupo . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idgrupo . ')"><i class="fa fa-close"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->usuario
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