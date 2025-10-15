<?php
/**
 * Controlador de Autenticación
 */

class Auth {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Iniciar sesión
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email y contraseña son requeridos';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $user = $this->usuarioModel->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['logged_in'] = true;
                
                // Registrar auditoría
                $auditoria = new Auditoria();
                $auditoria->registrar($user['id'], 'LOGIN', 'usuarios', $user['id'], 'Inicio de sesión exitoso');
                
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Credenciales inválidas';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
        
        // Mostrar formulario de login
        require APP_PATH . '/views/auth/login.php';
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $auditoria = new Auditoria();
            $auditoria->registrar($_SESSION['user_id'], 'LOGOUT', 'usuarios', $_SESSION['user_id'], 'Cierre de sesión');
        }
        
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    /**
     * Verificar si está autenticado
     */
    public static function check() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Verificar rol
     */
    public static function hasRole($role) {
        if (!self::check()) {
            return false;
        }
        
        if (is_array($role)) {
            return in_array($_SESSION['user_role'], $role);
        }
        
        return $_SESSION['user_role'] === $role;
    }

    /**
     * Requerir autenticación
     */
    public static function require() {
        if (!self::check()) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    /**
     * Requerir rol específico
     */
    public static function requireRole($role) {
        self::require();
        
        if (!self::hasRole($role)) {
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección';
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
    }
}
