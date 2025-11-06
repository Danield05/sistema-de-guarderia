<?php
require_once "../models/EstadosUsuario.php";
require_once "../controllers/EstadosUsuarioController.php";
if (strlen(session_id())<1)
	session_start();

$estadosUsuario = new EstadosUsuario();
$estadosUsuarioController = new EstadosUsuarioController();

$id_estado_usuario = isset($_POST["idestado_usuario"])? limpiarCadena($_POST["idestado_usuario"]):"";
$nombre_estado = isset($_POST["nombre_estado"])? limpiarCadena($_POST["nombre_estado"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$estadosUsuarioController->guardarYEditar();
		break;

	case 'eliminar':
		$estadosUsuarioController->eliminar();
		break;

	case 'mostrar':
		$estadosUsuarioController->mostrar();
		break;

    case 'listar':
		$estadosUsuarioController->listar();
		break;

    case 'listarParaSelect':
		$estadosUsuarioController->listarParaSelect();
		break;
}
?>