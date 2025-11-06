<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class ResponsablesRetiro{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin){
		$sql="INSERT INTO responsables_retiro (id_nino, nombre_completo, parentesco, telefono, autorizacion_firma, periodo_inicio, periodo_fin) 
		VALUES ('$id_nino', '$nombre_completo', '$parentesco', '$telefono', '$autorizacion_firma', '$periodo_inicio', '$periodo_fin')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_responsable, $id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin){
		$sql="UPDATE responsables_retiro SET 
		id_nino='$id_nino', 
		nombre_completo='$nombre_completo', 
		parentesco='$parentesco', 
		telefono='$telefono', 
		autorizacion_firma='$autorizacion_firma', 
		periodo_inicio='$periodo_inicio', 
		periodo_fin='$periodo_fin' 
		WHERE id_responsable='$id_responsable'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_responsable){
		$sql="DELETE FROM responsables_retiro WHERE id_responsable='$id_responsable'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_responsable){
		$sql="SELECT r.*, n.nombre_completo as nino 
		FROM responsables_retiro r 
		LEFT JOIN ninos n ON r.id_nino=n.id_nino 
		WHERE r.id_responsable='$id_responsable'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT r.id_responsable, r.nombre_completo, r.parentesco, r.telefono, r.autorizacion_firma, r.periodo_inicio, r.periodo_fin, 
		n.nombre_completo as nino, n.id_nino 
		FROM responsables_retiro r 
		LEFT JOIN ninos n ON r.id_nino=n.id_nino 
		ORDER BY r.id_responsable DESC";
		return ejecutarConsulta($sql);
	}

	//listar responsables por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_responsable, nombre_completo, parentesco, telefono, autorizacion_firma, periodo_inicio, periodo_fin 
		FROM responsables_retiro 
		WHERE id_nino='$id_nino' 
		ORDER BY nombre_completo";
		return ejecutarConsulta($sql);
	}
}
?>