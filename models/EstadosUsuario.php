<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class EstadosUsuario{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_estado, $descripcion){
		$sql="INSERT INTO estados_usuario (nombre_estado, descripcion) VALUES ('$nombre_estado', '$descripcion')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_estado_usuario, $nombre_estado, $descripcion){
		$sql="UPDATE estados_usuario SET nombre_estado='$nombre_estado', descripcion='$descripcion' WHERE id_estado_usuario='$id_estado_usuario'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_estado_usuario){
		$sql="DELETE FROM estados_usuario WHERE id_estado_usuario='$id_estado_usuario'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_estado_usuario){
		$sql="SELECT * FROM estados_usuario WHERE id_estado_usuario='$id_estado_usuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT * FROM estados_usuario ORDER BY id_estado_usuario DESC";
		return ejecutarConsulta($sql);
	}

	//obtener estado activo por defecto
	public function obtenerActivo(){
		$sql="SELECT * FROM estados_usuario WHERE nombre_estado='Activo' LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
	}

	//obtener estado inactivo por defecto
	public function obtenerInactivo(){
		$sql="SELECT * FROM estados_usuario WHERE nombre_estado='Inactivo' LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
	}
}
?>