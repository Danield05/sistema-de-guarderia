<?php
require_once "../models/PermisosAusencia.php";
if (strlen(session_id()) < 1)
    session_start();

class PermisosAusenciaController {

    public function guardarYEditar() {
        $permisos_ausencia = new PermisosAusencia();

        $id = isset($_POST["id_permiso"]) ? limpiarCadena($_POST["id_permiso"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $tipo_permiso = isset($_POST["tipo_permiso"]) ? limpiarCadena($_POST["tipo_permiso"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
        $fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
        $fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
        $hora_inicio = isset($_POST["hora_inicio"]) ? limpiarCadena($_POST["hora_inicio"]) : "";
        $hora_fin = isset($_POST["hora_fin"]) ? limpiarCadena($_POST["hora_fin"]) : "";
        // Manejo del archivo
        $archivo_permiso = "";
        if (!file_exists($_FILES['archivo_permiso']['tmp_name']) || !is_uploaded_file($_FILES['archivo_permiso']['tmp_name'])) {
            $archivo_permiso = isset($_POST["archivo_actual"]) && !empty($_POST["archivo_actual"]) ? $_POST["archivo_actual"] : "";
        } else {
            $ext = explode(".", $_FILES["archivo_permiso"]["name"]);
            if ($_FILES['archivo_permiso']['type'] == "application/pdf" ||
                $_FILES['archivo_permiso']['type'] == "image/jpg" ||
                $_FILES['archivo_permiso']['type'] == "image/jpeg" ||
                $_FILES['archivo_permiso']['type'] == "image/png" ||
                $_FILES['archivo_permiso']['type'] == "application/msword" ||
                $_FILES['archivo_permiso']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {

                $archivo_permiso = round(microtime(true)) . '.' . end($ext);
                $target_path = "../files/permisos/" . $archivo_permiso;

                if (move_uploaded_file($_FILES["archivo_permiso"]["tmp_name"], $target_path)) {
                    // Archivo subido correctamente
                } else {
                    $archivo_permiso = isset($_POST["archivo_actual"]) && !empty($_POST["archivo_actual"]) ? $_POST["archivo_actual"] : "";
                }
            } else {
                $archivo_permiso = isset($_POST["archivo_actual"]) && !empty($_POST["archivo_actual"]) ? $_POST["archivo_actual"] : "";
            }
        }

        if (empty($id)) {
            $rspta = $permisos_ausencia->insertar($id_nino, $tipo_permiso, $descripcion, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $archivo_permiso);
            echo $rspta ? "Permiso registrado correctamente" : "No se pudo registrar el permiso";
        } else {
            $rspta = $permisos_ausencia->editar($id, $id_nino, $tipo_permiso, $descripcion, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $archivo_permiso);
            echo $rspta ? "Permiso actualizado correctamente" : "No se pudo actualizar el permiso";
        }
    }

    public function eliminar() {
        $permisos_ausencia = new PermisosAusencia();
        $id = isset($_POST["id_permiso"]) ? limpiarCadena($_POST["id_permiso"]) : "";
        $rspta = $permisos_ausencia->eliminar($id);
        echo $rspta ? "Permiso eliminado correctamente" : "No se pudo eliminar el permiso";
    }

    public function mostrar() {
        $permisos_ausencia = new PermisosAusencia();
        $id = isset($_POST["id_permiso"]) ? limpiarCadena($_POST["id_permiso"]) : "";
        $rspta = $permisos_ausencia->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $permisos_ausencia = new PermisosAusencia();
        $rspta = $permisos_ausencia->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_permiso . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_permiso . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nino,
                "2" => $reg->tipo_permiso,
                "3" => $reg->descripcion,
                "4" => $reg->fecha_inicio,
                "5" => $reg->fecha_fin,
                "6" => $reg->hora_inicio,
                "7" => $reg->hora_fin,
                "8" => $reg->archivo_permiso,
                "9" => $reg->id_permiso
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

    public function listarPorNino() {
        $permisos_ausencia = new PermisosAusencia();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $permisos_ausencia->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_permiso,
                "1" => $reg->tipo_permiso,
                "2" => $reg->descripcion,
                "3" => $reg->fecha_inicio,
                "4" => $reg->fecha_fin,
                "5" => $reg->hora_inicio,
                "6" => $reg->hora_fin,
                "7" => $reg->archivo_permiso
            );
        }
        echo json_encode($data);
    }

    public function listarPorFechas() {
        $permisos_ausencia = new PermisosAusencia();
        $fecha_inicio = isset($_POST["fecha_inicio"]) ? limpiarCadena($_POST["fecha_inicio"]) : "";
        $fecha_fin = isset($_POST["fecha_fin"]) ? limpiarCadena($_POST["fecha_fin"]) : "";
        $rspta = $permisos_ausencia->listarPorFechas($fecha_inicio, $fecha_fin);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_permiso,
                "1" => $reg->nino,
                "2" => $reg->tipo_permiso,
                "3" => $reg->descripcion,
                "4" => $reg->fecha_inicio,
                "5" => $reg->fecha_fin,
                "6" => $reg->hora_inicio,
                "7" => $reg->hora_fin,
                "8" => $reg->archivo_permiso
            );
        }
        echo json_encode($data);
    }
}
?>