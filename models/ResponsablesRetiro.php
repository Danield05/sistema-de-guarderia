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
		VALUES (?, ?, ?, ?, ?, ?, ?)";
		$params = [$id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin];
		return ejecutarConsulta_preparada($sql, $params);
	}

	public function editar($id_responsable, $id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin){
		$sql="UPDATE responsables_retiro SET 
		id_nino=?,
		nombre_completo=?,
		parentesco=?,
		telefono=?,
		autorizacion_firma=?,
		periodo_inicio=?,
		periodo_fin=? 
		WHERE id_responsable=?";
		$params = [$id_nino, $nombre_completo, $parentesco, $telefono, $autorizacion_firma, $periodo_inicio, $periodo_fin, $id_responsable];
		return ejecutarConsulta_preparada($sql, $params);
	}

	public function eliminar($id_responsable){
		$sql="DELETE FROM responsables_retiro WHERE id_responsable=?";
		return ejecutarConsulta_preparada($sql, [$id_responsable]);
	}

	//metodo para mostrar registros
	public function mostrar($id_responsable){
		$sql="SELECT r.*, n.nombre_completo as nino 
		FROM responsables_retiro r 
		LEFT JOIN ninos n ON r.id_nino=n.id_nino 
		WHERE r.id_responsable=?";
		return ejecutarConsultaSimpleFila_preparada($sql, [$id_responsable]);
	}

	//listar registros
		public function listar(){
			$sql="SELECT r.id_responsable, r.nombre_completo, r.parentesco, r.telefono, r.autorizacion_firma, r.periodo_inicio, r.periodo_fin,
			n.nombre_completo as nino, n.id_nino, '1' as estado
			FROM responsables_retiro r
			LEFT JOIN ninos n ON r.id_nino=n.id_nino
			ORDER BY r.id_responsable DESC";
			return ejecutarConsulta($sql);
		}

		//listar registros con filtros
		public function listarConFiltros($busqueda = '', $filtroEstado = ''){
			$sql="SELECT r.id_responsable, r.nombre_completo, r.parentesco, r.telefono, r.autorizacion_firma, r.periodo_inicio, r.periodo_fin,
			n.nombre_completo as nino, n.id_nino, '1' as estado
			FROM responsables_retiro r
			LEFT JOIN ninos n ON r.id_nino=n.id_nino
			WHERE 1=1";

			if (!empty($busqueda)) {
				$sql .= " AND (r.nombre_completo LIKE '%$busqueda%' OR n.nombre_completo LIKE '%$busqueda%' OR r.parentesco LIKE '%$busqueda%')";
			}

			if (!empty($filtroEstado)) {
				$sql .= " AND '1' = '$filtroEstado'";
			}

			$sql .= " ORDER BY r.id_responsable DESC";
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

	//listar responsables para maestros (solo de sus niños asignados)
	public function listarParaMaestro($maestro_id, $busqueda = '', $filtroEstado = ''){
		$sql="SELECT r.id_responsable, r.nombre_completo, r.parentesco, r.telefono, r.autorizacion_firma, r.periodo_inicio, r.periodo_fin,
		n.nombre_completo as nino, n.id_nino, '1' as estado
		FROM responsables_retiro r
		INNER JOIN ninos n ON r.id_nino=n.id_nino
		WHERE n.maestro_id='$maestro_id' AND n.estado=1";

		if (!empty($busqueda)) {
			$sql .= " AND (r.nombre_completo LIKE '%$busqueda%' OR n.nombre_completo LIKE '%$busqueda%' OR r.parentesco LIKE '%$busqueda%')";
		}

		if (!empty($filtroEstado)) {
			$sql .= " AND '1' = '$filtroEstado'";
		}

		$sql .= " ORDER BY r.id_responsable DESC";
		return ejecutarConsulta($sql);
	}
}
?>