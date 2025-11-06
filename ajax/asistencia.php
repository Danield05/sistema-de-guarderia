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

    case 'listarPorNino':
		$asistenciaController->listarPorNino();
		break;

    case 'listarPorFecha':
		$asistenciaController->listarPorFecha();
		break;

    case 'verificarAsistencia':
		$asistenciaController->verificarAsistencia();
		break;
}
?>