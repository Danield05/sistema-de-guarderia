<?php
require_once "../models/EstadosUsuario.php";
if (strlen(session_id()) < 1)
    session_start();

class EstadosUsuarioController {

    public function guardarYEditar() {
        $estadosUsuario = new EstadosUsuario();

        $id = isset($_POST["idestado_usuario"]) ? limpiarCadena($_POST["idestado_usuario"]) : "";
        $nombre_estado = isset($_POST["nombre_estado"]) ? limpiarCadena($_POST["nombre_estado"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $estadosUsuario->insertar($nombre_estado, $descripcion);
            echo $rspta ? "Estado registrado correctamente" : "No se pudo registrar el estado";
        } else {
            $rspta = $estadosUsuario->editar($id, $nombre_estado, $descripcion);
            echo $rspta ? "Estado actualizado correctamente" : "No se pudo actualizar el estado";
        }
    }

    public function eliminar() {
        $estadosUsuario = new EstadosUsuario();
        $id = isset($_POST["idestado_usuario"]) ? limpiarCadena($_POST["idestado_usuario"]) : "";
        $rspta = $estadosUsuario->eliminar($id);
        echo $rspta ? "Estado eliminado correctamente" : "No se pudo eliminar el estado";
    }

    public function mostrar() {
        $estadosUsuario = new EstadosUsuario();
        $id = isset($_POST["idestado_usuario"]) ? limpiarCadena($_POST["idestado_usuario"]) : "";
        $rspta = $estadosUsuario->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $estadosUsuario = new EstadosUsuario();
        $rspta = $estadosUsuario->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_estado_usuario . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_estado_usuario . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nombre_estado,
                "2" => $reg->descripcion
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    }

    public function listarParaSelect() {
        $estadosUsuario = new EstadosUsuario();
        $rspta = $estadosUsuario->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_estado_usuario,
                "1" => $reg->nombre_estado
            );
        }
        echo json_encode($data);
    }
}
?>