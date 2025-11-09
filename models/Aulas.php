<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Aulas{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_aula, $descripcion){
		$sql="INSERT INTO aulas (nombre_aula, descripcion) VALUES ('$nombre_aula', '$descripcion')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_aula, $nombre_aula, $descripcion){
		$sql="UPDATE aulas SET nombre_aula='$nombre_aula', descripcion='$descripcion' WHERE id_aula='$id_aula'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_aula){
		$sql="DELETE FROM aulas WHERE id_aula='$id_aula'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_aula){
		$sql="UPDATE aulas SET estado='1' WHERE id_aula='$id_aula'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_aula){
		$sql="SELECT * FROM aulas WHERE id_aula='$id_aula'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT * FROM aulas ORDER BY id_aula DESC";
		return ejecutarConsulta($sql);
	}

	//listar registros para maestros (solo aulas donde tienen niños asignados)
	public function listarParaMaestro($maestro_id){
		$sql="SELECT DISTINCT a.* FROM aulas a
		INNER JOIN ninos n ON a.id_aula = n.aula_id
		WHERE n.maestro_id = '$maestro_id' AND n.estado = 1
		ORDER BY a.id_aula DESC";
		return ejecutarConsulta($sql);
	}
}
?>