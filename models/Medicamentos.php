<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Medicamentos{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones){
		$sql="INSERT INTO medicamentos (id_nino, nombre_medicamento, dosis, frecuencia, observaciones) 
		VALUES ('$id_nino', '$nombre_medicamento', '$dosis', '$frecuencia', '$observaciones')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_medicamento, $id_nino, $nombre_medicamento, $dosis, $frecuencia, $observaciones){
		$sql="UPDATE medicamentos SET 
		id_nino='$id_nino', 
		nombre_medicamento='$nombre_medicamento', 
		dosis='$dosis', 
		frecuencia='$frecuencia', 
		observaciones='$observaciones' 
		WHERE id_medicamento='$id_medicamento'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($id_medicamento){
		$sql="DELETE FROM medicamentos WHERE id_medicamento='$id_medicamento'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_medicamento){
		$sql="SELECT m.*, n.nombre_completo as nino 
		FROM medicamentos m 
		LEFT JOIN ninos n ON m.id_nino=n.id_nino 
		WHERE m.id_medicamento='$id_medicamento'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT m.id_medicamento, m.nombre_medicamento, m.dosis, m.frecuencia, m.observaciones,
		n.nombre_completo as nino, n.id_nino
		FROM medicamentos m
		LEFT JOIN ninos n ON m.id_nino=n.id_nino
		ORDER BY m.id_medicamento DESC";
		return ejecutarConsulta($sql);
	}

	//listar registros para maestros (solo medicamentos de sus niños asignados)
	public function listarParaMaestro($maestro_id){
		$sql="SELECT m.id_medicamento, m.nombre_medicamento, m.dosis, m.frecuencia, m.observaciones,
		n.nombre_completo as nino, n.id_nino
		FROM medicamentos m
		LEFT JOIN ninos n ON m.id_nino=n.id_nino
		WHERE n.maestro_id='$maestro_id' AND n.estado=1
		ORDER BY m.id_medicamento DESC";
		return ejecutarConsulta($sql);
	}

	//listar medicamentos por niño
	public function listarPorNino($id_nino){
		$sql="SELECT id_medicamento, nombre_medicamento, dosis, frecuencia, observaciones 
		FROM medicamentos 
		WHERE id_nino='$id_nino' 
		ORDER BY nombre_medicamento";
		return ejecutarConsulta($sql);
	}
}
?>