<?php
if (strlen(session_id()) < 1)
	session_start();
require_once "../models/Usuario.php";
require_once "../controllers/UsuarioController.php";


$usuario=new Usuario();
$usuarioController = new UsuarioController();

$idusuarioc=isset($_POST["idusuarioc"])? limpiarCadena($_POST["idusuarioc"]):"";
$clavec=isset($_POST["clavec"])? limpiarCadena($_POST["clavec"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$dui=isset($_POST["dui"])? limpiarCadena($_POST["dui"]):"";
$rol=isset($_POST["rol"])? limpiarCadena($_POST["rol"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
		}else
		{
			$ext=explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			 {
			   $fotografia = round(microtime(true)).'.'. end($ext);
			   $target_path = "../files/usuarios/" . $fotografia;

			   if(move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_path)) {
			   	// Archivo subido correctamente
			   } else {
			   	$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
			   }
		 	} else {
		 		$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
		 	}
		}

		//Hash SHA256 para la contraseña
		$clavehash=hash("SHA256", $clave);

		// Convertir el valor del rol a ID numérico
		$rol_id = intval($rol);
		if ($rol_id <= 0) {
			$rol_id = 1; // Default a Padre/Tutor si no es válido
		}

		if (empty($idusuario)) {
			$rspta=$usuario->insertar($nombre,$dui,$email,$clavehash,$rol_id,$telefono,$direccion,$fotografia);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		}
		else {
			// Para edición, obtener el estado actual del usuario
			$usuario_actual = $usuario->mostrar($idusuario);
			$estado_actual = $usuario_actual ? $usuario_actual['estado_usuario_id'] : 1;

			// Si se proporciona nueva contraseña, hashearla
			if (!empty($clave)) {
				$clavehash=hash("SHA256", $clave);
				$rspta=$usuario->editar($idusuario,$nombre,$dui,$email,$clavehash,$rol_id,$telefono,$direccion,$estado_actual,$fotografia);
			} else {
				// Mantener contraseña actual
				$rspta=$usuario->editar_sin_password($idusuario,$nombre,$dui,$email,$rol_id,$telefono,$direccion,$estado_actual,$fotografia);
			}
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;


	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;

	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

	case 'editar_clave':
		$clavehash=hash("SHA256", $clavec);

		$rspta=$usuario->editar_clave($idusuarioc,$clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
	break;

	case 'mostrar_clave':
		$rspta=$usuario->mostrar_clave($idusuario);
		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$usuario->listar();
		//declaramos un array
		$data=Array();

		while ($reg=$rspta->fetch(PDO::FETCH_OBJ)) {
			$data[]=array(
				"0"=>($reg->estado_usuario == 'Activo')?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id_usuario.')"><i class="fa fa-pencil"></i></button> <button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->id_usuario.')"><i class="fa fa-key"></i></button> <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->id_usuario.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id_usuario.')"><i class="fa fa-pencil"></i></button> <button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->id_usuario.')"><i class="fa fa-key"></i></button> <button class="btn btn-primary btn-xs" onclick="activar('.$reg->id_usuario.')"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre_completo,  // Nombre Completo
				"2"=>$reg->dui,              // DUI
				"3"=>$reg->telefono,         // Teléfono
				"4"=>$reg->email,            // Email
				"5"=>$reg->rol,              // Rol
				"6"=>$reg->imagen,           // Foto
				"7"=>$reg->estado_usuario,   // Estado
				"8"=>$reg->id_usuario        // ID (para botones)
				);
		}

		$results=array(
	            "sEcho"=>1,//info para datatables
	            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
	            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
	            "aaData"=>$data);
		echo json_encode($results);

	break;

	case 'permisos':
		//obtenemos toodos los permisos de la tabla permisos
		require_once "../models/Permiso.php";
		$permiso=new Permiso();
		$rspta=$permiso->listar();

		//obtener permisos asigandos
		$id=$_GET['id'];
		$marcados=$usuario->listarmarcados($id);
		//declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//almacenar permisos asigandos
		while ($per = $marcados->fetch(PDO::FETCH_OBJ))
			{
				array_push($valores, $per->idpermiso);
			}

		//mostramos la lista de permisos
		while ($reg=$rspta->fetch(PDO::FETCH_OBJ))
			{
				$sw=in_array($reg->idpermiso,$valores)?'checked':'';
				echo '<li><input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
			}
	break;

	case 'verificar':
		// Usar el controlador en lugar de lógica directa
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];
		echo $usuarioController->verificar($logina, $clavea);

	break;

	case 'mostrar_perfil':
		// Mostrar datos del usuario logueado
		$id_usuario_logueado = $_SESSION['idusuario'];
		$rspta = $usuario->mostrar($id_usuario_logueado);
		echo json_encode($rspta);
	break;

	case 'guardaryeditar_perfil':
		// Editar perfil del usuario logueado
		$id_usuario_logueado = $_SESSION['idusuario'];

		// Verificar contraseña actual si se proporciona nueva contraseña
		$clave_nueva = isset($_POST["clave_nueva"]) ? limpiarCadena($_POST["clave_nueva"]) : "";
		$clave_actual = isset($_POST["clave_actual"]) ? limpiarCadena($_POST["clave_actual"]) : "";

		if (!empty($clave_nueva)) {
			// Verificar que la contraseña actual sea correcta
			$clave_actual_hash = hash("SHA256", $clave_actual);
			$usuario_actual = $usuario->mostrar_clave($id_usuario_logueado);

			if ($usuario_actual['password'] !== $clave_actual_hash) {
				echo "La contraseña actual es incorrecta";
				break;
			}
		}

		// Procesar imagen
		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
				$fotografia = round(microtime(true)) . '.' . end($ext);
				$target_path = "../files/usuarios/" . $fotografia;

				if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_path)) {
					// Archivo subido correctamente
				} else {
					$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
				}
			} else {
				$fotografia = isset($_POST["imagenactual"]) && !empty($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
			}
		}

		// Obtener datos del usuario actual para mantener el rol y estado
		$usuario_actual = $usuario->mostrar($id_usuario_logueado);
		$rol_id = $usuario_actual['rol_id'];
		$estado_usuario_id = $usuario_actual['estado_usuario_id'];

		// Hash de la nueva contraseña si se proporciona
		$clavehash = !empty($clave_nueva) ? hash("SHA256", $clave_nueva) : $usuario_actual['password'];

		// Actualizar usuario
		if (!empty($clave_nueva)) {
			$rspta = $usuario->editar($id_usuario_logueado, $nombre, $dui, $email, $clavehash, $rol_id, $telefono, $direccion, $estado_usuario_id, $fotografia);
		} else {
			$rspta = $usuario->editar_sin_password($id_usuario_logueado, $nombre, $dui, $email, $rol_id, $telefono, $direccion, $estado_usuario_id, $fotografia);
		}

		// Actualizar la sesión con la nueva imagen si se cambió
		if ($rspta && !empty($fotografia)) {
			$_SESSION['fotografia'] = $fotografia;
		}

		echo $rspta ? "Perfil actualizado correctamente" : "No se pudo actualizar el perfil";
	break;

	case 'listar_maestros':
		$rspta=$usuario->listar_maestros();
		//declaramos un array
		$data=Array();

		while ($reg=$rspta->fetch(PDO::FETCH_OBJ)) {
			$data[]=array(
				"0"=>$reg->id_usuario,
				"1"=>$reg->nombre_completo
				);
		}

		$results=array(
	            "sEcho"=>1,//info para datatables
	            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
	            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
	            "aaData"=>$data);
		echo json_encode($results);

	break;

	case 'listar_tutores':
		$rspta=$usuario->listar_tutores();
		//declaramos un array
		$data=Array();

		while ($reg=$rspta->fetch(PDO::FETCH_OBJ)) {
			$data[]=array(
				"0"=>$reg->id_usuario,
				"1"=>$reg->nombre_completo
				);
		}

		$results=array(
	            "sEcho"=>1,//info para datatables
	            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
	            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
	            "aaData"=>$data);
		echo json_encode($results);

	break;

	case 'salir':
		//Limpiamos las variables de sesión
	       session_unset();
	       //Destruìmos la sesión
	       session_destroy();
	       //Redireccionamos al landing
	       header("Location: ../views/landing.php");

	break;
}
?>