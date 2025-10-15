<?php
/**
 * Test de URL Base y Configuración del Sistema
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
    <title>Test URL Base - <?php echo APP_NAME; ?></title>
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
            max-width: 700px;
        }
        .info-icon {
            font-size: 4rem;
            color: #007bff;
            margin-bottom: 20px;
        }
        .code-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            font-family: monospace;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-card">
            <div class="text-center">
                <div class="info-icon">⚙️</div>
                <h2 class="mb-4">Test de URL Base y Configuración</h2>
            </div>
            
            <div class="mt-4">
                <h5>Información del Sistema:</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Nombre de la Aplicación:</th>
                        <td><?php echo APP_NAME; ?></td>
                    </tr>
                    <tr>
                        <th>Versión:</th>
                        <td><?php echo APP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th>URL Base:</th>
                        <td><code><?php echo BASE_URL; ?></code></td>
                    </tr>
                    <tr>
                        <th>Zona Horaria:</th>
                        <td><?php echo date_default_timezone_get(); ?></td>
                    </tr>
                    <tr>
                        <th>Hora del Servidor:</th>
                        <td><?php echo date('Y-m-d H:i:s'); ?></td>
                    </tr>
                </table>
            </div>

            <div class="mt-4">
                <h5>Rutas del Sistema:</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>ROOT_PATH:</th>
                        <td><code><?php echo ROOT_PATH; ?></code></td>
                    </tr>
                    <tr>
                        <th>APP_PATH:</th>
                        <td><code><?php echo APP_PATH; ?></code></td>
                    </tr>
                    <tr>
                        <th>CONFIG_PATH:</th>
                        <td><code><?php echo CONFIG_PATH; ?></code></td>
                    </tr>
                    <tr>
                        <th>PUBLIC_PATH:</th>
                        <td><code><?php echo PUBLIC_PATH; ?></code></td>
                    </tr>
                </table>
            </div>

            <div class="mt-4">
                <h5>Información del Servidor:</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>PHP Version:</th>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th>Servidor:</th>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido'; ?></td>
                    </tr>
                    <tr>
                        <th>Host:</th>
                        <td><?php echo $_SERVER['HTTP_HOST']; ?></td>
                    </tr>
                    <tr>
                        <th>Protocolo:</th>
                        <td><?php echo (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'HTTPS' : 'HTTP'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="mt-4">
                <h5>Verificación de Módulos PHP:</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        PDO (MySQL)
                        <?php if (extension_loaded('pdo_mysql')): ?>
                            <span class="badge bg-success">✓ Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ No disponible</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        mbstring
                        <?php if (extension_loaded('mbstring')): ?>
                            <span class="badge bg-success">✓ Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ No disponible</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Session
                        <?php if (extension_loaded('session')): ?>
                            <span class="badge bg-success">✓ Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ No disponible</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>

            <div class="mt-4">
                <h5>Enlaces de Prueba:</h5>
                <div class="list-group">
                    <a href="<?php echo BASE_URL; ?>" class="list-group-item list-group-item-action">
                        Página Principal (Login)
                    </a>
                    <a href="<?php echo BASE_URL; ?>test-connection" class="list-group-item list-group-item-action">
                        Test de Conexión a BD
                    </a>
                    <a href="<?php echo BASE_URL; ?>dashboard" class="list-group-item list-group-item-action">
                        Dashboard (requiere login)
                    </a>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Ir al Sistema</a>
                <a href="<?php echo BASE_URL; ?>test-connection" class="btn btn-success">Test Conexión BD</a>
            </div>
        </div>
    </div>
</body>
</html>
