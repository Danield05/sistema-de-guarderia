<?php
require_once "../models/Medicamentos.php";
require_once "../controllers/MedicamentosController.php";
if (strlen(session_id())<1)
	session_start();

$medicamentos = new Medicamentos();
$medicamentosController = new MedicamentosController();

$id_medicamento = isset($_POST["id_medicamento"])? limpiarCadena($_POST["id_medicamento"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$nombre_medicamento = isset($_POST["nombre_medicamento"])? limpiarCadena($_POST["nombre_medicamento"]):"";
$dosis = isset($_POST["dosis"])? limpiarCadena($_POST["dosis"]):"";
$frecuencia = isset($_POST["frecuencia"])? limpiarCadena($_POST["frecuencia"]):"";
$observaciones = isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$medicamentosController->guardarYEditar();
		break;

	case 'eliminar':
		$medicamentosController->eliminar();
		break;

	case 'mostrar':
		$medicamentosController->mostrar();
		break;

    case 'listar':
		$medicamentosController->listar();
		break;

    case 'listarPorNino':
		$medicamentosController->listarPorNino();
		break;
}
?>