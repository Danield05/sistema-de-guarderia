<?php
// Configuración general de la aplicación

// URL base de la aplicación (detecta automáticamente el puerto del servidor)
define('BASE_URL', 'http://localhost' . (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '') . '/sistema-de-guarderia');

// Configuración de la aplicación
define("PRO_NOMBRE", "Sistema de Guardería");
define("PRO_VERSION", "1.0.0");

// Configuración de zona horaria
date_default_timezone_set('America/El_Salvador');

// Configuración de errores (deshabilitados en producción)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

?>