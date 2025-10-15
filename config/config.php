<?php
/**
 * Configuración General del Sistema
 * Sistema de Control de Suministro de Agua a Pipas
 */

// Detectar URL base automáticamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . $host . $script_path);

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usa HTTPS

// Configuración de errores (cambiar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Control de Suministro de Agua');
define('APP_VERSION', '1.0.0');
define('APP_CHARSET', 'UTF-8');

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// Configuración de paginación
define('ITEMS_PER_PAGE', 10);

// Configuración de tarifas por defecto (pesos por litro)
define('DEFAULT_TARIFF', 0.50);

return [
    'app_name' => APP_NAME,
    'app_version' => APP_VERSION,
    'base_url' => BASE_URL,
    'timezone' => 'America/Mexico_City'
];
