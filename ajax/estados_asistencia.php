<?php
require_once "../models/EstadosAsistencia.php";
require_once "../controllers/EstadosAsistenciaController.php";
if (strlen(session_id())<1)
	session_start();

$estados_asistencia = new EstadosAsistencia();
$estados_asistenciaController = new EstadosAsistenciaController();

$id_estado = isset($_POST["idestado"])? limpiarCadena($_POST["idestado"]):"";
$nombre_estado = isset($_POST["nombre_estado"])? limpiarCadena($_POST["nombre_estado"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$estados_asistenciaController->guardarYEditar();
		break;

	case 'eliminar':
		$estados_asistenciaController->eliminar();
		break;

	case 'mostrar':
		$estados_asistenciaController->mostrar();
		break;

    case 'listar':
		$estados_asistenciaController->listar();
		break;

    case 'listarActivos':
		$estados_asistenciaController->listarActivos();
		break;
}
?>