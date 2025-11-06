<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Alertas{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $mensaje, $tipo, $estado){
		$sql="INSERT INTO alertas (id_nino, mensaje, tipo, estado) 
		VALUES ('$id_nino', '$mensaje', '$tipo', '$estado')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_alerta, $id_nino, $mensaje, $tipo, $estado){
		$sql="UPDATE alertas SET 
		id_nino='$id_nino', 
		mensaje='$mensaje', 
		tipo='$tipo', 
		estado='$estado' 
		WHERE id_alerta='$id_alerta'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_alerta){
		$sql="DELETE FROM alertas WHERE id_alerta='$id_alerta'";
		return ejecutarConsulta($sql);
	}

	public function marcarRespondida($id_alerta){
		$sql="UPDATE alertas SET estado='Respondida' WHERE id_alerta='$id_alerta'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_alerta){
		$sql="SELECT a.*, n.nombre_completo as nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.id_alerta='$id_alerta'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT a.id_alerta, a.fecha_alerta, a.mensaje, a.tipo, a.estado, 
		n.nombre_completo as nino, n.id_nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		ORDER BY a.fecha_alerta DESC, a.id_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//listar alertas por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_alerta, fecha_alerta, mensaje, tipo, estado 
		FROM alertas 
		WHERE id_nino='$id_nino' 
		ORDER BY fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//listar alertas por tipo
	public function listarPorTipo($tipo){
		$sql="SELECT a.id_alerta, a.fecha_alerta, a.mensaje, a.estado, 
		n.nombre_completo as nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.tipo='$tipo' 
		ORDER BY a.fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//listar alertas pendientes
	public function listarPendientes(){
		$sql="SELECT a.id_alerta, a.fecha_alerta, a.mensaje, a.tipo, 
		n.nombre_completo as nino, n.id_nino 
		FROM alertas a 
		LEFT JOIN ninos n ON a.id_nino=n.id_nino 
		WHERE a.estado='Pendiente' 
		ORDER BY a.fecha_alerta DESC";
		return ejecutarConsulta($sql);
	}

	//contar alertas pendientes
	public function contarPendientes(){
		$sql="SELECT COUNT(*) as total FROM alertas WHERE estado='Pendiente'";
		return ejecutarConsultaSimpleFila($sql);
	}
}
?>