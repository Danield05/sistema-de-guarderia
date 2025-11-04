<?php
require_once "config/Conexion.php";

// Función para ejecutar consultas SQL
function ejecutarQuery($sql) {
    global $conexion;
    $resultado = $conexion->query($sql);
    if (!$resultado) {
        die("Error ejecutando consulta: " . $conexion->error . "\nConsulta: " . $sql . "\n");
    }
    return $resultado;
}

echo "Iniciando configuración de la base de datos...\n\n";

// Crear base de datos si no existe
echo "1. Creando base de datos...\n";
$sql = "CREATE DATABASE IF NOT EXISTS sis_school CHARACTER SET utf8 COLLATE utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Base de datos 'sis_school' creada/verificada\n\n";

// Seleccionar la base de datos
$conexion->select_db("sis_school");

// Crear tabla permiso
echo "2. Creando tabla permiso...\n";
$sql = "CREATE TABLE IF NOT EXISTS permiso (
    idpermiso int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(30) NOT NULL,
    PRIMARY KEY (idpermiso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
ejecutarQuery($sql);
echo "   ✓ Tabla permiso creada\n";

// Insertar permisos básicos
$sql = "INSERT IGNORE INTO permiso (idpermiso, nombre) VALUES
(1, 'Escritorio'),
(2, 'Grupos'),
(3, 'Acceso')";
ejecutarQuery($sql);
echo "   ✓ Permisos básicos insertados\n\n";

// Crear tabla usuario
echo "3. Creando tabla usuario...\n";
$sql = "CREATE TABLE IF NOT EXISTS usuario (
    idusuario int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(100) NOT NULL,
    tipo_documento varchar(20) NOT NULL,
    num_documento varchar(20) NOT NULL,
    direccion varchar(70) DEFAULT NULL,
    telefono varchar(20) DEFAULT NULL,
    email varchar(50) DEFAULT NULL,
    cargo varchar(20) DEFAULT NULL,
    login varchar(20) NOT NULL,
    clave varchar(64) NOT NULL,
    imagen varchar(50) NOT NULL,
    condicion tinyint(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (idusuario),
    UNIQUE KEY login_UNIQUE (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
ejecutarQuery($sql);
echo "   ✓ Tabla usuario creada\n";

// Insertar usuario administrador
$clave_hash = hash("SHA256", "admin");
$sql = "INSERT IGNORE INTO usuario (idusuario, nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion) VALUES
(1, 'Administrador', 'DNI', '12345678', 'Dirección Principal', '123456789', 'admin@guarderia.com', 'Administrador', 'admin', '$clave_hash', 'admin.jpg', 1)";
ejecutarQuery($sql);
echo "   ✓ Usuario administrador creado (login: admin, password: admin)\n\n";

// Crear tabla usuario_permiso
echo "4. Creando tabla usuario_permiso...\n";
$sql = "CREATE TABLE IF NOT EXISTS usuario_permiso (
    idusuario_permiso int(11) NOT NULL AUTO_INCREMENT,
    idusuario int(11) NOT NULL,
    idpermiso int(11) NOT NULL,
    PRIMARY KEY (idusuario_permiso),
    KEY fk_u_permiso_usuario_idx (idusuario),
    KEY fk_usuario_permiso_idx (idpermiso),
    CONSTRAINT fk_u_permiso_usuario FOREIGN KEY (idusuario) REFERENCES usuario (idusuario) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT fk_usuario_permiso FOREIGN KEY (idpermiso) REFERENCES permiso (idpermiso) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
ejecutarQuery($sql);
echo "   ✓ Tabla usuario_permiso creada\n";

// Asignar permisos al administrador
$sql = "INSERT IGNORE INTO usuario_permiso (idusuario_permiso, idusuario, idpermiso) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3)";
ejecutarQuery($sql);
echo "   ✓ Permisos asignados al administrador\n\n";

// Crear tabla team (grupos)
echo "5. Creando tabla team (grupos)...\n";
$sql = "CREATE TABLE IF NOT EXISTS team (
    idgrupo int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(50) COLLATE utf8_bin NOT NULL,
    favorito tinyint(1) NOT NULL,
    idusuario int(11) NOT NULL,
    PRIMARY KEY (idgrupo),
    KEY team_ibfk_1 (idusuario),
    CONSTRAINT team_ibfk_1 FOREIGN KEY (idusuario) REFERENCES usuario (idusuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla team creada\n";

// Insertar grupos de ejemplo
$sql = "INSERT IGNORE INTO team (idgrupo, nombre, favorito, idusuario) VALUES
(1, 'MATERNAL 1', 1, 1),
(2, 'MATERNAL 2', 1, 1),
(3, 'PREESCOLAR 1', 1, 1),
(4, 'PREESCOLAR 2', 1, 1),
(5, 'PRIMERO DE PRIMARIA', 1, 1),
(6, 'SEGUNDO DE PRIMARIA', 1, 1)";
ejecutarQuery($sql);
echo "   ✓ Grupos de ejemplo insertados\n\n";

// Crear tabla alumn
echo "6. Creando tabla alumn...\n";
$sql = "CREATE TABLE IF NOT EXISTS alumn (
    id int(11) NOT NULL AUTO_INCREMENT,
    image varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'anonymous.png',
    name varchar(50) COLLATE utf8_bin NOT NULL,
    lastname varchar(50) COLLATE utf8_bin NOT NULL,
    email varchar(255) COLLATE utf8_bin NOT NULL,
    address varchar(60) COLLATE utf8_bin NOT NULL,
    phone varchar(60) COLLATE utf8_bin NOT NULL,
    c1_fullname varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c1_address varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c1_phone varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c1_note varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c2_fullname varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c2_address varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c2_phone varchar(100) COLLATE utf8_bin DEFAULT NULL,
    c2_note varchar(100) COLLATE utf8_bin DEFAULT NULL,
    is_active tinyint(1) NOT NULL DEFAULT 1,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    user_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    CONSTRAINT alumn_ibfk_1 FOREIGN KEY (user_id) REFERENCES usuario (idusuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla alumn creada\n\n";

// Crear tabla alumn_team
echo "7. Creando tabla alumn_team...\n";
$sql = "CREATE TABLE IF NOT EXISTS alumn_team (
    id int(11) NOT NULL AUTO_INCREMENT,
    alumn_id int(11) NOT NULL,
    team_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY alumn_id (alumn_id),
    KEY team_id (team_id),
    CONSTRAINT alumn_team_ibfk_1 FOREIGN KEY (alumn_id) REFERENCES alumn (id),
    CONSTRAINT alumn_team_ibfk_2 FOREIGN KEY (team_id) REFERENCES team (idgrupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla alumn_team creada\n\n";

// Crear tabla block (cursos/materias)
echo "8. Creando tabla block (cursos)...\n";
$sql = "CREATE TABLE IF NOT EXISTS block (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) COLLATE utf8_bin DEFAULT NULL,
    team_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY team_id (team_id),
    CONSTRAINT block_ibfk_1 FOREIGN KEY (team_id) REFERENCES team (idgrupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla block creada\n\n";

// Crear tabla calification
echo "9. Creando tabla calification...\n";
$sql = "CREATE TABLE IF NOT EXISTS calification (
    id int(11) NOT NULL AUTO_INCREMENT,
    val double DEFAULT NULL,
    alumn_id int(11) NOT NULL,
    block_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY alumn_id (alumn_id),
    KEY block_id (block_id),
    CONSTRAINT calification_ibfk_1 FOREIGN KEY (alumn_id) REFERENCES alumn (id),
    CONSTRAINT calification_ibfk_2 FOREIGN KEY (block_id) REFERENCES block (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla calification creada\n\n";

// Crear tabla assistance
echo "10. Creando tabla assistance...\n";
$sql = "CREATE TABLE IF NOT EXISTS assistance (
    id int(11) NOT NULL AUTO_INCREMENT,
    kind_id int(11) DEFAULT NULL,
    date_at date NOT NULL,
    alumn_id int(11) NOT NULL,
    team_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY alumn_id (alumn_id),
    KEY team_id (team_id),
    CONSTRAINT assistance_ibfk_1 FOREIGN KEY (alumn_id) REFERENCES alumn (id),
    CONSTRAINT assistance_ibfk_2 FOREIGN KEY (team_id) REFERENCES team (idgrupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla assistance creada\n\n";

// Crear tabla behavior
echo "11. Creando tabla behavior...\n";
$sql = "CREATE TABLE IF NOT EXISTS behavior (
    id int(11) NOT NULL AUTO_INCREMENT,
    kind_id int(11) DEFAULT NULL,
    date_at date NOT NULL,
    alumn_id int(11) NOT NULL,
    team_id int(11) NOT NULL,
    PRIMARY KEY (id),
    KEY alumn_id (alumn_id),
    KEY team_id (team_id),
    CONSTRAINT behavior_ibfk_1 FOREIGN KEY (alumn_id) REFERENCES alumn (id),
    CONSTRAINT behavior_ibfk_2 FOREIGN KEY (team_id) REFERENCES team (idgrupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
ejecutarQuery($sql);
echo "   ✓ Tabla behavior creada\n\n";

echo "\n🎉 ¡Configuración completada exitosamente!\n\n";
echo "Datos de acceso:\n";
echo "Usuario: admin\n";
echo "Contraseña: admin\n\n";
echo "Puedes acceder al sistema en: http://localhost/sistema-de-guarderia/\n\n";

// Crear directorios necesarios
echo "Creando directorios necesarios...\n";
$directorios = ['files/usuarios', 'files/articulos', 'public/css', 'public/js', 'public/img'];

foreach ($directorios as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "   ✓ Directorio $dir creado\n";
    } else {
        echo "   ✓ Directorio $dir ya existe\n";
    }
}

echo "\n✅ Setup completado. El sistema está listo para usar.\n";
?>