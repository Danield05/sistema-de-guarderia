<?php
// Configuración de la base de datos usando PDO
$host = 'localhost';
$dbname = 'sis_school'; // Nombre de la base de datos del sistema de guardería
$username = 'root'; // Usuario por defecto en XAMPP
$password = '1234'; // Contraseña por defecto en XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Clase PDO para consultas con protección contra redeclaración
if (!class_exists('Database')) {
    class Database {
        private static $instance = null;
        private $pdo;

        private function __construct() {
            $host = 'localhost';
            $dbname = 'sis_school';
            $username = 'root';
            $password = '1234';

            try {
                $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        public function getPDO() {
            return $this->pdo;
        }

        public function query($sql) {
            return $this->pdo->query($sql);
        }

        public function prepare($sql) {
            return $this->pdo->prepare($sql);
        }
    }
}

// Instancia global de la base de datos
$db = Database::getInstance();
$pdo = $db->getPDO();

// Funciones de compatibilidad para mantener el código existente funcionando
// con guards para evitar redeclaraciones
if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql) {
        global $pdo;
        try {
            $stmt = $pdo->query($sql);
            return $stmt;
        } catch (PDOException $e) {
            die("Error en consulta: " . $e->getMessage());
        }
    }
}

if (!function_exists('ejecutarConsultaSimpleFila')) {
    function ejecutarConsultaSimpleFila($sql) {
        global $pdo;
        try {
            $stmt = $pdo->query($sql);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Error en consulta: " . $e->getMessage());
        }
    }
}

if (!function_exists('ejecutarConsulta_retornarID')) {
    function ejecutarConsulta_retornarID($sql) {
        global $pdo;
        try {
            $pdo->exec($sql);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            die("Error en consulta: " . $e->getMessage());
        }
    }
}

if (!function_exists('limpiarCadena')) {
    function limpiarCadena($str) {
        $str = trim($str);
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
?>