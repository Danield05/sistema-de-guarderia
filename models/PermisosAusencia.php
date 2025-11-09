<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class PermisosAusencia{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $tipo_permiso, $descripcion, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $archivo_permiso){
		$sql="INSERT INTO permisos_ausencia (id_nino, tipo_permiso, descripcion, fecha_inicio, fecha_fin, hora_inicio, hora_fin, archivo_permiso) 
		VALUES ('$id_nino', '$tipo_permiso', '$descripcion', '$fecha_inicio', '$fecha_fin', '$hora_inicio', '$hora_fin', '$archivo_permiso')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_permiso, $id_nino, $tipo_permiso, $descripcion, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $archivo_permiso){
		$sql="UPDATE permisos_ausencia SET 
		id_nino='$id_nino', 
		tipo_permiso='$tipo_permiso', 
		descripcion='$descripcion', 
		fecha_inicio='$fecha_inicio', 
		fecha_fin='$fecha_fin', 
		hora_inicio='$hora_inicio', 
		hora_fin='$hora_fin', 
		archivo_permiso='$archivo_permiso' 
		WHERE id_permiso='$id_permiso'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_permiso){
		$sql="DELETE FROM permisos_ausencia WHERE id_permiso='$id_permiso'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_permiso){
		$sql="SELECT p.*, n.nombre_completo as nino 
		FROM permisos_ausencia p 
		LEFT JOIN ninos n ON p.id_nino=n.id_nino 
		WHERE p.id_permiso='$id_permiso'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT p.id_permiso, p.tipo_permiso, p.descripcion, p.fecha_inicio, p.fecha_fin, p.hora_inicio, p.hora_fin, p.archivo_permiso,
		n.nombre_completo as nino, n.id_nino
		FROM permisos_ausencia p
		LEFT JOIN ninos n ON p.id_nino=n.id_nino
		ORDER BY p.id_permiso DESC";
		return ejecutarConsulta($sql);
	}

	//listar registros para maestros (solo permisos de sus niños asignados)
	public function listarParaMaestro($maestro_id){
		$sql="SELECT p.id_permiso, p.tipo_permiso, p.descripcion, p.fecha_inicio, p.fecha_fin, p.hora_inicio, p.hora_fin, p.archivo_permiso,
		n.nombre_completo as nino, n.id_nino
		FROM permisos_ausencia p
		LEFT JOIN ninos n ON p.id_nino=n.id_nino
		WHERE n.maestro_id='$maestro_id' AND n.estado=1
		ORDER BY p.id_permiso DESC";
		return ejecutarConsulta($sql);
	}

	//listar permisos por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_permiso, tipo_permiso, descripcion, fecha_inicio, fecha_fin, hora_inicio, hora_fin, archivo_permiso 
		FROM permisos_ausencia 
		WHERE id_nino='$id_nino' 
		ORDER BY fecha_inicio DESC";
		return ejecutarConsulta($sql);
	}

	//listar permisos por fechas
	public function listarPorFechas($fecha_inicio, $fecha_fin){
		$sql="SELECT p.id_permiso, p.tipo_permiso, p.descripcion, p.fecha_inicio, p.fecha_fin, p.hora_inicio, p.hora_fin, p.archivo_permiso,
		n.nombre_completo as nino
		FROM permisos_ausencia p
		LEFT JOIN ninos n ON p.id_nino=n.id_nino
		WHERE (p.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin') OR (p.fecha_fin BETWEEN '$fecha_inicio' AND '$fecha_fin')
		ORDER BY p.fecha_inicio DESC";
		return ejecutarConsulta($sql);
	}

	//listar permisos por tutor/padre
	public function listarPorTutor($tutor_id){
		$sql="SELECT p.id_permiso, p.tipo_permiso, p.descripcion, p.fecha_inicio, p.fecha_fin, p.hora_inicio, p.hora_fin, p.archivo_permiso,
		n.nombre_completo as nino, n.id_nino
		FROM permisos_ausencia p
		LEFT JOIN ninos n ON p.id_nino=n.id_nino
		WHERE n.tutor_id='$tutor_id' AND n.estado=1
		ORDER BY p.id_permiso DESC";
		return ejecutarConsulta($sql);
	}
}
?>