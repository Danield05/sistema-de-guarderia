<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ninos{

	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_completo, $fecha_nacimiento, $edad, $peso, $aula_id, $seccion_id, $maestro_id, $tutor_id){
		$sql="INSERT INTO ninos (nombre_completo, fecha_nacimiento, edad, peso, aula_id, seccion_id, maestro_id, tutor_id) 
		VALUES ('$nombre_completo', '$fecha_nacimiento', '$edad', '$peso', '$aula_id', '$seccion_id', '$maestro_id', '$tutor_id')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_nino, $nombre_completo, $fecha_nacimiento, $edad, $peso, $aula_id, $seccion_id, $maestro_id, $tutor_id){
		$sql="UPDATE ninos SET 
		nombre_completo='$nombre_completo', 
		fecha_nacimiento='$fecha_nacimiento', 
		edad='$edad', 
		peso='$peso', 
		aula_id='$aula_id', 
		seccion_id='$seccion_id', 
		maestro_id='$maestro_id', 
		tutor_id='$tutor_id' 
		WHERE id_nino='$id_nino'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_nino){
		$sql="UPDATE ninos SET estado='0' WHERE id_nino='$id_nino'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_nino){
		$sql="UPDATE ninos SET estado='1' WHERE id_nino='$id_nino'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_nino){
		$sql="SELECT n.*, a.nombre_aula, s.nombre_seccion,
		u1.nombre_completo as maestro, u2.nombre_completo as tutor
		FROM ninos n
		LEFT JOIN aulas a ON n.aula_id=a.id_aula
		LEFT JOIN secciones s ON n.seccion_id=s.id_seccion
		LEFT JOIN usuarios u1 ON n.maestro_id=u1.id_usuario
		LEFT JOIN usuarios u2 ON n.tutor_id=u2.id_usuario
		WHERE n.id_nino='$id_nino'";
		$result = ejecutarConsulta($sql);
		return $result->fetch(PDO::FETCH_OBJ);
	}

	//listar registros
	public function listar(){
		$sql="SELECT n.id_nino, n.nombre_completo, n.fecha_nacimiento, n.edad, n.peso, 
		a.nombre_aula, s.nombre_seccion, 
		u1.nombre_completo as maestro, u2.nombre_completo as tutor, n.estado 
		FROM ninos n 
		LEFT JOIN aulas a ON n.aula_id=a.id_aula 
		LEFT JOIN secciones s ON n.seccion_id=s.id_seccion 
		LEFT JOIN usuarios u1 ON n.maestro_id=u1.id_usuario 
		LEFT JOIN usuarios u2 ON n.tutor_id=u2.id_usuario 
		ORDER BY n.id_nino DESC";
		return ejecutarConsulta($sql);
	}

	//listar niños por aula
	public function listarPorAula($aula_id){
		$sql="SELECT n.id_nino, n.nombre_completo, n.edad, s.nombre_seccion 
		FROM ninos n 
		LEFT JOIN secciones s ON n.seccion_id=s.id_seccion 
		WHERE n.aula_id='$aula_id' AND n.estado=1 
		ORDER BY n.nombre_completo";
		return ejecutarConsulta($sql);
	}

	//listar niños por sección
	public function listarPorSeccion($seccion_id){
		$sql="SELECT id_nino, nombre_completo, edad FROM ninos 
		WHERE seccion_id='$seccion_id' AND estado=1 
		ORDER BY nombre_completo";
		return ejecutarConsulta($sql);
	}
}
?>