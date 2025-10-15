<?php
/**
 * Controlador de Usuarios
 */

class UsuarioController {
    private $usuarioModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::requireRole('admin'); // Solo administradores
        $this->usuarioModel = new Usuario();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar usuarios
     */
    public function index() {
        $usuarios = $this->usuarioModel->getAll('nombre', 'ASC');
        require APP_PATH . '/views/usuarios/index.php';
    }

    /**
     * Crear usuario
     */
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'rol' => $_POST['rol'] ?? 'operador',
                'activo' => $_POST['activo'] ?? 1
            ];
            
            // Validar email único
            if ($this->usuarioModel->emailExists($data['email'])) {
                $_SESSION['error'] = 'El email ya está registrado';
                $_SESSION['old_data'] = $data;
                header('Location: ' . BASE_URL . 'usuarios/crear');
                exit;
            }
            
            $usuarioId = $this->usuarioModel->createUser($data);
            
            if ($usuarioId) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'usuarios',
                    $usuarioId,
                    "Nuevo usuario creado: {$data['nombre']} ({$data['email']})"
                );
                
                $_SESSION['success'] = 'Usuario creado exitosamente';
                header('Location: ' . BASE_URL . 'usuarios');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear usuario';
                header('Location: ' . BASE_URL . 'usuarios/crear');
                exit;
            }
        }
        
        require APP_PATH . '/views/usuarios/crear.php';
    }

    /**
     * Editar usuario
     */
    public function editar($id) {
        $usuario = $this->usuarioModel->getById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'rol' => $_POST['rol'] ?? 'operador',
                'activo' => $_POST['activo'] ?? 1
            ];
            
            // Validar email único
            if ($this->usuarioModel->emailExists($data['email'], $id)) {
                $_SESSION['error'] = 'El email ya está registrado en otro usuario';
                header('Location: ' . BASE_URL . 'usuarios/editar/' . $id);
                exit;
            }
            
            // Cambiar contraseña solo si se proporciona
            if (!empty($_POST['password'])) {
                $this->usuarioModel->changePassword($id, $_POST['password']);
            }
            
            if ($this->usuarioModel->update($id, $data)) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'UPDATE',
                    'usuarios',
                    $id,
                    "Usuario actualizado: {$data['nombre']}"
                );
                
                $_SESSION['success'] = 'Usuario actualizado exitosamente';
                header('Location: ' . BASE_URL . 'usuarios');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar usuario';
                header('Location: ' . BASE_URL . 'usuarios/editar/' . $id);
                exit;
            }
        }
        
        require APP_PATH . '/views/usuarios/editar.php';
    }

    /**
     * Cambiar estado de usuario
     */
    public function toggleEstado($id) {
        $usuario = $this->usuarioModel->getById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: ' . BASE_URL . 'usuarios');
            exit;
        }
        
        $nuevoEstado = $usuario['activo'] ? 0 : 1;
        
        if ($this->usuarioModel->update($id, ['activo' => $nuevoEstado])) {
            $estado = $nuevoEstado ? 'activado' : 'desactivado';
            
            $this->auditoriaModel->registrar(
                $_SESSION['user_id'],
                'UPDATE',
                'usuarios',
                $id,
                "Usuario {$estado}: {$usuario['nombre']}"
            );
            
            $_SESSION['success'] = "Usuario {$estado} exitosamente";
        } else {
            $_SESSION['error'] = 'Error al cambiar estado del usuario';
        }
        
        header('Location: ' . BASE_URL . 'usuarios');
        exit;
    }
}
