<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Asistencia{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $fecha, $estado_id, $observaciones){
		$sql="INSERT INTO asistencias (id_nino, fecha, estado_id, observaciones) 
		VALUES ('$id_nino', '$fecha', '$estado_id', '$observaciones')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_asistencia, $id_nino, $fecha, $estado_id, $observaciones){
		$sql="UPDATE asistencias SET 
		id_nino='$id_nino', 
		fecha='$fecha', 
		estado_id='$estado_id', 
		observaciones='$observaciones' 
		WHERE id_asistencia='$id_asistencia'";
		return ejecutarConsulta($sql);
	}

	public function verificar($fecha, $id_nino){
		$sql="SELECT * FROM asistencias WHERE fecha='$fecha' AND id_nino='$id_nino'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function eliminar($id_asistencia){
		$sql="DELETE FROM asistencias WHERE id_asistencia='$id_asistencia'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_asistencia){
		$sql="SELECT a.*, n.nombre_completo as nino, ea.nombre_estado 
		FROM asistencias a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		LEFT JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		WHERE a.id_asistencia='$id_asistencia'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT a.id_asistencia, a.fecha, a.observaciones, 
		n.nombre_completo as nino, ea.nombre_estado 
		FROM asistencias a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		LEFT JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		ORDER BY a.fecha DESC, a.id_asistencia DESC";
		return ejecutarConsulta($sql);
	}

	//listar asistencias por niño
	public function listarPorNino($id_nino){
		$sql="SELECT a.id_asistencia, a.fecha, a.observaciones, ea.nombre_estado 
		FROM asistencias a 
		LEFT JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		WHERE a.id_nino='$id_nino' 
		ORDER BY a.fecha DESC";
		return ejecutarConsulta($sql);
	}

	//listar asistencias por fecha
	public function listarPorFecha($fecha){
		$sql="SELECT a.id_asistencia, a.fecha, a.observaciones, 
		n.nombre_completo as nino, ea.nombre_estado 
		FROM asistencias a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		LEFT JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		WHERE a.fecha='$fecha' 
		ORDER BY n.nombre_completo";
		return ejecutarConsulta($sql);
	}

	//verificar si un niño tiene asistencia en una fecha específica
	public function verificarAsistencia($fecha, $id_nino, $estado_id = null){
		if ($estado_id) {
			$sql="SELECT * FROM asistencias WHERE fecha='$fecha' AND id_nino='$id_nino' AND estado_id='$estado_id'";
		} else {
			$sql="SELECT * FROM asistencias WHERE fecha='$fecha' AND id_nino='$id_nino'";
		}
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar asistencia con filtros
	public function listarConFiltros($aula_id = null, $seccion_id = null, $fecha_inicio = null, $fecha_fin = null, $estado_id = null){
		$where_conditions = array();
		
		if ($aula_id) {
			$where_conditions[] = "n.aula_id = '$aula_id'";
		}
		
		if ($seccion_id) {
			$where_conditions[] = "n.seccion_id = '$seccion_id'";
		}
		
		if ($fecha_inicio && $fecha_fin) {
			$where_conditions[] = "a.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		} elseif ($fecha_inicio) {
			$where_conditions[] = "a.fecha >= '$fecha_inicio'";
		} elseif ($fecha_fin) {
			$where_conditions[] = "a.fecha <= '$fecha_fin'";
		}
		
		if ($estado_id) {
			$where_conditions[] = "a.estado_id = '$estado_id'";
		}
		
		$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
		
		$sql="SELECT a.id_asistencia, a.fecha, a.observaciones,
		n.id_nino, n.nombre_completo, aul.nombre_aula, sec.nombre_seccion, ea.nombre_estado
		FROM asistencias a
		LEFT JOIN ninos n ON a.id_nino=n.id_nino
		LEFT JOIN aulas aul ON n.aula_id=aul.id_aula
		LEFT JOIN secciones sec ON n.seccion_id=sec.id_seccion
		LEFT JOIN estados_asistencia ea ON a.estado_id=ea.id_estado
		$where_clause
		ORDER BY a.fecha DESC, n.nombre_completo";
		return ejecutarConsulta($sql);
	}

	//obtener niños para registrar asistencia
	public function obtenerNinos($aula_id = null, $seccion_id = null){
		$where_conditions = array("n.estado = 1");
		
		if ($aula_id) {
			$where_conditions[] = "n.aula_id = '$aula_id'";
		}
		
		if ($seccion_id) {
			$where_conditions[] = "n.seccion_id = '$seccion_id'";
		}
		
		$where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
		
		$sql="SELECT n.id_nino, n.nombre_completo, aul.nombre_aula, sec.nombre_seccion
		FROM ninos n
		LEFT JOIN aulas aul ON n.aula_id=aul.id_aula
		LEFT JOIN secciones sec ON n.seccion_id=sec.id_seccion
		$where_clause
		ORDER BY n.nombre_completo";
		return ejecutarConsulta($sql);
	}

	//generar reporte de asistencia
	public function generarReporte($formato, $aula_id = null, $seccion_id = null, $fecha_inicio = null, $fecha_fin = null, $estado_id = null, $vista_previa = false){
		$rspta = $this->listarConFiltros($aula_id, $seccion_id, $fecha_inicio, $fecha_fin, $estado_id);
		
		// Si es vista previa, devolver solo el HTML sin headers de descarga
		if ($vista_previa && $formato == 'pdf') {
			return $this->generarHTMLParaVistaPrevia($rspta);
		}
		
		// Configurar headers según el formato (solo para descargas reales)
		switch($formato){
			case 'csv':
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=reporte_asistencia_'.date('Y-m-d').'.csv');
				$output = fopen('php://output', 'w');
				// UTF-8 BOM para Excel
				fputs($output, "\xEF\xBB\xBF");
				break;
			case 'xls':
				header('Content-Type: application/vnd.ms-excel; charset=utf-8');
				header('Content-Disposition: attachment; filename=reporte_asistencia_'.date('Y-m-d').'.xls');
				$output = fopen('php://output', 'w');
				// UTF-8 BOM para Excel (agregado para corregir caracteres)
				fputs($output, "\xEF\xBB\xBF");
				break;
			case 'pdf':
				// Generamos HTML optimizado para impresión que puede convertirse a PDF
				header('Content-Type: text/html; charset=utf-8');
				header('Content-Disposition: inline; filename=reporte_asistencia_'.date('Y-m-d').'.html');
				$output = fopen('php://output', 'w');
				break;
		}
		
		if ($formato == 'pdf') {
			// Generar HTML optimizado para impresión/PDF
			fwrite($output, '<html><head><meta charset="UTF-8"><title>Reporte de Asistencia</title>');
			fwrite($output, '<style>
				@page { margin: 1in; }
				body { font-family: Arial, sans-serif; font-size: 12px; }
				table { border-collapse: collapse; width: 100%; margin-top: 20px; }
				th, td { border: 1px solid #000; padding: 8px; text-align: left; }
				th { background-color: #f0f0f0; font-weight: bold; }
				h1 { color: #333; border-bottom: 2px solid #333; }
				.info { margin: 10px 0; }
			</style></head><body>');
			fwrite($output, '<h1>📅 Reporte de Asistencia</h1>');
			fwrite($output, '<p>Fecha de generación: '.date('d/m/Y H:i').'</p>');
		} else {
			// Headers CSV/XLS
			fputcsv($output, array('Nombre Completo', 'Aula', 'Sección', 'Fecha', 'Estado', 'Observaciones'));
		}
		
		while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
			if ($formato == 'pdf') {
				fwrite($output, '<tr>');
				fwrite($output, '<td>'.$reg->nombre_completo.'</td>');
				fwrite($output, '<td>'.$reg->nombre_aula.'</td>');
				fwrite($output, '<td>'.$reg->nombre_seccion.'</td>');
				fwrite($output, '<td>'.$reg->fecha.'</td>');
				fwrite($output, '<td>'.$reg->nombre_estado.'</td>');
				fwrite($output, '<td>'.$reg->observaciones.'</td>');
				fwrite($output, '</tr>');
			} else {
				// Asegurar que los datos estén en UTF-8
				$nombre_completo = $this->ensureUtf8($reg->nombre_completo);
				$nombre_aula = $this->ensureUtf8($reg->nombre_aula);
				$nombre_seccion = $this->ensureUtf8($reg->nombre_seccion);
				$nombre_estado = $this->ensureUtf8($reg->nombre_estado);
				$observaciones = $this->ensureUtf8($reg->observaciones);
				
				fputcsv($output, array(
					$nombre_completo,
					$nombre_aula,
					$nombre_seccion,
					$reg->fecha,
					$nombre_estado,
					$observaciones
				));
			}
		}
		
		if ($formato == 'pdf') {
			fwrite($output, '</table></body></html>');
		}
		
		fclose($output);
	}
	
	//generar HTML para vista previa del PDF
	private function generarHTMLParaVistaPrevia($rspta){
		$html = '<div style="padding: 20px;">';
		$html .= '<h1>📅 Reporte de Asistencia</h1>';
		$html .= '<p><strong>Fecha de generación:</strong> '.date('d/m/Y H:i').'</p>';
		$html .= '<table style="border-collapse: collapse; width: 100%; margin-top: 20px;">';
		$html .= '<thead>';
		$html .= '<tr style="background-color: #f0f0f0; font-weight: bold;">';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Nombre Completo</th>';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Aula</th>';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Sección</th>';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Fecha</th>';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Estado</th>';
		$html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Observaciones</th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		
		$contador = 0;
		while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
			$contador++;
			$color_fila = ($contador % 2 == 0) ? '#f9f9f9' : '#ffffff';
			$html .= '<tr style="background-color: '.$color_fila.';">';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$this->ensureUtf8($reg->nombre_completo).'</td>';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$this->ensureUtf8($reg->nombre_aula).'</td>';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$this->ensureUtf8($reg->nombre_seccion).'</td>';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$reg->fecha.'</td>';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$this->ensureUtf8($reg->nombre_estado).'</td>';
			$html .= '<td style="border: 1px solid #000; padding: 8px;">'.$this->ensureUtf8($reg->observaciones).'</td>';
			$html .= '</tr>';
		}
		
		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<p style="margin-top: 20px; font-size: 10px; color: #666;">Total de registros: '.$contador.'</p>';
		
		if ($contador == 0) {
			$html .= '<p style="text-align: center; color: #666; padding: 20px;">No se encontraron registros de asistencia para los filtros especificados.</p>';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	//función auxiliar para asegurar UTF-8
	private function ensureUtf8($str) {
		if (is_null($str)) return '';
		if (!mb_check_encoding($str, 'UTF-8')) {
			return mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1');
		}
		return $str;
	}

	//obtener estadísticas de asistencia - TODAS LAS ASISTENCIAS HISTÓRICAS
	public function obtenerEstadisticas($fecha = null){
		// Total de todos los registros de asistencia (sin filtro de fecha)
		$sqlTotal = "SELECT COUNT(*) as total FROM asistencias a
					INNER JOIN ninos n ON a.id_nino = n.id_nino
					WHERE n.estado = 1";
		$rsptaTotal = ejecutarConsultaSimpleFila($sqlTotal);
		$total_estudiantes = $rsptaTotal['total'];
		
		// TODAS las asistencias (estado_id = 1) - Histórico
		$sqlAsistieron = "SELECT COUNT(*) as total FROM asistencias a
						 INNER JOIN ninos n ON a.id_nino = n.id_nino
						 WHERE a.estado_id = 1 AND n.estado = 1";
		$rsptaAsistieron = ejecutarConsultaSimpleFila($sqlAsistieron);
		$asistieron = $rsptaAsistieron['total'];
		
		// TODAS las faltas (estado_id = 2) - Histórico
		$sqlFaltaron = "SELECT COUNT(*) as total FROM asistencias a
					   INNER JOIN ninos n ON a.id_nino = n.id_nino
					   WHERE a.estado_id = 2 AND n.estado = 1";
		$rsptaFaltaron = ejecutarConsultaSimpleFila($sqlFaltaron);
		$faltaron = $rsptaFaltaron['total'];
		
		// TODAS las tardanzas (estado_id = 4) - Histórico
		$sqlTardanzas = "SELECT COUNT(*) as total FROM asistencias a
						INNER JOIN ninos n ON a.id_nino = n.id_nino
						WHERE a.estado_id = 4 AND n.estado = 1";
		$rsptaTardanzas = ejecutarConsultaSimpleFila($sqlTardanzas);
		$tardanzas = $rsptaTardanzas['total'];
		
		// TODOS los permisos (estado_id = 3) - Histórico
		$sqlPermisos = "SELECT COUNT(*) as total FROM asistencias a
					   INNER JOIN ninos n ON a.id_nino = n.id_nino
					   WHERE a.estado_id = 3 AND n.estado = 1";
		$rsptaPermisos = ejecutarConsultaSimpleFila($sqlPermisos);
		$permisos = $rsptaPermisos['total'];
		
		return array(
			'total_estudiantes' => $total_estudiantes,
			'asistieron' => $asistieron,
			'faltaron' => $faltaron,
			'tardanzas' => $tardanzas,
			'permisos' => $permisos
		);
	}
}
?>