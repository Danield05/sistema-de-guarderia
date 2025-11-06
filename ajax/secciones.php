<?php
require_once "../models/Secciones.php";
require_once "../controllers/SeccionesController.php";
if (strlen(session_id())<1)
	session_start();

$secciones = new Secciones();
$seccionesController = new SeccionesController();

$id_seccion = isset($_POST["idseccion"])? limpiarCadena($_POST["idseccion"]):"";
$nombre_seccion = isset($_POST["nombre_seccion"])? limpiarCadena($_POST["nombre_seccion"]):"";
$aula_id = isset($_POST["aula_id"])? limpiarCadena($_POST["aula_id"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$seccionesController->guardarYEditar();
		break;

	case 'desactivar':
		$seccionesController->desactivar();
		break;

	case 'activar':
		$seccionesController->activar();
		break;

	case 'mostrar':
		$seccionesController->mostrar();
		break;

    case 'listar':
		$seccionesController->listar();
		break;

    case 'listarPorAula':
		$seccionesController->listarPorAula();
		break;
}
?>