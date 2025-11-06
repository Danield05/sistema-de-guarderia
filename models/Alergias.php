<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Alergias{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $tipo_alergia, $descripcion){
		$sql="INSERT INTO alergias (id_nino, tipo_alergia, descripcion) 
		VALUES ('$id_nino', '$tipo_alergia', '$descripcion')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_alergia, $id_nino, $tipo_alergia, $descripcion){
		$sql="UPDATE alergias SET 
		id_nino='$id_nino', 
		tipo_alergia='$tipo_alergia', 
		descripcion='$descripcion' 
		WHERE id_alergia='$id_alergia'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_alergia){
		$sql="DELETE FROM alergias WHERE id_alergia='$id_alergia'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_alergia){
		$sql="SELECT a.*, n.nombre_completo as nino 
		FROM alergias a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.id_alergia='$id_alergia'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT a.id_alergia, a.tipo_alergia, a.descripcion, 
		n.nombre_completo as nino, n.id_nino 
		FROM alergias a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		ORDER BY a.id_alergia DESC";
		return ejecutarConsulta($sql);
	}

	//listar alergias por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_alergia, tipo_alergia, descripcion 
		FROM alergias 
		WHERE id_nino='$id_nino' 
		ORDER BY tipo_alergia";
		return ejecutarConsulta($sql);
	}
}
?>