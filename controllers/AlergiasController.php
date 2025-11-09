<?php
require_once "../models/Alergias.php";
if (strlen(session_id()) < 1)
    session_start();

class AlergiasController {

    public function guardarYEditar() {
        $alergias = new Alergias();

        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $tipo_alergia = isset($_POST["tipo_alergia"]) ? limpiarCadena($_POST["tipo_alergia"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        // Verificar permisos
        $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
        $esPadreTutor = ($cargo == 'Padre/Tutor');

        if (empty($id)) {
            // Agregar nueva alergia - padres/tutores pueden hacerlo
            $rspta = $alergias->insertar($id_nino, $tipo_alergia, $descripcion);
            echo $rspta ? "Alergia registrada correctamente" : "No se pudo registrar la alergia";
        } else {
            // Editar alergia existente
            if ($esPadreTutor) {
                // Verificar si la alergia pertenece a un niño del tutor
                $alergia = $alergias->mostrar($id);
                if ($alergia && $this->perteneceAlTutor($alergia['id_nino'], $_SESSION['idusuario'])) {
                    $rspta = $alergias->editar($id, $id_nino, $tipo_alergia, $descripcion);
                    echo $rspta ? "Alergia actualizada correctamente" : "No se pudo actualizar la alergia";
                } else {
                    echo "No tienes permisos para editar esta alergia";
                }
            } else {
                $rspta = $alergias->editar($id, $id_nino, $tipo_alergia, $descripcion);
                echo $rspta ? "Alergia actualizada correctamente" : "No se pudo actualizar la alergia";
            }
        }
    }

    public function eliminar() {
        $alergias = new Alergias();
        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";

        // Verificar permisos
        $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
        $esPadreTutor = ($cargo == 'Padre/Tutor');

        if ($esPadreTutor) {
            // Verificar si la alergia pertenece a un niño del tutor
            $alergia = $alergias->mostrar($id);
            if ($alergia && $this->perteneceAlTutor($alergia['id_nino'], $_SESSION['idusuario'])) {
                $rspta = $alergias->eliminar($id);
                echo $rspta ? "Alergia eliminada correctamente" : "No se pudo eliminar la alergia";
            } else {
                echo "No tienes permisos para eliminar esta alergia";
            }
        } else {
            $rspta = $alergias->eliminar($id);
            echo $rspta ? "Alergia eliminada correctamente" : "No se pudo eliminar la alergia";
        }
    }

    public function mostrar() {
        $alergias = new Alergias();
        $id = isset($_POST["id_alergia"]) ? limpiarCadena($_POST["id_alergia"]) : "";
        $rspta = $alergias->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $alergias = new Alergias();

        // Si es maestro, mostrar solo alergias de sus niños asignados
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 'Maestro') {
            $rspta = $alergias->listarParaMaestro($_SESSION['idusuario']);
        } else {
            $rspta = $alergias->listar();
        }

        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            // Para médicos y administradores pueden editar/eliminar, maestros y padres/tutores solo ver
            $cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
            if ($cargo == 'Médico/Enfermería' || $cargo == 'Administrador') {
                $acciones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_alergia . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_alergia . ')"><i class="fa fa-trash"></i></button>';
            } elseif ($cargo == 'Maestro' || $cargo == 'Padre/Tutor') {
                $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_alergia . ')"><i class="fa fa-eye"></i></button>';
            } else {
                $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_alergia . ')"><i class="fa fa-eye"></i></button>';
            }

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->tipo_alergia,
                "3" => $reg->descripcion,
                "4" => $reg->id_alergia
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
        $alergias = new Alergias();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $alergias->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_alergia,
                "1" => $reg->tipo_alergia,
                "2" => $reg->descripcion
            );
        }
        echo json_encode($data);
    }

    public function listarPorTutor() {
        $alergias = new Alergias();

        if (!isset($_SESSION['idusuario'])) {
            echo json_encode(array("sEcho" => 1, "iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "aaData" => array()));
            return;
        }

        $user_id = $_SESSION['idusuario'];
        $rspta = $alergias->listarPorTutor($user_id);

        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            // Para padres/tutores, mostrar solo botón de ver
            $acciones = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->id_alergia . ')"><i class="fa fa-eye"></i></button>';

            $data[] = array(
                "0" => $acciones,
                "1" => $reg->nino,
                "2" => $reg->tipo_alergia,
                "3" => $reg->descripcion,
                "4" => $reg->id_alergia
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