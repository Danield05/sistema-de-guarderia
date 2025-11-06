<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class EstadosAsistencia{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_estado, $descripcion){
		$sql="INSERT INTO estados_asistencia (nombre_estado, descripcion) 
		VALUES ('$nombre_estado', '$descripcion')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_estado, $nombre_estado, $descripcion){
		$sql="UPDATE estados_asistencia SET 
		nombre_estado='$nombre_estado', 
		descripcion='$descripcion' 
		WHERE id_estado='$id_estado'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_estado){
		$sql="DELETE FROM estados_asistencia WHERE id_estado='$id_estado'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_estado){
		$sql="SELECT * FROM estados_asistencia WHERE id_estado='$id_estado'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT * FROM estados_asistencia ORDER BY id_estado DESC";
		return ejecutarConsulta($sql);
	}

	//listar registros activos
	public function listarActivos(){
		$sql="SELECT * FROM estados_asistencia ORDER BY nombre_estado";
		return ejecutarConsulta($sql);
	}
}
?>