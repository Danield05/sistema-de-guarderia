<?php
require_once "../models/Aulas.php";
if (strlen(session_id()) < 1)
    session_start();

class AulasController {

    public function guardarYEditar() {
        $aulas = new Aulas();

        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $nombre_aula = isset($_POST["nombre_aula"]) ? limpiarCadena($_POST["nombre_aula"]) : "";
        $descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

        if (empty($id)) {
            $rspta = $aulas->insertar($nombre_aula, $descripcion);
            echo $rspta ? "Aula registrada correctamente" : "No se pudo registrar el aula";
        } else {
            $rspta = $aulas->editar($id, $nombre_aula, $descripcion);
            echo $rspta ? "Aula actualizada correctamente" : "No se pudo actualizar el aula";
        }
    }

    public function desactivar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->desactivar($id);
        echo $rspta ? "Aula eliminada correctamente" : "No se pudo eliminar el aula";
    }

    public function activar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->activar($id);
        echo $rspta ? "Aula activada correctamente" : "No se pudo activar el aula";
    }

    public function mostrar() {
        $aulas = new Aulas();
        $id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $rspta = $aulas->mostrar($id);
        echo json_encode($rspta);
    }

    public function listar() {
        $aulas = new Aulas();
        $rspta = $aulas->listar();
        $data = Array();

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $data[] = array(
                $reg->id_aula,
                $reg->nombre_aula
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

    public function detalleSeccionesMaestro() {
        require_once "../models/Consultas.php";
        $consultas = new Consultas();

        $aula_id = isset($_POST["idaula"]) ? limpiarCadena($_POST["idaula"]) : "";
        $maestro_id = isset($_POST["maestro_id"]) ? limpiarCadena($_POST["maestro_id"]) : "";

        // Obtener secciones con detalle de niños
        $sql = "SELECT
            s.nombre_seccion,
            COUNT(n.id_nino) as total_ninos,
            GROUP_CONCAT(n.nombre_completo SEPARATOR ', ') as nombres_ninos
        FROM secciones s
        LEFT JOIN ninos n ON s.id_seccion = n.seccion_id AND n.maestro_id = '$maestro_id' AND n.estado = 1
        WHERE s.aula_id = '$aula_id'
        GROUP BY s.id_seccion, s.nombre_seccion
        ORDER BY s.nombre_seccion";

        $rspta = ejecutarConsulta($sql);

        $html = '<div class="card" style="border: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px;">';
        $html .= '<div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: white; border-radius: 15px 15px 0 0; border-bottom: none;">';
        $html .= '<h5 class="mb-0"><i class="fa fa-sitemap"></i> Detalle de Secciones y Niños</h5>';
        $html .= '</div>';
        $html .= '<div class="card-body" style="padding: 2rem;">';

        $total_secciones = 0;
        $total_ninos = 0;

        while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
            $total_secciones++;
            $total_ninos += $reg->total_ninos;

            $html .= '<div class="mb-3 p-3" style="background: rgba(255,255,255,0.8); border-radius: 10px; border-left: 4px solid #17a2b8;">';
            $html .= '<h6 class="mb-2" style="color: #17a2b8; font-weight: 600;"><i class="fa fa-users"></i> ' . $reg->nombre_seccion . '</h6>';
            $html .= '<p class="mb-1"><strong>Total de niños:</strong> <span style="color: #e74c3c; font-weight: 600;">' . $reg->total_ninos . '</span></p>';

            if ($reg->total_ninos > 0) {
                $html .= '<p class="mb-0"><strong>Niños asignados:</strong> ' . $reg->nombres_ninos . '</p>';
            } else {
                $html .= '<p class="mb-0 text-muted"><em>No tiene niños asignados en esta sección</em></p>';
            }

            $html .= '</div>';
        }

        $html .= '<div class="mt-4 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px;">';
        $html .= '<h6 class="mb-0"><i class="fa fa-chart-bar"></i> Resumen del Aula</h6>';
        $html .= '<p class="mb-0">Total de secciones: <strong>' . $total_secciones . '</strong> | Total de niños asignados: <strong>' . $total_ninos . '</strong></p>';
        $html .= '</div>';

        $html .= '</div></div>';

        echo $html;
    }
}
?>