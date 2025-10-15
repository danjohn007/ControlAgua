<?php
/**
 * Test de Conexión a Base de Datos
 */

// Configurar sesión ANTES de iniciarla
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usa HTTPS

session_start();
require_once __DIR__ . '/config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
            max-width: 600px;
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-card">
            <h2 class="text-center mb-4">Test de Conexión a Base de Datos</h2>
            
            <?php
            $dbConfig = require CONFIG_PATH . '/database.php';
            
            try {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $options);
                
                echo '<div class="text-center">';
                echo '<div class="status-icon success">✓</div>';
                echo '<h3 class="text-success">Conexión Exitosa</h3>';
                echo '</div>';
                
                echo '<div class="mt-4">';
                echo '<h5>Detalles de la Conexión:</h5>';
                echo '<ul class="list-group">';
                echo '<li class="list-group-item"><strong>Host:</strong> ' . $dbConfig['host'] . '</li>';
                echo '<li class="list-group-item"><strong>Puerto:</strong> ' . $dbConfig['port'] . '</li>';
                echo '<li class="list-group-item"><strong>Base de Datos:</strong> ' . $dbConfig['database'] . '</li>';
                echo '<li class="list-group-item"><strong>Usuario:</strong> ' . $dbConfig['username'] . '</li>';
                echo '<li class="list-group-item"><strong>Charset:</strong> ' . $dbConfig['charset'] . '</li>';
                echo '</ul>';
                echo '</div>';
                
                // Verificar tablas
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (count($tables) > 0) {
                    echo '<div class="mt-4">';
                    echo '<h5>Tablas Encontradas (' . count($tables) . '):</h5>';
                    echo '<div class="alert alert-success">';
                    echo implode(', ', $tables);
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="mt-4">';
                    echo '<div class="alert alert-warning">';
                    echo 'No se encontraron tablas. Por favor, ejecute el archivo sql/schema.sql';
                    echo '</div>';
                    echo '</div>';
                }
                
            } catch (PDOException $e) {
                echo '<div class="text-center">';
                echo '<div class="status-icon error">✗</div>';
                echo '<h3 class="text-danger">Error de Conexión</h3>';
                echo '</div>';
                
                echo '<div class="mt-4">';
                echo '<div class="alert alert-danger">';
                echo '<strong>Error:</strong> ' . $e->getMessage();
                echo '</div>';
                echo '</div>';
                
                echo '<div class="mt-3">';
                echo '<h5>Configuración Actual:</h5>';
                echo '<ul class="list-group">';
                echo '<li class="list-group-item"><strong>Host:</strong> ' . $dbConfig['host'] . '</li>';
                echo '<li class="list-group-item"><strong>Puerto:</strong> ' . $dbConfig['port'] . '</li>';
                echo '<li class="list-group-item"><strong>Base de Datos:</strong> ' . $dbConfig['database'] . '</li>';
                echo '<li class="list-group-item"><strong>Usuario:</strong> ' . $dbConfig['username'] . '</li>';
                echo '</ul>';
                echo '</div>';
            }
            ?>
            
            <div class="mt-4 text-center">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Ir al Sistema</a>
                <a href="<?php echo BASE_URL; ?>test-url" class="btn btn-secondary">Test URL Base</a>
            </div>
        </div>
    </div>
</body>
</html>
