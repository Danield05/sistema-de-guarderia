<?php
require_once "../models/PermisosAusencia.php";
require_once "../controllers/PermisosAusenciaController.php";
if (strlen(session_id())<1)
	session_start();

$permisos_ausencia = new PermisosAusencia();
$permisos_ausenciaController = new PermisosAusenciaController();

$id_permiso = isset($_POST["idpermiso"])? limpiarCadena($_POST["idpermiso"]):"";
$id_nino = isset($_POST["id_nino"])? limpiarCadena($_POST["id_nino"]):"";
$tipo_permiso = isset($_POST["tipo_permiso"])? limpiarCadena($_POST["tipo_permiso"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$fecha_inicio = isset($_POST["fecha_inicio"])? limpiarCadena($_POST["fecha_inicio"]):"";
$fecha_fin = isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";
$hora_inicio = isset($_POST["hora_inicio"])? limpiarCadena($_POST["hora_inicio"]):"";
$hora_fin = isset($_POST["hora_fin"])? limpiarCadena($_POST["hora_fin"]):"";
$archivo_permiso = isset($_POST["archivo_permiso"])? limpiarCadena($_POST["archivo_permiso"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$permisos_ausenciaController->guardarYEditar();
		break;

	case 'eliminar':
		$permisos_ausenciaController->eliminar();
		break;

	case 'mostrar':
		$permisos_ausenciaController->mostrar();
		break;

    case 'listar':
		$permisos_ausenciaController->listar();
		break;

    case 'listarPorNino':
		$permisos_ausenciaController->listarPorNino();
		break;

    case 'listarPorFechas':
  $permisos_ausenciaController->listarPorFechas();
  break;

    case 'listarPorTutor':
  $permisos_ausenciaController->listarPorTutor();
  break;
}
?>