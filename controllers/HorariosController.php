<?php
require_once "../models/Horarios.php";
if (strlen(session_id()) < 1)
    session_start();

class HorariosController {

    public function guardarYEditar() {
        $horarios = new Horarios();

        $id = isset($_POST["idhorario"]) ? limpiarCadena($_POST["idhorario"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $dia_semana = isset($_POST["dia_semana"]) ? limpiarCadena($_POST["dia_semana"]) : "";
        $hora_entrada = isset($_POST["hora_entrada"]) ? limpiarCadena($_POST["hora_entrada"]) : "";
        $hora_salida = isset($_POST["hora_salida"]) ? limpiarCadena($_POST["hora_salida"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $horarios->insertar($id_nino, $dia_semana, $hora_entrada, $hora_salida, $descripcion);
            echo $rspta ? "Horario registrado correctamente" : "No se pudo registrar el horario";
        } else {
            $rspta = $horarios->editar($id, $id_nino, $dia_semana, $hora_entrada, $hora_salida, $descripcion);
            echo $rspta ? "Horario actualizado correctamente" : "No se pudo actualizar el horario";
        }
    }

    public function desactivar() {
        $horarios = new Horarios();
        $id = isset($_POST["idhorario"]) ? limpiarCadena($_POST["idhorario"]) : "";
        $rspta = $horarios->desactivar($id);
        echo $rspta ? "Horario eliminado correctamente" : "No se pudo eliminar el horario";
    }

    public function activar() {
        $horarios = new Horarios();
        $id = isset($_POST["idhorario"]) ? limpiarCadena($_POST["idhorario"]) : "";
        $rspta = $horarios->activar($id);
        echo $rspta ? "Horario activado correctamente" : "No se pudo activar el horario";
    }

    public function mostrar() {
        $horarios = new Horarios();
        $id = isset($_POST["idhorario"]) ? limpiarCadena($_POST["idhorario"]) : "";
        $rspta = $horarios->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $horarios = new Horarios();

        // Verificar si es tutor para mostrar solo sus hijos
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Tutor') {
            $rspta = $horarios->listarPorTutor($_SESSION['id_usuario']);
        } else {
            // Obtener filtros
            $nombre_nino = isset($_GET["nombre_nino"]) ? limpiarCadena($_GET["nombre_nino"]) : "";
            $aula = isset($_GET["aula"]) ? limpiarCadena($_GET["aula"]) : "";
            $seccion = isset($_GET["seccion"]) ? limpiarCadena($_GET["seccion"]) : "";

            $rspta = $horarios->listarConFiltros($nombre_nino, $aula, $seccion);
        }

        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            // Solo mostrar botones de acción si no es tutor
            $acciones = '';
            if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 'Tutor') {
                $acciones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_horario . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id_horario . ')"><i class="fa fa-trash"></i></button>';
            }

            $data[] = array(
                "acciones" => $acciones,
                "nombre_completo" => $reg->nombre_completo,
                "dia_semana" => $reg->dia_semana,
                "hora_entrada" => $reg->hora_entrada,
                "hora_salida" => $reg->hora_salida,
                "descripcion" => $reg->descripcion
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
        $horarios = new Horarios();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $horarios->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_horario,
                "1" => $reg->dia_semana,
                "2" => $reg->hora_entrada,
                "3" => $reg->hora_salida,
                "4" => $reg->descripcion,
                "5" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>'
            );
        }
        echo json_encode($data);
    }

    public function selectNinos() {
        $horarios = new Horarios();
        $rspta = $horarios->listarNinos();
        $html = '<option value="">Seleccionar niño</option>';

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $html .= '<option value="' . $reg->id_nino . '" data-aula="' . $reg->nombre_aula . '" data-seccion="' . $reg->nombre_seccion . '">' . $reg->nombre_completo . '</option>';
        }
        echo $html;
    }

    public function selectAulas() {
        $horarios = new Horarios();
        $rspta = $horarios->listarAulas();
        $html = '<option value="">Todas las aulas</option>';

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $html .= '<option value="' . $reg->nombre_aula . '">' . $reg->nombre_aula . '</option>';
        }
        echo $html;
    }

    public function selectSecciones() {
        $horarios = new Horarios();
        $rspta = $horarios->listarSecciones();
        $html = '<option value="">Todas las secciones</option>';

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $html .= '<option value="' . $reg->nombre_seccion . '">' . $reg->nombre_seccion . '</option>';
        }
        echo $html;
    }
}
?>