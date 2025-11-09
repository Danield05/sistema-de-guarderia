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
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrarAsistencia(' . $reg->id_asistencia . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminarAsistencia(' . $reg->id_asistencia . ')"><i class="fa fa-trash"></i></button>',
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

    public function listarConFiltros() {
        $asistencia = new Asistencia();
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";
        $seccion_id = isset($_POST["seccion_id"]) ? limpiarCadena($_POST["seccion_id"]) : "";
        $fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
        $fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
        $estado_id = isset($_POST["estado_id"]) ? limpiarCadena($_POST["estado_id"]) : "";
        
        $rspta = $asistencia->listarConFiltros($aula_id, $seccion_id, $fecha_inicio, $fecha_fin, $estado_id);
        $data = Array();
        
        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_asistencia,
                "1" => $reg->nombre_completo,
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

    public function obtenerNinos() {
        $asistencia = new Asistencia();
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";
        $seccion_id = isset($_POST["seccion_id"]) ? limpiarCadena($_POST["seccion_id"]) : "";
        
        $rspta = $asistencia->obtenerNinos($aula_id, $seccion_id);
        echo json_encode($rspta->fetchAll(PDO::FETCH_OBJ));
    }

    public function generarReporte() {
        $asistencia = new Asistencia();
        $formato = isset($_GET["formato"]) ? limpiarCadena($_GET["formato"]) : "csv";
        $aula_id = isset($_GET["aula_id"]) ? limpiarCadena($_GET["aula_id"]) : "";
        $seccion_id = isset($_GET["seccion_id"]) ? limpiarCadena($_GET["seccion_id"]) : "";
        $fecha_inicio = isset($_GET["fecha_inicio"]) ? limpiarCadena($_GET["fecha_inicio"]) : "";
        $fecha_fin = isset($_GET["fecha_fin"]) ? limpiarCadena($_GET["fecha_fin"]) : "";
        $estado_id = isset($_GET["estado_id"]) ? limpiarCadena($_GET["estado_id"]) : "";
        $vista_previa = isset($_POST["vista_previa"]) ? true : false;
        
        // Si es vista previa, devolver directamente el HTML
        if ($vista_previa && $formato == 'pdf') {
            $html = $asistencia->generarReporte($formato, $aula_id, $seccion_id, $fecha_inicio, $fecha_fin, $estado_id, $vista_previa);
            echo $html;
        } else {
            // Para descargas reales
            $asistencia->generarReporte($formato, $aula_id, $seccion_id, $fecha_inicio, $fecha_fin, $estado_id, $vista_previa);
        }
    }

    public function obtenerEstadisticas() {
        $asistencia = new Asistencia();
        $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : null; // null para estadísticas generales

        $estadisticas = $asistencia->obtenerEstadisticas($fecha);
        echo json_encode($estadisticas);
    }
}
?>