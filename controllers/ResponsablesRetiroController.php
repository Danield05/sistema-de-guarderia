<?php
require_once "../models/ResponsablesRetiro.php";
if (strlen(session_id()) < 1)
    session_start();

class ResponsablesRetiroController {

    public function guardarYEditar() {
        $responsables_retiro = new ResponsablesRetiro();

        $id = isset($_POST["idresponsable"]) ? limpiarCadena($_POST["idresponsable"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $nombre_completo = isset($_POST["nombre_completo"]) ? limpiarCadena($_POST["nombre_completo"]) : "";
        $parentesco = isset($_POST["parentesco"]) ? limpiarCadena($_POST["parentesco"]) : "";
        $telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
        $autorizacion_firma = isset($_POST["autorizacion_firma"]) ? limpiarCadena($_POST["autorizacion_firma"]) : "";
        $periodo_inicio = isset($_POST["periodo_inicio"]) ? limpiarCadena($_POST["periodo_inicio"]) : "";
        $periodo_fin = isset($_POST["periodo_fin"]) ? limpiarCadena($_POST["periodo_fin"]) : "";

        if (empty($id)) {
            $rspta = $responsables_retiro->insertar($id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin);
            echo $rspta ? "Responsable registrado correctamente" : "No se pudo registrar el responsable";
        } else {
            $rspta = $responsables_retiro->editar($id, $id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin);
            echo $rspta ? "Responsable actualizado correctamente" : "No se pudo actualizar el responsable";
        }
    }

    public function eliminar() {
        $responsables_retiro = new ResponsablesRetiro();
        $id = isset($_POST["idresponsable"]) ? limpiarCadena($_POST["idresponsable"]) : "";
        $rspta = $responsables_retiro->eliminar($id);
        echo $rspta ? "Responsable eliminado correctamente" : "No se pudo eliminar el responsable";
    }

    public function mostrar() {
        $responsables_retiro = new ResponsablesRetiro();
        $id = isset($_POST["idresponsable"]) ? limpiarCadena($_POST["idresponsable"]) : "";
        $rspta = $responsables_retiro->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $responsables_retiro = new ResponsablesRetiro();
        $rspta = $responsables_retiro->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_responsable . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_responsable . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->nombre_completo,
                "3" => $reg->parentesco,
                "4" => $reg->telefono,
                "5" => $reg->periodo_inicio,
                "6" => $reg->periodo_fin,
                "7" => $reg->autorizacion_firma,
                "8" => $reg->id_responsable
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
        $responsables_retiro = new ResponsablesRetiro();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $responsables_retiro->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_responsable,
                "1" => $reg->nombre_completo,
                "2" => $reg->parentesco,
                "3" => $reg->telefono,
                "4" => $reg->autorizacion_firma,
                "5" => $reg->periodo_inicio,
                "6" => $reg->periodo_fin
            );
        }
        echo json_encode($data);
    }
}
?>