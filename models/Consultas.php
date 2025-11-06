<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Consultas{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo para listar asistencia de un niño
	public function listar_asistencia($id_nino, $fecha_inicio, $fecha_fin){
		$sql="SELECT a.fecha, a.observaciones, ea.nombre_estado 
		FROM asistencias a 
		INNER JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		WHERE a.id_nino='$id_nino' 
		AND a.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
		ORDER BY a.fecha DESC";
		return ejecutarConsulta($sql);
	}

	//metodo para listar comportamiento/alertas de un niño
	public function listar_comportamiento($id_nino, $fecha_inicio, $fecha_fin){
		$sql="SELECT al.fecha_alerta, al.mensaje, al.tipo, al.estado 
		FROM alertas al 
		WHERE al.id_nino='$id_nino' 
		AND al.fecha_alerta BETWEEN '$fecha_inicio' AND '$fecha_fin'
		ORDER BY al.fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//metodo para contar total de niños
	public function cantidad_ninos($user_id = null){
		if ($user_id) {
			$sql="SELECT COUNT(*) total_ninos FROM ninos n
			INNER JOIN usuarios u ON n.maestro_id=u.id_usuario
			WHERE u.id_usuario='$user_id'";
		} else {
			$sql="SELECT COUNT(*) total_ninos FROM ninos WHERE estado=1";
		}
		return ejecutarConsultaSimpleFila($sql);
	}

	//metodo para contar niños por aula
	public function cantidad_ninos_por_aula($aula_id){
		$sql="SELECT COUNT(*) total_ninos FROM ninos WHERE aula_id='$aula_id' AND estado=1";
		return ejecutarConsultaSimpleFila($sql);
	}

	//metodo para obtener estadísticas de asistencia
	public function estadisticas_asistencia($id_nino, $fecha_inicio, $fecha_fin){
		$sql="SELECT 
		ea.nombre_estado,
		COUNT(*) as cantidad
		FROM asistencias a 
		INNER JOIN estados_asistencia ea ON a.estado_id=ea.id_estado 
		WHERE a.id_nino='$id_nino' 
		AND a.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY a.estado_id, ea.nombre_estado";
		return ejecutarConsulta($sql);
	}

	//metodo para obtener niños con más alertas
	public function ninos_con_mas_alertas($fecha_inicio, $fecha_fin, $limite = 5){
		$sql="SELECT n.id_nino, n.nombre_completo, COUNT(al.id_alerta) as total_alertas
		FROM ninos n 
		INNER JOIN alertas al ON n.id_nino=al.id_nino 
		WHERE al.fecha_alerta BETWEEN '$fecha_inicio' AND '$fecha_fin'
		AND al.estado='Pendiente'
		GROUP BY n.id_nino, n.nombre_completo 
		ORDER BY total_alertas DESC 
		LIMIT $limite";
		return ejecutarConsulta($sql);
	}

	//metodo para obtener resumen de aulas
	public function resumen_aulas(){
		$sql="SELECT
		a.id_aula,
		a.nombre_aula,
		COUNT(DISTINCT n.id_nino) as total_ninos,
		COUNT(DISTINCT s.id_seccion) as total_secciones
		FROM aulas a
		LEFT JOIN ninos n ON a.id_aula=n.aula_id AND n.estado=1
		LEFT JOIN secciones s ON a.id_aula=s.aula_id
		GROUP BY a.id_aula, a.nombre_aula
		ORDER BY a.nombre_aula";
		return ejecutarConsulta($sql);
	}
}
?>