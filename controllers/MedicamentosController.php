<?php
require_once "../models/Medicamentos.php";
if (strlen(session_id()) < 1)
    session_start();

class MedicamentosController {

    public function guardarYEditar() {
        $medicamentos = new Medicamentos();

        $id = isset($_POST["id_medicamento"]) ? limpiarCadena($_POST["id_medicamento"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $nombre_medicamento = isset($_POST["nombre_medicamento"]) ? limpiarCadena($_POST["nombre_medicamento"]) : "";
        $dosis = isset($_POST["dosis"]) ? limpiarCadena($_POST["dosis"]) : "";
        $frecuencia = isset($_POST["frecuencia"]) ? limpiarCadena($_POST["frecuencia"]) : "";
        $observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";

        if (empty($id)) {
            $rspta = $medicamentos->insertar($id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones);
            echo $rspta ? "Medicamento registrado correctamente" : "No se pudo registrar el medicamento";
        } else {
            $rspta = $medicamentos->editar($id, $id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones);
            echo $rspta ? "Medicamento actualizado correctamente" : "No se pudo actualizar el medicamento";
        }
    }

    public function eliminar() {
        $medicamentos = new Medicamentos();
        $id = isset($_POST["id_medicamento"]) ? limpiarCadena($_POST["id_medicamento"]) : "";
        $rspta = $medicamentos->eliminar($id);
        echo $rspta ? "Medicamento eliminado correctamente" : "No se pudo eliminar el medicamento";
    }

    public function mostrar() {
        $medicamentos = new Medicamentos();
        $id = isset($_POST["id_medicamento"]) ? limpiarCadena($_POST["id_medicamento"]) : "";
        $rspta = $medicamentos->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $medicamentos = new Medicamentos();

        // Si es maestro, mostrar solo medicamentos de sus niños asignados
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
            $rspta = $medicamentos->listarParaMaestro($_SESSION['idusuario']);
        } else {
            $rspta = $medicamentos->listar();
        }

        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            // Para maestros, solo mostrar botón de ver
            if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
                $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-eye"></i></button>';
            } else {
                $acciones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_medicamento . ')"><i class="fa fa-trash"></i></button>';
            }

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->nombre_medicamento,
                "3" => $reg->dosis,
                "4" => $reg->frecuencia,
                "5" => $reg->observaciones,
                "6" => $reg->id_medicamento
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
        $medicamentos = new Medicamentos();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $medicamentos->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_medicamento,
                "1" => $reg->nombre_medicamento,
                "2" => $reg->dosis,
                "3" => $reg->frecuencia,
                "4" => $reg->observaciones
            );
        }
        echo json_encode($data);
    }

    public function listarPorTutor() {
        $medicamentos = new Medicamentos();

        if (!isset($_SESSION['idusuario'])) {
            echo json_encode(array("sEcho" => 1, "iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "aaData" => array()));
            return;
        }

        $user_id = $_SESSION['idusuario'];
        $rspta = $medicamentos->listarPorTutor($user_id);

        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            // Para padres/tutores, solo mostrar botón de ver
            $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-eye"></i></button>';

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->nombre_medicamento,
                "3" => $reg->dosis,
                "4" => $reg->frecuencia,
                "5" => $reg->observaciones,
                "6" => $reg->id_medicamento
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
}
?>