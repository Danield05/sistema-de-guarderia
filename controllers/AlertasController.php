<?php
require_once "../models/Alertas.php";
if (strlen(session_id()) < 1)
    session_start();

class AlertasController {

    public function guardarYEditar() {
        $alertas = new Alertas();

        $id = isset($_POST["idalerta"]) ? limpiarCadena($_POST["idalerta"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $mensaje = isset($_POST["mensaje"]) ? limpiarCadena($_POST["mensaje"]) : "";
        $tipo = isset($_POST["tipo"]) ? limpiarCadena($_POST["tipo"]) : "";
        $estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "Pendiente";

        if (empty($id)) {
            $rspta = $alertas->insertar($id_nino, $mensaje, $tipo, $estado);
            echo $rspta ? "Alerta registrada correctamente" : "No se pudo registrar la alerta";
        } else {
            $rspta = $alertas->editar($id, $id_nino, $mensaje, $tipo, $estado);
            echo $rspta ? "Alerta actualizada correctamente" : "No se pudo actualizar la alerta";
        }
    }

    public function eliminar() {
        $alertas = new Alertas();
        $id = isset($_POST["idalerta"]) ? limpiarCadena($_POST["idalerta"]) : "";
        $rspta = $alertas->eliminar($id);
        echo $rspta ? "Alerta eliminada correctamente" : "No se pudo eliminar la alerta";
    }

    public function marcarRespondida() {
        $alertas = new Alertas();
        $id = isset($_POST["idalerta"]) ? limpiarCadena($_POST["idalerta"]) : "";
        $rspta = $alertas->marcarRespondida($id);
        echo $rspta ? "Alerta marcada como respondida" : "No se pudo marcar la alerta como respondida";
    }

    public function mostrar() {
        $alertas = new Alertas();
        $id = isset($_POST["idalerta"]) ? limpiarCadena($_POST["idalerta"]) : "";
        $rspta = $alertas->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $alertas = new Alertas();
        $rspta = $alertas->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_alerta . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_alerta . ')"><i class="fa fa-trash"></i></button>' . ' ' . (($reg->estado == 'Pendiente') ? '<button class="btn btn-success btn-xs" onclick="marcarRespondida(' . $reg->id_alerta . ')"><i class="fa fa-check"></i></button>' : ''),
                "1" => $reg->nino,
                "2" => $reg->fecha_alerta,
                "3" => $reg->mensaje,
                "4" => $reg->tipo,
                "5" => ($reg->estado == 'Pendiente') ? '<span class="label bg-red">Pendiente</span>' : '<span class="label bg-green">Respondida</span>'
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
        $alertas = new Alertas();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $alertas->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_alerta,
                "1" => $reg->fecha_alerta,
                "2" => $reg->mensaje,
                "3" => $reg->tipo,
                "4" => ($reg->estado == 'Pendiente') ? '<span class="label bg-red">Pendiente</span>' : '<span class="label bg-green">Respondida</span>'
            );
        }
        echo json_encode($data);
    }

    public function listarPendientes() {
        $alertas = new Alertas();
        $rspta = $alertas->listarPendientes();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_alerta,
                "1" => $reg->nino,
                "2" => $reg->fecha_alerta,
                "3" => $reg->mensaje,
                "4" => $reg->tipo,
                "5" => '<button class="btn btn-success btn-xs" onclick="marcarRespondida(' . $reg->id_alerta . ')"><i class="fa fa-check"></i></button>'
            );
        }
        echo json_encode($data);
    }

    public function contarPendientes() {
        $alertas = new Alertas();
        $rspta = $alertas->contarPendientes();
        echo json_encode($rspta);
    }
}
?>