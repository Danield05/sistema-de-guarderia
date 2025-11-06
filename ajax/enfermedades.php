<?php
require_once "../models/Enfermedades.php";
require_once "../controllers/EnfermedadesController.php";
if (strlen(session_id())<1)
	session_start();

$enfermedades = new Enfermedades();
$enfermedadesController = new EnfermedadesController();

$id_enfermedad = isset($_POST["idenfermedad"])? limpiarCadena($_POST["idenfermedad"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$nombre_enfermedad = isset($_POST["nombre_enfermedad"])? limpiarCadena($_POST["nombre_enfermedad"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$fecha_diagnostico = isset($_POST["fecha_diagnostico"])? limpiarCadena($_POST["fecha_diagnostico"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$enfermedadesController->guardarYEditar();
		break;

	case 'eliminar':
		$enfermedadesController->eliminar();
		break;

	case 'mostrar':
		$enfermedadesController->mostrar();
		break;

    case 'listar':
		$enfermedadesController->listar();
		break;

    case 'listarPorNino':
		$enfermedadesController->listarPorNino();
		break;
}
?>