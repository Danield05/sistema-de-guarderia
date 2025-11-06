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
}
?>