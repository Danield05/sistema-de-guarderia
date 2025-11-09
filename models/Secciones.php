<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Secciones{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_seccion, $aula_id){
		$sql="INSERT INTO secciones (nombre_seccion, aula_id) VALUES ('$nombre_seccion', '$aula_id')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_seccion, $nombre_seccion, $aula_id){
		$sql="UPDATE secciones SET nombre_seccion='$nombre_seccion', aula_id='$aula_id' WHERE id_seccion='$id_seccion'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_seccion){
		$sql="DELETE FROM secciones WHERE id_seccion='$id_seccion'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_seccion){
		$sql="UPDATE secciones SET estado='1' WHERE id_seccion='$id_seccion'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_seccion){
		$sql="SELECT * FROM secciones WHERE id_seccion='$id_seccion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT s.*, a.nombre_aula FROM secciones s LEFT JOIN aulas a ON s.aula_id=a.id_aula ORDER BY s.id_seccion DESC";
		return ejecutarConsulta($sql);
	}

	//listar secciones por aula
	public function listarPorAula($aula_id){
		$sql="SELECT * FROM secciones WHERE aula_id='$aula_id' ORDER BY id_seccion DESC";
		return ejecutarConsulta($sql);
	}
}
?>