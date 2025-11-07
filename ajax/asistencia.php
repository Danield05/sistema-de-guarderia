<?php
require_once "../models/Asistencia.php";
require_once "../controllers/AsistenciaController.php";
if (strlen(session_id())<1)
	session_start();

$asistencia = new Asistencia();
$asistenciaController = new AsistenciaController();

$id_asistencia = isset($_POST["idasistencia"])? limpiarCadena($_POST["idasistencia"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$fecha = isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$estado_id = isset($_POST["estado_id"])? limpiarCadena($_POST["estado_id"]):"";
$observaciones = isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";
$aula_id = isset($_POST["aula_id"])? limpiarCadena($_POST["aula_id"]):"";
$seccion_id = isset($_POST["seccion_id"])? limpiarCadena($_POST["seccion_id"]):"";
$fecha_inicio = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$asistenciaController->guardarYEditar();
		break;

	case 'eliminar':
		$asistenciaController->eliminar();
		break;

	case 'mostrar':
		$asistenciaController->mostrar();
		break;

    case 'listar':
		$asistenciaController->listar();
		break;

    case 'listarConFiltros':
		$asistenciaController->listarConFiltros();
		break;

    case 'obtenerNinos':
		$asistenciaController->obtenerNinos();
		break;

    case 'listarPorNino':
		$asistenciaController->listarPorNino();
		break;

    case 'listarPorFecha':
		$asistenciaController->listarPorFecha();
		break;

    case 'verificarAsistencia':
		$asistenciaController->verificarAsistencia();
		break;

    case 'generarReporte':
  $asistenciaController->generarReporte();
  break;

    case 'obtenerEstadisticas':
  $asistenciaController->obtenerEstadisticas();
  break;
}
?>