<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
require "../models/EstadosUsuario.php";
class Usuario{

	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar registro
	public function insertar($nombre_completo, $email, $password, $rol_id, $telefono, $direccion){
		// Obtener ID del estado "Activo" por defecto
		$estados = new EstadosUsuario();
		$estadoActivo = $estados->obtenerActivo();
		$estado_usuario_id = $estadoActivo ? $estadoActivo['id_estado_usuario'] : 1;
		
		$sql="INSERT INTO usuarios (nombre_completo, email, password, rol_id, telefono, direccion, estado_usuario_id) 
		VALUES ('$nombre_completo', '$email', '$password', '$rol_id', '$telefono', '$direccion', '$estado_usuario_id')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_usuario, $nombre_completo, $email, $password, $rol_id, $telefono, $direccion, $estado_usuario_id){
		$sql="UPDATE usuarios SET 
		nombre_completo='$nombre_completo', 
		email='$email', 
		password='$password', 
		rol_id='$rol_id', 
		telefono='$telefono', 
		direccion='$direccion', 
		estado_usuario_id='$estado_usuario_id' 
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function editar_sin_password($id_usuario, $nombre_completo, $email, $rol_id, $telefono, $direccion, $estado_usuario_id){
		$sql="UPDATE usuarios SET 
		nombre_completo='$nombre_completo', 
		email='$email', 
		rol_id='$rol_id', 
		telefono='$telefono', 
		direccion='$direccion', 
		estado_usuario_id='$estado_usuario_id' 
		WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_usuario){
		// Obtener ID del estado "Inactivo"
		$estados = new EstadosUsuario();
		$estadoInactivo = $estados->obtenerInactivo();
		$estado_usuario_id = $estadoInactivo ? $estadoInactivo['id_estado_usuario'] : 2;
		
		$sql="UPDATE usuarios SET estado_usuario_id='$estado_usuario_id' WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_usuario){
		// Obtener ID del estado "Activo"
		$estados = new EstadosUsuario();
		$estadoActivo = $estados->obtenerActivo();
		$estado_usuario_id = $estadoActivo ? $estadoActivo['id_estado_usuario'] : 1;
		
		$sql="UPDATE usuarios SET estado_usuario_id='$estado_usuario_id' WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_usuario){
		$sql="SELECT u.*, r.nombre_rol as rol, eu.nombre_estado as estado_usuario 
		FROM usuarios u 
		LEFT JOIN roles r ON u.rol_id=r.id_rol 
		LEFT JOIN estados_usuario eu ON u.estado_usuario_id=eu.id_estado_usuario 
		WHERE u.id_usuario='$id_usuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT u.*, r.nombre_rol as rol, eu.nombre_estado as estado_usuario 
		FROM usuarios u 
		LEFT JOIN roles r ON u.rol_id=r.id_rol 
		LEFT JOIN estados_usuario eu ON u.estado_usuario_id=eu.id_estado_usuario 
		ORDER BY u.id_usuario DESC";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
	{
		$sql="SELECT u.id_usuario,u.nombre_completo,u.rol_id,u.telefono,u.email,u.password, r.nombre_rol
		FROM usuarios u
		LEFT JOIN roles r ON u.rol_id=r.id_rol
		LEFT JOIN estados_usuario eu ON u.estado_usuario_id=eu.id_estado_usuario
		WHERE u.email='$login' AND u.password='$clave' AND eu.nombre_estado='Activo'";
		return ejecutarConsulta($sql);
	}

	//Metodos adicionales para gestionar estados de usuario
	public function cambiarEstado($id_usuario, $estado_usuario_id){
		$sql="UPDATE usuarios SET estado_usuario_id='$estado_usuario_id' WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function listarEstados(){
		$estados = new EstadosUsuario();
		return $estados->listar();
	}

	public function editar_clave($id_usuario, $password){
		$sql="UPDATE usuarios SET password='$password' WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function editar_password($id_usuario, $password){
		$sql="UPDATE usuarios SET password='$password' WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}

	public function mostrar_clave($id_usuario){
		$sql="SELECT password FROM usuarios WHERE id_usuario='$id_usuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	// Método para listar permisos marcados (usado en gestión de permisos)
	public function listarmarcados($id_usuario){
		$sql="SELECT * FROM usuario_permiso WHERE id_usuario='$id_usuario'";
		return ejecutarConsulta($sql);
	}
}
?>