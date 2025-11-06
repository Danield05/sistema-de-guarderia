<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Calificaciones{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro (para calificaciones o evaluaciones)
	public function insertar($id_nino, $evaluacion, $tipo_evaluacion){
		// Simulamos calificaciones con una tabla de evaluaciones
		$sql="INSERT INTO alertas (id_nino, mensaje, tipo, estado) 
		VALUES ('$id_nino', 'Evaluación: $evaluacion', '$tipo_evaluacion', 'Pendiente')";
		return ejecutarConsulta($sql);
	}

	public function editar($id, $id_nino, $evaluacion, $tipo_evaluacion){
		// Actualizar alerta relacionada con la evaluación
		$sql="UPDATE alertas SET mensaje='Evaluación: $evaluacion', tipo='$tipo_evaluacion' 
		WHERE id_nino='$id_nino' AND tipo='$tipo_evaluacion'";
		return ejecutarConsulta($sql);
	}

	public function verificar($id_nino, $tipo_evaluacion){
		$sql="SELECT * FROM alertas WHERE id_nino='$id_nino' AND tipo='$tipo_evaluacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listar_calificacion($id_nino, $fecha_inicio, $fecha_fin){
		$sql="SELECT * FROM alertas 
		WHERE id_nino='$id_nino' 
		AND fecha_alerta BETWEEN '$fecha_inicio' AND '$fecha_fin'
		AND tipo IN ('Conducta', 'Evaluación', 'Desarrollo')
		ORDER BY fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//metodo para listar evaluaciones por fecha
	public function listar_por_fecha($fecha){
		$sql="SELECT a.id_alerta, a.fecha_alerta, a.mensaje, a.tipo, a.estado, 
		n.nombre_completo as nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE DATE(a.fecha_alerta)='$fecha' 
		AND a.tipo IN ('Conducta', 'Evaluación', 'Desarrollo')
		ORDER BY a.fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}
}
?>