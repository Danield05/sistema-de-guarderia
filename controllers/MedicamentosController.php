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

        // Verificar permisos
        $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
        $esPadreTutor = ($cargo == 'Padre/Tutor');

        if (empty($id)) {
            // Agregar nuevo medicamento - padres/tutores pueden hacerlo
            $rspta = $medicamentos->insertar($id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones);
            echo $rspta ? "Medicamento registrado correctamente" : "No se pudo registrar el medicamento";
        } else {
            // Editar medicamento existente
            if ($esPadreTutor) {
                // Verificar si el medicamento pertenece a un niño del tutor
                $medicamento = $medicamentos->mostrar($id);
                if ($medicamento && $this->perteneceAlTutor($medicamento['id_nino'], $_SESSION['idusuario'])) {
                    $rspta = $medicamentos->editar($id, $id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones);
                    echo $rspta ? "Medicamento actualizado correctamente" : "No se pudo actualizar el medicamento";
                } else {
                    echo "No tienes permisos para editar este medicamento";
                }
            } else {
                $rspta = $medicamentos->editar($id, $id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones);
                echo $rspta ? "Medicamento actualizado correctamente" : "No se pudo actualizar el medicamento";
            }
        }
    }

    public function eliminar() {
        $medicamentos = new Medicamentos();
        $id = isset($_POST["id_medicamento"]) ? limpiarCadena($_POST["id_medicamento"]) : "";

        // Verificar permisos
        $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
        $esPadreTutor = ($cargo == 'Padre/Tutor');

        if ($esPadreTutor) {
            // Verificar si el medicamento pertenece a un niño del tutor
            $medicamento = $medicamentos->mostrar($id);
            if ($medicamento && $this->perteneceAlTutor($medicamento['id_nino'], $_SESSION['idusuario'])) {
                $rspta = $medicamentos->eliminar($id);
                echo $rspta ? "Medicamento eliminado correctamente" : "No se pudo eliminar el medicamento";
            } else {
                echo "No tienes permisos para eliminar este medicamento";
            }
        } else {
            $rspta = $medicamentos->eliminar($id);
            echo $rspta ? "Medicamento eliminado correctamente" : "No se pudo eliminar el medicamento";
        }
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
            // Para médicos y administradores pueden editar/eliminar, maestros y padres/tutores solo ver
            $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
            if ($cargo == 'Médico/Enfermería' || $cargo == 'Administrador') {
                $acciones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_medicamento . ')"><i class="fa fa-trash"></i></button>';
            } elseif ($cargo == 'Maestro' || $cargo == 'Padre/Tutor') {
                $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-eye"></i></button>';
            } else {
                $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-eye"></i></button>';
            }

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->nombre_medicamento,
                "3" => $reg->dosis,
                "4" => $reg->frecuencia,
                "5" => $reg->observaciones
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
            // Para padres/tutores, mostrar solo botón de ver
            $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_medicamento . ')"><i class="fa fa-eye"></i></button>';

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->nombre_medicamento,
                "3" => $reg->dosis,
                "4" => $reg->frecuencia,
                "5" => $reg->observaciones
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

    // Método auxiliar para verificar si un niño pertenece a un tutor
    private function perteneceAlTutor($id_nino, $id_usuario) {
        require_once "../models/Ninos.php";
        $ninos = new Ninos();
        $rspta = $ninos->listarParaPadre($id_usuario);

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            if ($reg->id_nino == $id_nino) {
                return true;
            }
        }
        return false;
    }
}
?>