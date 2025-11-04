<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Permiso{


	//implementamos nuestro constructor
	public function __construct(){

	}

//insertar registro
public function insertar($nombre){
	$sql="INSERT INTO permiso (nombre,condicion) VALUES ('$nombre','1')";
	return ejecutarConsulta($sql);
}

//editar registro
public function editar($idpermiso,$nombre){
	$sql="UPDATE permiso SET nombre='$nombre' WHERE idpermiso='$idpermiso'";
	return ejecutarConsulta($sql);
}

//desactivar registro
public function desactivar($idpermiso){
	$sql="UPDATE permiso SET condicion='0' WHERE idpermiso='$idpermiso'";
	return ejecutarConsulta($sql);
}

//activar registro
public function activar($idpermiso){
	$sql="UPDATE permiso SET condicion='1' WHERE idpermiso='$idpermiso'";
	return ejecutarConsulta($sql);
}

//mostrar datos de un registro
public function mostrar($idpermiso){
	$sql="SELECT * FROM permiso WHERE idpermiso='$idpermiso'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM permiso";
	return ejecutarConsulta($sql);
}
}

?>