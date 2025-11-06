<?php
require_once "../models/Aulas.php";
require_once "../controllers/AulasController.php";
if (strlen(session_id())<1)
	session_start();

$aulas = new Aulas();
$aulasController = new AulasController();

$id_aula = isset($_POST["idaula"])? limpiarCadena($_POST["idaula"]):"";
$nombre_aula = isset($_POST["nombre_aula"])? limpiarCadena($_POST["nombre_aula"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$aulasController->guardarYEditar();
		break;

	case 'desactivar':
		$aulasController->desactivar();
		break;

	case 'activar':
		$aulasController->activar();
		break;

	case 'mostrar':
		$aulasController->mostrar();
		break;

    case 'listar':
		$aulasController->listar();
		break;
}
?>