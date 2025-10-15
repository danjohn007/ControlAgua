<?php
/**
 * Index Principal - Sistema de Control de Suministro de Agua
 * Punto de entrada de la aplicación
 */

// Configurar sesión ANTES de iniciarla
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usa HTTPS

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Cargar clase Database
require_once APP_PATH . '/models/Database.php';

// Cargar clase base Model
require_once APP_PATH . '/models/Model.php';

// Cargar modelos
require_once APP_PATH . '/models/Usuario.php';
require_once APP_PATH . '/models/Empresa.php';
require_once APP_PATH . '/models/Pipa.php';
require_once APP_PATH . '/models/Suministro.php';
require_once APP_PATH . '/models/Estacion.php';
require_once APP_PATH . '/models/Acceso.php';
require_once APP_PATH . '/models/Pago.php';
require_once APP_PATH . '/models/Auditoria.php';

// Cargar controladores
require_once APP_PATH . '/controllers/Auth.php';

// Obtener URL solicitada
$url = $_GET['url'] ?? 'login';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determinar controlador y método
$controllerName = !empty($url[0]) ? ucfirst($url[0]) : 'Auth';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// Rutas públicas que no requieren autenticación
$publicRoutes = ['login', 'test-connection', 'test-url'];

// Ruta especial para login
if ($url[0] === 'login' || $url[0] === '') {
    $auth = new Auth();
    if ($method === 'index' || $method === 'login') {
        $auth->login();
    } else {
        $auth->logout();
    }
    exit;
}

// Ruta para logout
if ($url[0] === 'logout') {
    $auth = new Auth();
    $auth->logout();
    exit;
}

// Verificar si es una ruta de prueba
if ($url[0] === 'test-connection') {
    require __DIR__ . '/test-connection.php';
    exit;
}

if ($url[0] === 'test-url') {
    require __DIR__ . '/test-url.php';
    exit;
}

// Para todas las demás rutas, verificar autenticación
Auth::require();

// Mapeo de controladores
$controllerMap = [
    'dashboard' => 'DashboardController',
    'empresas' => 'EmpresaController',
    'pipas' => 'PipaController',
    'suministros' => 'SuministroController',
    'accesos' => 'AccesoController',
    'pagos' => 'PagoController',
    'reportes' => 'ReporteController',
    'usuarios' => 'UsuarioController',
    'estaciones' => 'EstacionController'
];

$controllerClass = $controllerMap[$url[0]] ?? null;

if ($controllerClass) {
    $controllerFile = APP_PATH . '/controllers/' . $controllerClass . '.php';
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerClass();
        
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            // Método no encontrado
            http_response_code(404);
            echo "Método no encontrado";
        }
    } else {
        // Controlador no encontrado
        http_response_code(404);
        echo "Controlador no encontrado";
    }
} else {
    // Ruta no válida
    http_response_code(404);
    echo "Página no encontrada";
}
