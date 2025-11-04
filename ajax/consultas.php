<?php
require_once "../models/Consultas.php";
require_once "../controllers/ConsultasController.php";
if (strlen(session_id())<1)
    session_start();

$consulta = new Consultas();
$consultasController = new ConsultasController();

$user_id=$_SESSION["idusuario"];


switch ($_GET["op"]) {
    case 'lista_asistencia':
        $consultasController->listaAsistencia();
        break;

    case 'lista_comportamiento':
        $consultasController->listaComportamiento();
        break;

    case 'listar_calificacion':
        $consultasController->listarCalificacion();
        break;
}
?>