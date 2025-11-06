<?php
session_start();

// Verificar si hay datos de niños en la base de datos
require_once "config/Conexion.php";

echo "<h2>🔍 Prueba del Sistema de Niños</h2>";
echo "<hr>";

try {
    // Verificar conexión
    echo "<p>✅ <strong>Conexión a la base de datos:</strong> Exitosa</p>";
    
    // Verificar si existen las tablas
    $tables = ['ninos', 'aulas', 'secciones', 'usuarios'];
    foreach ($tables as $table) {
        $sql = "SHOW TABLES LIKE '$table'";
        $result = ejecutarConsulta($sql);
        $exists = $result->rowCount() > 0;
        echo "<p>" . ($exists ? "✅" : "❌") . " <strong>Tabla '$table':</strong> " . ($exists ? "Existe" : "No existe") . "</p>";
    }
    
    // Contar registros en cada tabla
    echo "<h3>📊 Contador de Registros:</h3>";
    $sql_ninos = "SELECT COUNT(*) as total FROM ninos";
    $sql_aulas = "SELECT COUNT(*) as total FROM aulas";
    $sql_secciones = "SELECT COUNT(*) as total FROM secciones";
    $sql_usuarios = "SELECT COUNT(*) as total FROM usuarios";
    
    $result_ninos = ejecutarConsulta($sql_ninos);
    $result_aulas = ejecutarConsulta($sql_aulas);
    $result_secciones = ejecutarConsulta($sql_secciones);
    $result_usuarios = ejecutarConsulta($sql_usuarios);
    
    $total_ninos = $result_ninos->fetch()['total'];
    $total_aulas = $result_aulas->fetch()['total'];
    $total_secciones = $result_secciones->fetch()['total'];
    $total_usuarios = $result_usuarios->fetch()['total'];
    
    echo "<p>👶 <strong>Niños:</strong> $total_ninos registros</p>";
    echo "<p>🏫 <strong>Aulas:</strong> $total_aulas registros</p>";
    echo "<p>📚 <strong>Secciones:</strong> $total_secciones registros</p>";
    echo "<p>👥 <strong>Usuarios:</strong> $total_usuarios registros</p>";
    
    if ($total_ninos > 0) {
        echo "<h3>📋 Primeros 5 niños:</h3>";
        $sql_lista = "SELECT n.id_nino, n.nombre_completo, n.edad, a.nombre_aula, s.nombre_seccion 
                      FROM ninos n 
                      LEFT JOIN aulas a ON n.aula_id = a.id_aula 
                      LEFT JOIN secciones s ON n.seccion_id = s.id_seccion 
                      ORDER BY n.id_nino LIMIT 5";
        
        $result_lista = ejecutarConsulta($sql_lista);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Nombre</th><th>Edad</th><th>Aula</th><th>Sección</th></tr>";
        
        while ($row = $result_lista->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id_nino'] . "</td>";
            echo "<td>" . $row['nombre_completo'] . "</td>";
            echo "<td>" . $row['edad'] . "</td>";
            echo "<td>" . ($row['nombre_aula'] ?? 'N/A') . "</td>";
            echo "<td>" . ($row['nombre_seccion'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ <strong>No hay niños registrados en la base de datos</strong></p>";
        echo "<p><strong>Para agregar datos de prueba, ejecuta el script SQL:</strong></p>";
        echo "<pre>INSERT INTO ninos (nombre_completo, fecha_nacimiento, edad, peso, aula_id, seccion_id, maestro_id, tutor_id) VALUES ('Carlos López', '2021-06-12', 4, 16.5, 1, 1, 2, 1);</pre>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p>🧪 <strong>Para probar el AJAX, verifica en el navegador:</strong></p>";
echo "<p>→ Abre las herramientas de desarrollador (F12)</p>";
echo "<p>→ Ve a la pestaña Console</p>";
echo "<p>→ Recarga la página http://localhost/sistema-de-guarderia/views/ninos.php</p>";
echo "<p>→ Busca errores JavaScript o de red</p>";
?>