<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Horarios{

	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $dia_semana, $hora_entrada, $hora_salida, $descripcion){
		$sql="INSERT INTO horarios_estudio (id_nino, dia_semana, hora_entrada, hora_salida, descripcion)
		VALUES ('$id_nino', '$dia_semana', '$hora_entrada', '$hora_salida', '$descripcion')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_horario, $id_nino, $dia_semana, $hora_entrada, $hora_salida, $descripcion){
		$sql="UPDATE horarios_estudio SET
		id_nino='$id_nino',
		dia_semana='$dia_semana',
		hora_entrada='$hora_entrada',
		hora_salida='$hora_salida',
		descripcion='$descripcion'
		WHERE id_horario='$id_horario'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_horario){
		$sql="DELETE FROM horarios_estudio WHERE id_horario='$id_horario'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_horario){
		// No aplicable para esta tabla sin campo estado
		return true;
	}

	//metodo para mostrar registros
	public function mostrar($id_horario){
		$sql="SELECT h.*, n.nombre_completo
		FROM horarios_estudio h
		INNER JOIN ninos n ON h.id_nino=n.id_nino
		WHERE h.id_horario='$id_horario'";
		$result = ejecutarConsulta($sql);
		return $result->fetch(PDO::FETCH_OBJ);
	}

	//listar registros
	public function listar(){
		$sql="SELECT h.id_horario, h.id_nino, h.dia_semana, h.hora_entrada, h.hora_salida, h.descripcion,
		n.nombre_completo, 1 as estado
		FROM horarios_estudio h
		INNER JOIN ninos n ON h.id_nino=n.id_nino
		ORDER BY n.nombre_completo, h.dia_semana";
		return ejecutarConsulta($sql);
	}

	//listar horarios por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_horario, dia_semana, hora_entrada, hora_salida, descripcion, 1 as estado
		FROM horarios_estudio
		WHERE id_nino='$id_nino'
		ORDER BY FIELD(dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
		return ejecutarConsulta($sql);
	}

	//listar niños para select con información adicional
	public function listarNinos(){
		$sql="SELECT n.id_nino, n.nombre_completo, a.nombre_aula, s.nombre_seccion
		FROM ninos n
		LEFT JOIN aulas a ON n.aula_id=a.id_aula
		LEFT JOIN secciones s ON n.seccion_id=s.id_seccion
		WHERE n.estado=1 ORDER BY n.nombre_completo";
		return ejecutarConsulta($sql);
	}

	//listar horarios por tutor (solo para el hijo del tutor)
	public function listarPorTutor($id_usuario){
		$sql="SELECT h.id_horario, h.id_nino, h.dia_semana, h.hora_entrada, h.hora_salida, h.descripcion,
		n.nombre_completo, h.estado
		FROM horarios_estudio h
		INNER JOIN ninos n ON h.id_nino=n.id_nino
		WHERE n.tutor_id='$id_usuario' AND h.estado=1
		ORDER BY n.nombre_completo, FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
		return ejecutarConsulta($sql);
	}

	//listar con filtros
	public function listarConFiltros($nombre_nino='', $aula='', $seccion=''){
		$sql="SELECT h.id_horario, h.id_nino, h.dia_semana, h.hora_entrada, h.hora_salida, h.descripcion,
		n.nombre_completo, a.nombre_aula, s.nombre_seccion, 1 as estado
		FROM horarios_estudio h
		INNER JOIN ninos n ON h.id_nino=n.id_nino
		LEFT JOIN aulas a ON n.aula_id=a.id_aula
		LEFT JOIN secciones s ON n.seccion_id=s.id_seccion
		WHERE 1=1";

		if (!empty($nombre_nino)) {
			$sql .= " AND n.nombre_completo LIKE '%$nombre_nino%'";
		}
		if (!empty($aula)) {
			$sql .= " AND a.nombre_aula = '$aula'";
		}
		if (!empty($seccion)) {
			$sql .= " AND s.nombre_seccion = '$seccion'";
		}

		$sql .= " ORDER BY n.nombre_completo, FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
		return ejecutarConsulta($sql);
	}

	//listar aulas para filtro
	public function listarAulas(){
		$sql="SELECT DISTINCT a.nombre_aula FROM aulas a ORDER BY a.nombre_aula";
		return ejecutarConsulta($sql);
	}

	//listar secciones para filtro
	public function listarSecciones(){
		$sql="SELECT DISTINCT s.nombre_seccion FROM secciones s ORDER BY s.nombre_seccion";
		return ejecutarConsulta($sql);
	}
}
?>