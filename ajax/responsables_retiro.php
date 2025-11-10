<?php
require_once "../models/ResponsablesRetiro.php";
require_once "../controllers/ResponsablesRetiroController.php";
if (strlen(session_id())<1)
	session_start();

$responsables_retiro = new ResponsablesRetiro();
$responsables_retiroController = new ResponsablesRetiroController();

$id_responsable = isset($_POST["id_responsable"])? limpiarCadena($_POST["id_responsable"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$nombre_completo = isset($_POST["nombre_completo"])? limpiarCadena($_POST["nombre_completo"]):"";
$parentesco = isset($_POST["parentesco"])? limpiarCadena($_POST["parentesco"]):"";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$autorizacion_firma = isset($_POST["autorizacion_firma"])? limpiarCadena($_POST["autorizacion_firma"]):"";
$periodo_inicio = isset($_POST["periodo_inicio"])? limpiarCadena($_POST["periodo_inicio"]):"";
$periodo_fin = isset($_POST["periodo_fin"])? limpiarCadena($_POST["periodo_fin"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$responsables_retiroController->guardarYEditar();
		break;

	case 'eliminar':
		$responsables_retiroController->eliminar();
		break;

	case 'mostrar':
		$responsables_retiroController->mostrar();
		break;

    case 'listar':
		$responsables_retiroController->listar();
		break;

    case 'listarPorNino':
  $responsables_retiroController->listarPorNino();
  break;

    case 'iniciarFirmaDocumento':
  $responsables_retiroController->iniciarFirmaDocumento();
  break;

    case 'guardarDocumentoPDF':
  $responsables_retiroController->guardarDocumentoPDF();
  break;

    case 'guardarFirmaDocumento':
  $responsables_retiroController->guardarFirmaDocumento();
  break;

    case 'obtenerPreviewPDF':
  $responsables_retiroController->obtenerPreviewPDF();
  break;
}
?>