<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Enfermedades{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $nombre_enfermedad, $descripcion, $fecha_diagnostico){
		$sql="INSERT INTO enfermedades (id_nino, nombre_enfermedad, descripcion, fecha_diagnostico) 
		VALUES ('$id_nino', '$nombre_enfermedad', '$descripcion', '$fecha_diagnostico')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_enfermedad, $id_nino, $nombre_enfermedad, $descripcion, $fecha_diagnostico){
		$sql="UPDATE enfermedades SET 
		id_nino='$id_nino', 
		nombre_enfermedad='$nombre_enfermedad', 
		descripcion='$descripcion', 
		fecha_diagnostico='$fecha_diagnostico' 
		WHERE id_enfermedad='$id_enfermedad'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_enfermedad){
		$sql="DELETE FROM enfermedades WHERE id_enfermedad='$id_enfermedad'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_enfermedad){
		$sql="SELECT e.*, n.nombre_completo as nino 
		FROM enfermedades e 
		LEFT JOIN ninos n ON e.id_nino=n.id_nino 
		WHERE e.id_enfermedad='$id_enfermedad'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT e.id_enfermedad, e.nombre_enfermedad, e.descripcion, e.fecha_diagnostico,
		n.nombre_completo as nino, n.id_nino
		FROM enfermedades e
		LEFT JOIN ninos n ON e.id_nino=n.id_nino
		ORDER BY e.id_enfermedad DESC";
		return ejecutarConsulta($sql);
	}

	//listar registros para maestros (solo enfermedades de sus niños asignados)
	public function listarParaMaestro($maestro_id){
		$sql="SELECT e.id_enfermedad, e.nombre_enfermedad, e.descripcion, e.fecha_diagnostico,
		n.nombre_completo as nino, n.id_nino
		FROM enfermedades e
		LEFT JOIN ninos n ON e.id_nino=n.id_nino
		WHERE n.maestro_id='$maestro_id' AND n.estado=1
		ORDER BY e.id_enfermedad DESC";
		return ejecutarConsulta($sql);
	}

	//listar enfermedades por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_enfermedad, nombre_enfermedad, descripcion, fecha_diagnostico 
		FROM enfermedades 
		WHERE id_nino='$id_nino' 
		ORDER BY fecha_diagnostico DESC";
		return ejecutarConsulta($sql);
	}
}
?>