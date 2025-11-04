<?php
session_start();
require_once "../models/Permiso.php";

$permiso = new Permiso();

$idpermiso = isset($_POST["idpermiso"]) ? limpiarCadena($_POST["idpermiso"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idpermiso)) {
            $rspta = $permiso->insertar($nombre);
            echo $rspta ? "Permiso registrado correctamente" : "No se pudo registrar el permiso";
        } else {
            $rspta = $permiso->editar($idpermiso, $nombre);
            echo $rspta ? "Permiso actualizado correctamente" : "No se pudo actualizar el permiso";
        }
        break;

    case 'desactivar':
        $rspta = $permiso->desactivar($idpermiso);
        echo $rspta ? "Permiso desactivado correctamente" : "No se pudo desactivar el permiso";
        break;

    case 'activar':
        $rspta = $permiso->activar($idpermiso);
        echo $rspta ? "Permiso activado correctamente" : "No se pudo activar el permiso";
        break;

    case 'mostrar':
        $rspta = $permiso->mostrar($idpermiso);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $permiso->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => ($reg->condicion) ?
                    '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idpermiso . ')"><i class="fa fa-pencil"></i></button>' . ' ' .
                    '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idpermiso . ')"><i class="fa fa-close"></i></button>' :
                    '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idpermiso . ')"><i class="fa fa-pencil"></i></button>' . ' ' .
                    '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->idpermiso . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
?>