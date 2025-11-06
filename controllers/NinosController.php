<?php
require_once "../models/Ninos.php";
if (strlen(session_id()) < 1)
    session_start();

class NinosController {

    public function guardarYEditar() {
        $ninos = new Ninos();

        $id = isset($_POST["idnino"]) ? limpiarCadena($_POST["idnino"]) : "";
        $nombre_completo = isset($_POST["nombre_completo"]) ? limpiarCadena($_POST["nombre_completo"]) : "";
        $fecha_nacimiento = isset($_POST["fecha_nacimiento"]) ? limpiarCadena($_POST["fecha_nacimiento"]) : "";
        $edad = isset($_POST["edad"]) ? limpiarCadena($_POST["edad"]) : "";
        $peso = isset($_POST["peso"]) ? limpiarCadena($_POST["peso"]) : "";
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";
        $seccion_id = isset($_POST["seccion_id"]) ? limpiarCadena($_POST["seccion_id"]) : "";
        $maestro_id = isset($_POST["maestro_id"]) ? limpiarCadena($_POST["maestro_id"]) : "";
        $tutor_id = isset($_POST["tutor_id"]) ? limpiarCadena($_POST["tutor_id"]) : "";

        if (empty($id)) {
            $rspta = $ninos->insertar($nombre_completo, $fecha_nacimiento, $edad, $peso, $aula_id, $seccion_id, $maestro_id, $tutor_id);
            echo $rspta ? "Niño registrado correctamente" : "No se pudo registrar el niño";
        } else {
            $rspta = $ninos->editar($id, $nombre_completo, $fecha_nacimiento, $edad, $peso, $aula_id, $seccion_id, $maestro_id, $tutor_id);
            echo $rspta ? "Niño actualizado correctamente" : "No se pudo actualizar el niño";
        }
    }

    public function desactivar() {
        $ninos = new Ninos();
        $id = isset($_POST["idnino"]) ? limpiarCadena($_POST["idnino"]) : "";
        $rspta = $ninos->desactivar($id);
        echo $rspta ? "Niño desactivado correctamente" : "No se pudo desactivar el niño";
    }

    public function activar() {
        $ninos = new Ninos();
        $id = isset($_POST["idnino"]) ? limpiarCadena($_POST["idnino"]) : "";
        $rspta = $ninos->activar($id);
        echo $rspta ? "Niño activado correctamente" : "No se pudo activar el niño";
    }

    public function mostrar() {
        $ninos = new Ninos();
        $id = isset($_POST["idnino"]) ? limpiarCadena($_POST["idnino"]) : "";
        $rspta = $ninos->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $ninos = new Ninos();
        $rspta = $ninos->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "acciones" => ($reg->estado) ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_nino . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id_nino . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_nino . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-primary btn-xs" onclick="activar(' . $reg->id_nino . ')"><i class="fa fa-check"></i></button>',
                "nombre_completo" => $reg->nombre_completo,
                "edad" => $reg->edad,
                "fecha_nacimiento" => $reg->fecha_nacimiento,
                "peso" => $reg->peso,
                "nombre_aula" => $reg->nombre_aula,
                "nombre_seccion" => $reg->nombre_seccion,
                "maestro" => $reg->maestro,
                "tutor" => $reg->tutor
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

    public function listarPorAula() {
        $ninos = new Ninos();
        $aula_id = isset($_POST["aula_id"]) ? limpiarCadena($_POST["aula_id"]) : "";
        $rspta = $ninos->listarPorAula($aula_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_nino,
                "1" => $reg->nombre_completo,
                "2" => $reg->edad,
                "3" => $reg->nombre_seccion
            );
        }
        echo json_encode($data);
    }

    public function listarPorSeccion() {
        $ninos = new Ninos();
        $seccion_id = isset($_POST["seccion_id"]) ? limpiarCadena($_POST["seccion_id"]) : "";
        $rspta = $ninos->listarPorSeccion($seccion_id);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_nino,
                "1" => $reg->nombre_completo,
                "2" => $reg->edad
            );
        }
        echo json_encode($data);
    }
}
?>