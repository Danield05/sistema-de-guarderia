<?php
require_once "../models/Ninos.php";
require_once "../controllers/NinosController.php";
if (strlen(session_id())<1)
	session_start();

$ninos = new Ninos();
$ninosController = new NinosController();

$id_nino = isset($_POST["idnino"])? limpiarCadena($_POST["idnino"]):"";
$nombre_completo = isset($_POST["nombre_completo"])? limpiarCadena($_POST["nombre_completo"]):"";
$fecha_nacimiento = isset($_POST["fecha_nacimiento"])? limpiarCadena($_POST["fecha_nacimiento"]):"";
$edad = isset($_POST["edad"])? limpiarCadena($_POST["edad"]):"";
$peso = isset($_POST["peso"])? limpiarCadena($_POST["peso"]):"";
$aula_id = isset($_POST["aula_id"])? limpiarCadena($_POST["aula_id"]):"";
$seccion_id = isset($_POST["seccion_id"])? limpiarCadena($_POST["seccion_id"]):"";
$maestro_id = isset($_POST["maestro_id"])? limpiarCadena($_POST["maestro_id"]):"";
$tutor_id = isset($_POST["tutor_id"])? limpiarCadena($_POST["tutor_id"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$ninosController->guardarYEditar();
		break;

	case 'desactivar':
		$ninosController->desactivar();
		break;

	case 'activar':
		$ninosController->activar();
		break;

	case 'mostrar':
		$ninosController->mostrar();
		break;

    case 'listar':
		$ninosController->listar();
		break;

    case 'listarPorAula':
		$ninosController->listarPorAula();
		break;

    case 'listarPorSeccion':
  $ninosController->listarPorSeccion();
  break;

    case 'select':
  $ninosController->select();
  break;
}
?>