<?php
/**
 * Controlador de Estaciones
 */

class EstacionController {
    private $estacionModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::requireRole('admin'); // Solo administradores
        $this->estacionModel = new Estacion();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar estaciones
     */
    public function index() {
        $estaciones = $this->estacionModel->getAll('nombre', 'ASC');
        require APP_PATH . '/views/estaciones/index.php';
    }

    /**
     * Crear estación
     */
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'ubicacion' => $_POST['ubicacion'] ?? '',
                'capacidad_diaria' => $_POST['capacidad_diaria'] ?? 100000,
                'activa' => $_POST['activa'] ?? 1
            ];
            
            $estacionId = $this->estacionModel->insert($data);
            
            if ($estacionId) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'estaciones',
                    $estacionId,
                    "Nueva estación creada: {$data['nombre']}"
                );
                
                $_SESSION['success'] = 'Estación creada exitosamente';
                header('Location: ' . BASE_URL . 'estaciones');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear estación';
                header('Location: ' . BASE_URL . 'estaciones/crear');
                exit;
            }
        }
        
        require APP_PATH . '/views/estaciones/crear.php';
    }

    /**
     * Editar estación
     */
    public function editar($id) {
        $estacion = $this->estacionModel->getById($id);
        
        if (!$estacion) {
            $_SESSION['error'] = 'Estación no encontrada';
            header('Location: ' . BASE_URL . 'estaciones');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'ubicacion' => $_POST['ubicacion'] ?? '',
                'capacidad_diaria' => $_POST['capacidad_diaria'] ?? 100000,
                'activa' => $_POST['activa'] ?? 1
            ];
            
            if ($this->estacionModel->update($id, $data)) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'UPDATE',
                    'estaciones',
                    $id,
                    "Estación actualizada: {$data['nombre']}"
                );
                
                $_SESSION['success'] = 'Estación actualizada exitosamente';
                header('Location: ' . BASE_URL . 'estaciones');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar estación';
                header('Location: ' . BASE_URL . 'estaciones/editar/' . $id);
                exit;
            }
        }
        
        require APP_PATH . '/views/estaciones/editar.php';
    }

    /**
     * Ver detalles de estación
     */
    public function ver($id) {
        $estacion = $this->estacionModel->getById($id);
        
        if (!$estacion) {
            $_SESSION['error'] = 'Estación no encontrada';
            header('Location: ' . BASE_URL . 'estaciones');
            exit;
        }
        
        // Obtener estadísticas
        $estadisticas = $this->estacionModel->getEstadisticas($id);
        
        // Obtener accesos recientes
        $accesoModel = new Acceso();
        $accesos = $accesoModel->getByEstacion($id);
        
        // Obtener suministros recientes
        $suministroModel = new Suministro();
        $sql = "SELECT s.*, p.matricula as pipa_matricula, e.razon_social as empresa_nombre
                FROM suministros s
                INNER JOIN pipas p ON s.pipa_id = p.id
                INNER JOIN empresas e ON s.empresa_id = e.id
                WHERE s.estacion_id = ?
                ORDER BY s.fecha_hora DESC
                LIMIT 20";
        $db = Database::getInstance();
        $suministros = $db->query($sql, [$id]);
        
        require APP_PATH . '/views/estaciones/ver.php';
    }

    /**
     * Toggle estado de estación
     */
    public function toggleEstado($id) {
        $estacion = $this->estacionModel->getById($id);
        
        if (!$estacion) {
            $_SESSION['error'] = 'Estación no encontrada';
            header('Location: ' . BASE_URL . 'estaciones');
            exit;
        }
        
        $nuevoEstado = $estacion['activa'] ? 0 : 1;
        
        if ($this->estacionModel->update($id, ['activa' => $nuevoEstado])) {
            $estado = $nuevoEstado ? 'activada' : 'desactivada';
            
            $this->auditoriaModel->registrar(
                $_SESSION['user_id'],
                'UPDATE',
                'estaciones',
                $id,
                "Estación {$estado}: {$estacion['nombre']}"
            );
            
            $_SESSION['success'] = "Estación {$estado} exitosamente";
        } else {
            $_SESSION['error'] = 'Error al cambiar estado de la estación';
        }
        
        header('Location: ' . BASE_URL . 'estaciones');
        exit;
    }
}
