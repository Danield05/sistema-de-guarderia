<?php
require_once "../models/Alergias.php";
require_once "../controllers/AlergiasController.php";
if (strlen(session_id())<1)
	session_start();

$alergias = new Alergias();
$alergiasController = new AlergiasController();

$id_alergia = isset($_POST["idalergia"])? limpiarCadena($_POST["idalergia"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$tipo_alergia = isset($_POST["tipo_alergia"])? limpiarCadena($_POST["tipo_alergia"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$alergiasController->guardarYEditar();
		break;

	case 'eliminar':
		$alergiasController->eliminar();
		break;

	case 'mostrar':
		$alergiasController->mostrar();
		break;

    case 'listar':
		$alergiasController->listar();
		break;

    case 'listarPorNino':
		$alergiasController->listarPorNino();
		break;
}
?>