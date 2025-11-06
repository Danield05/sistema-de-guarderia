<?php
require_once "../models/Calificaciones.php";
require_once "../models/Alertas.php";
if (strlen(session_id()) < 1)
    session_start();

class CalificacionesController {

    public function guardarYEditar() {
        $calificaciones = new Calificaciones();

        $id = isset($_POST["idevaluacion"]) ? limpiarCadena($_POST["idevaluacion"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $evaluacion = isset($_POST["evaluacion"]) ? limpiarCadena($_POST["evaluacion"]) : "";
        $tipo_evaluacion = isset($_POST["tipo_evaluacion"]) ? limpiarCadena($_POST["tipo_evaluacion"]) : "";

        if (empty($id)) {
            $rspta = $calificaciones->insertar($id_nino, $evaluacion, $tipo_evaluacion);
            echo $rspta ? "Evaluación registrada correctamente" : "No se pudo registrar la evaluación";
        } else {
            $rspta = $calificaciones->editar($id, $id_nino, $evaluacion, $tipo_evaluacion);
            echo $rspta ? "Evaluación actualizada correctamente" : "No se pudo actualizar la evaluación";
        }
    }

    public function eliminar() {
        $calificaciones = new Calificaciones();
        $id = isset($_POST["idevaluacion"]) ? limpiarCadena($_POST["idevaluacion"]) : "";
        // Como las evaluaciones se almacenan como alertas, eliminamos la alerta correspondiente
        require_once "../models/Alertas.php";
        $alertas = new Alertas();
        $rspta = $alertas->eliminar($id);
        echo $rspta ? "Evaluación eliminada correctamente" : "No se pudo eliminar la evaluación";
    }

    public function mostrar() {
        // Como las evaluaciones se almacenan como alertas, usamos el modelo Alertas
        require_once "../models/Alertas.php";
        $alertas = new Alertas();
        $id = isset($_POST["idevaluacion"]) ? limpiarCadena($_POST["idevaluacion"]) : "";
        $rspta = $alertas->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $calificaciones = new Calificaciones();
        $fecha_inicio = isset($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : date('Y-m-d');
        
        $rspta = $calificaciones->listar_por_fecha($fecha_inicio);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_alerta . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_alerta . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->mensaje,
                "3" => $reg->tipo,
                "4" => $reg->fecha_alerta,
                "5" => ($reg->estado == 'Pendiente') ? '<span class="label bg-yellow">Pendiente</span>' : '<span class="label bg-green">Completada</span>'
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
        $calificaciones = new Calificaciones();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : date('Y-m-01');
        $fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : date('Y-m-d');
        
        $rspta = $calificaciones->listar_calificacion($id_nino, $fecha_inicio, $fecha_fin);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_alerta,
                "1" => $reg->fecha_alerta,
                "2" => $reg->mensaje,
                "3" => $reg->tipo,
                "4" => ($reg->estado == 'Pendiente') ? '<span class="label bg-yellow">Pendiente</span>' : '<span class="label bg-green">Completada</span>'
            );
        }
        echo json_encode($data);
    }
}
?>