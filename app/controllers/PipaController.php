<?php
/**
 * Controlador de Pipas
 */

class PipaController {
    private $pipaModel;
    private $empresaModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::require();
        $this->pipaModel = new Pipa();
        $this->empresaModel = new Empresa();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar pipas
     */
    public function index() {
        $buscar = $_GET['buscar'] ?? '';
        
        if (!empty($buscar)) {
            $pipas = $this->pipaModel->buscar($buscar);
        } else {
            $pipas = $this->pipaModel->getAllWithEmpresa();
        }
        
        $total = count($pipas);
        
        require APP_PATH . '/views/pipas/index.php';
    }

    /**
     * Crear pipa
     */
    public function crear() {
        Auth::requireRole(['admin', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresaId = $_POST['empresa_id'] ?? 0;
            
            $data = [
                'empresa_id' => $empresaId,
                'matricula' => strtoupper($_POST['matricula'] ?? ''),
                'capacidad_litros' => $_POST['capacidad_litros'] ?? 0,
                'operador_nombre' => $_POST['operador_nombre'] ?? '',
                'numero_serie' => strtoupper($_POST['numero_serie'] ?? ''),
                'codigo_qr' => $this->pipaModel->generateUniqueQR($empresaId),
                'estado' => $_POST['estado'] ?? 'activa'
            ];
            
            // Validar matrícula única
            if ($this->pipaModel->matriculaExists($data['matricula'])) {
                $_SESSION['error'] = 'La matrícula ya está registrada';
                $_SESSION['old_data'] = $data;
                header('Location: ' . BASE_URL . 'pipas/crear');
                exit;
            }
            
            $pipaId = $this->pipaModel->insert($data);
            
            if ($pipaId) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'pipas',
                    $pipaId,
                    "Nueva pipa registrada: {$data['matricula']}"
                );
                
                $_SESSION['success'] = 'Pipa creada exitosamente. Código QR: ' . $data['codigo_qr'];
                header('Location: ' . BASE_URL . 'pipas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear pipa';
                header('Location: ' . BASE_URL . 'pipas/crear');
                exit;
            }
        }
        
        $empresas = $this->empresaModel->getActivas();
        require APP_PATH . '/views/pipas/crear.php';
    }

    /**
     * Editar pipa
     */
    public function editar($id) {
        Auth::requireRole(['admin', 'supervisor']);
        
        $pipa = $this->pipaModel->getById($id);
        
        if (!$pipa) {
            $_SESSION['error'] = 'Pipa no encontrada';
            header('Location: ' . BASE_URL . 'pipas');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'empresa_id' => $_POST['empresa_id'] ?? 0,
                'matricula' => strtoupper($_POST['matricula'] ?? ''),
                'capacidad_litros' => $_POST['capacidad_litros'] ?? 0,
                'operador_nombre' => $_POST['operador_nombre'] ?? '',
                'numero_serie' => strtoupper($_POST['numero_serie'] ?? ''),
                'estado' => $_POST['estado'] ?? 'activa'
            ];
            
            // Validar matrícula única
            if ($this->pipaModel->matriculaExists($data['matricula'], $id)) {
                $_SESSION['error'] = 'La matrícula ya está registrada en otra pipa';
                header('Location: ' . BASE_URL . 'pipas/editar/' . $id);
                exit;
            }
            
            if ($this->pipaModel->update($id, $data)) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'UPDATE',
                    'pipas',
                    $id,
                    "Pipa actualizada: {$data['matricula']}"
                );
                
                $_SESSION['success'] = 'Pipa actualizada exitosamente';
                header('Location: ' . BASE_URL . 'pipas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar pipa';
                header('Location: ' . BASE_URL . 'pipas/editar/' . $id);
                exit;
            }
        }
        
        $empresas = $this->empresaModel->getActivas();
        require APP_PATH . '/views/pipas/editar.php';
    }

    /**
     * Ver detalle de pipa
     */
    public function ver($id) {
        $pipa = $this->pipaModel->getById($id);
        
        if (!$pipa) {
            $_SESSION['error'] = 'Pipa no encontrada';
            header('Location: ' . BASE_URL . 'pipas');
            exit;
        }
        
        $empresa = $this->empresaModel->getById($pipa['empresa_id']);
        $historialSuministros = $this->pipaModel->getHistorialSuministros($id, 20);
        
        $accesoModel = new Acceso();
        $historialAccesos = $accesoModel->getByPipa($id, 20);
        
        require APP_PATH . '/views/pipas/ver.php';
    }

    /**
     * Generar QR para pipa
     */
    public function generarQR($id) {
        $pipa = $this->pipaModel->getById($id);
        
        if (!$pipa) {
            $_SESSION['error'] = 'Pipa no encontrada';
            header('Location: ' . BASE_URL . 'pipas');
            exit;
        }
        
        require APP_PATH . '/views/pipas/qr.php';
    }
}
