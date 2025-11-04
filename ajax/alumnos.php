<?php
require_once "../models/Alumnos.php";
require_once "../controllers/AlumnosController.php";
if (strlen(session_id())<1)
	session_start();

$alumnos=new Alumnos();
$alumnosController = new AlumnosController();

$id=isset($_POST["idalumno"])? limpiarCadena($_POST["idalumno"]):"";
$image=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$name=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$lastname=isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$address=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$phone=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$c1_fullname=isset($_POST["c1_nombres"])? limpiarCadena($_POST["c1_nombres"]):"";
$c1_address=isset($_POST["c1_direccion"])? limpiarCadena($_POST["c1_direccion"]):"";
$c1_phone=isset($_POST["c1_telefono"])? limpiarCadena($_POST["c1_telefono"]):"";
$c1_note=isset($_POST["c1_nota"])? limpiarCadena($_POST["c1_nota"]):"";
$team_id=isset($_POST["idgrupo"])? limpiarCadena($_POST["idgrupo"]):"";
$user_id=$_SESSION["idusuario"];

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$alumnosController->guardarYEditar();
		break;

	case 'desactivar':
		$alumnosController->desactivar();
		break;

	case 'activar':
		$alumnosController->activar();
		break;

	case 'mostrar':
		$alumnosController->mostrar();
		break;

    case 'listar':
		$alumnosController->listar();
		break;
}
?>