<?php
require_once "../models/ResponsablesRetiro.php";
if (strlen(session_id()) < 1)
    session_start();

class ResponsablesRetiroController {

    public function guardarYEditar() {
        $responsables_retiro = new ResponsablesRetiro();

        $id = isset($_POST["id_responsable"]) ? limpiarCadena($_POST["id_responsable"]) : "";
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $nombre_completo = isset($_POST["nombre_completo"]) ? limpiarCadena($_POST["nombre_completo"]) : "";
        $parentesco = isset($_POST["parentesco"]) ? limpiarCadena($_POST["parentesco"]) : "";
        $telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
        $periodo_inicio = isset($_POST["periodo_inicio"]) ? limpiarCadena($_POST["periodo_inicio"]) : "";
        $periodo_fin = isset($_POST["periodo_fin"]) ? limpiarCadena($_POST["periodo_fin"]) : "";

        $autorizacion_firma = "";

        if (empty($id)) {
            $rspta = $responsables_retiro->insertar($id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin);
            echo $rspta ? "Responsable registrado correctamente" : "No se pudo registrar el responsable";
        } else {
            $rspta = $responsables_retiro->editar($id, $id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin);
            echo $rspta ? "Responsable actualizado correctamente" : "No se pudo actualizar el responsable";
        }
    }



    private function obtenerNombreNino($id_nino) {
        $sql = "SELECT nombre_completo FROM ninos WHERE id_nino = ?";
        $resultado = ejecutarConsultaSimpleFila_preparada($sql, [$id_nino]);
        return $resultado ? $resultado['nombre_completo'] : '';
    }

    private function obtenerFechaNacimiento($id_nino) {
        $sql = "SELECT fecha_nacimiento FROM ninos WHERE id_nino = ?";
        $resultado = ejecutarConsultaSimpleFila_preparada($sql, [$id_nino]);
        return $resultado ? $resultado['fecha_nacimiento'] : '';
    }

    private function obtenerAula($id_nino) {
        $sql = "SELECT a.nombre_aula FROM ninos n LEFT JOIN aulas a ON n.aula_id = a.id_aula WHERE n.id_nino = ?";
        $resultado = ejecutarConsultaSimpleFila_preparada($sql, [$id_nino]);
        return $resultado ? $resultado['nombre_aula'] : '';
    }

    private function calcularEdad($fecha_nacimiento) {
        if (!$fecha_nacimiento) return 0;
        $nacimiento = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        return $hoy->diff($nacimiento)->y;
    }

    public function eliminar() {
        $responsables_retiro = new ResponsablesRetiro();
        $id = isset($_POST["id_responsable"]) ? limpiarCadena($_POST["id_responsable"]) : "";
        $rspta = $responsables_retiro->eliminar($id);
        echo $rspta ? "Responsable eliminado correctamente" : "No se pudo eliminar el responsable";
    }

    public function mostrar() {
        $responsables_retiro = new ResponsablesRetiro();
        $id = isset($_POST["id_responsable"]) ? limpiarCadena($_POST["id_responsable"]) : "";
        $rspta = $responsables_retiro->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $responsables_retiro = new ResponsablesRetiro();
        $busqueda = isset($_POST['busqueda']) ? limpiarCadena($_POST['busqueda']) : '';
        $filtroEstado = isset($_POST['filtroEstado']) ? limpiarCadena($_POST['filtroEstado']) : '';

        try {
            $rspta = $responsables_retiro->listarConFiltros($busqueda, $filtroEstado);
            $data = Array();

            while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                $acciones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_responsable . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminar(' . $reg->id_responsable . ')"><i class="fa fa-trash"></i></button>';
    
                $data[] = array(
                    "0" => $acciones,
                    "1" => $reg->nino,
                    "2" => $reg->nombre_completo,
                    "3" => $reg->parentesco,
                    "4" => $reg->telefono,
                    "5" => $reg->periodo_inicio,
                    "6" => $reg->periodo_fin,
                    "7" => $reg->autorizacion_firma,
                    "8" => $reg->id_responsable
                );
            }
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode(array(
                "error" => true,
                "message" => "Error en consulta: " . $e->getMessage()
            ));
        }
    }

    public function listarPorNino() {
        $responsables_retiro = new ResponsablesRetiro();
        $id_nino = isset($_POST["id_nino"]) ? limpiarCadena($_POST["id_nino"]) : "";
        $rspta = $responsables_retiro->listarPorNino($id_nino);
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                "0" => $reg->id_responsable,
                "1" => $reg->nombre_completo,
                "2" => $reg->parentesco,
                "3" => $reg->telefono,
                "4" => $reg->autorizacion_firma,
                "5" => $reg->periodo_inicio,
                "6" => $reg->periodo_fin
            );
        }
        echo json_encode($data);
    }
}
?>