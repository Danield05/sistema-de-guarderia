<?php
require_once "../models/Alertas.php";
require_once "../controllers/AlertasController.php";
if (strlen(session_id())<1)
	session_start();

$alertas = new Alertas();
$alertasController = new AlertasController();

$id_alerta = isset($_POST["idalerta"])? limpiarCadena($_POST["idalerta"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$mensaje = isset($_POST["mensaje"])? limpiarCadena($_POST["mensaje"]):"";
$tipo = isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$estado = isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$alertasController->guardarYEditar();
		break;

	case 'eliminar':
		$alertasController->eliminar();
		break;

	case 'marcarRespondida':
		$alertasController->marcarRespondida();
		break;

	case 'mostrar':
		$alertasController->mostrar();
		break;

    case 'listar':
		$alertasController->listar();
		break;

    case 'listarPorNino':
		$alertasController->listarPorNino();
		break;

    case 'listarPendientes':
		$alertasController->listarPendientes();
		break;

    case 'contarPendientes':
		$alertasController->contarPendientes();
		break;
}
?>