<?php
require_once "../models/Horarios.php";
require_once "../controllers/HorariosController.php";
if (strlen(session_id())<1)
	session_start();

$horarios = new Horarios();
$horariosController = new HorariosController();

$id_horario = isset($_POST["idhorario"])? limpiarCadena($_POST["idhorario"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$dia_semana = isset($_POST["dia_semana"])? limpiarCadena($_POST["dia_semana"]):"";
$hora_entrada = isset($_POST["hora_entrada"])? limpiarCadena($_POST["hora_entrada"]):"";
$hora_salida = isset($_POST["hora_salida"])? limpiarCadena($_POST["hora_salida"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$horariosController->guardarYEditar();
		break;

	case 'desactivar':
		$horariosController->desactivar();
		break;

	case 'activar':
		$horariosController->activar();
		break;

	case 'mostrar':
		$horariosController->mostrar();
		break;

    case 'listar':
		$horariosController->listar();
		break;

    case 'listarPorNino':
		$horariosController->listarPorNino();
		break;

    case 'selectNinos':
  $horariosController->selectNinos();
  break;

    case 'selectAulas':
  $horariosController->selectAulas();
  break;

    case 'selectSecciones':
  $horariosController->selectSecciones();
  break;
}
?>