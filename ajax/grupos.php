<?php
require_once "../models/Grupos.php";
require_once "../controllers/GruposController.php";
if (strlen(session_id())<1)
	session_start();

$grupos = new Grupos();
$gruposController = new GruposController();

$idgrupo=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
$idusuario=$_SESSION["idusuario"];
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$favorito=isset($_POST["favorito"])? 1 :0;


switch ($_GET["op"]) {
	case 'guardaryeditar':
		$gruposController->guardarYEditar();
		break;

	case 'anular':
		$gruposController->anular();
		break;

	case 'mostrar':
		$gruposController->mostrar();
		break;

    case 'listar':
		$gruposController->listar();
		break;
}
?>