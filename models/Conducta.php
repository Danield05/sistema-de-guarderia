<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Conducta{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro (comportamiento)
	public function insertar($id_nino, $descripcion_comportamiento, $tipo_comportamiento){
		$sql="INSERT INTO alertas (id_nino, mensaje, tipo, estado) 
		VALUES ('$id_nino', 'Conducta: $descripcion_comportamiento', '$tipo_comportamiento', 'Pendiente')";
		return ejecutarConsulta($sql);
	}

	public function editar($id, $id_nino, $descripcion_comportamiento, $tipo_comportamiento){
		$sql="UPDATE alertas SET 
		mensaje='Conducta: $descripcion_comportamiento', 
		tipo='$tipo_comportamiento' 
		WHERE id_nino='$id_nino' AND tipo='$tipo_comportamiento'";
		return ejecutarConsulta($sql);
	}

	public function verificar($id_nino, $fecha){
		$sql="SELECT * FROM alertas 
		WHERE id_nino='$id_nino' 
		AND tipo='Conducta' 
		AND DATE(fecha_alerta)='$fecha'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function desactivar($id){
		// Marcar alerta como respondida para desactivar
		$sql="UPDATE alertas SET estado='Respondida' WHERE id='$id' AND tipo='Conducta'";
		return ejecutarConsulta($sql);
	}

	public function activar($id){
		// Marcar alerta como pendiente para activar
		$sql="UPDATE alertas SET estado='Pendiente' WHERE id='$id' AND tipo='Conducta'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id){
		$sql="SELECT a.*, n.nombre_completo as nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.id='$id' AND a.tipo='Conducta'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros de conducta
	public function listar($user_id = null){
		$sql="SELECT a.id, a.fecha_alerta, a.mensaje, a.estado, 
		n.nombre_completo as nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.tipo='Conducta'";
		
		if ($user_id) {
			$sql .= " AND n.maestro_id='$user_id'";
		}
		
		$sql .= " ORDER BY a.fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//listar y mostrar en select para conducta activa
	public function select(){
		$sql="SELECT * FROM alertas WHERE tipo='Conducta' AND estado='Pendiente'";
		return ejecutarConsulta($sql);
	}

	//metodo para listar conducta por niño y fecha
	public function listar_por_nino_y_fecha($id_nino, $fecha){
		$sql="SELECT * FROM alertas 
		WHERE id_nino='$id_nino' 
		AND tipo='Conducta' 
		AND DATE(fecha_alerta)='$fecha' 
		ORDER BY fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}
}
?>