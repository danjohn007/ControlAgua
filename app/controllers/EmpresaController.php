<?php
/**
 * Controlador de Empresas
 */

class EmpresaController {
    private $empresaModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::require();
        $this->empresaModel = new Empresa();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar empresas
     */
    public function index() {
        $page = $_GET['page'] ?? 1;
        $buscar = $_GET['buscar'] ?? '';
        
        if (!empty($buscar)) {
            $empresas = $this->empresaModel->buscar($buscar);
            $total = count($empresas);
        } else {
            $empresas = $this->empresaModel->getAll('razon_social', 'ASC');
            $total = $this->empresaModel->count();
        }
        
        require APP_PATH . '/views/empresas/index.php';
    }

    /**
     * Crear empresa
     */
    public function crear() {
        Auth::requireRole(['admin', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'razon_social' => $_POST['razon_social'] ?? '',
                'rfc' => strtoupper($_POST['rfc'] ?? ''),
                'direccion_fiscal' => $_POST['direccion_fiscal'] ?? '',
                'representante_legal' => $_POST['representante_legal'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'estado' => $_POST['estado'] ?? 'activa',
                'credito_autorizado' => $_POST['credito_autorizado'] ?? 0
            ];
            
            // Validar RFC único
            if ($this->empresaModel->rfcExists($data['rfc'])) {
                $_SESSION['error'] = 'El RFC ya está registrado';
                $_SESSION['old_data'] = $data;
                header('Location: ' . BASE_URL . 'empresas/crear');
                exit;
            }
            
            $empresaId = $this->empresaModel->insert($data);
            
            if ($empresaId) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'empresas',
                    $empresaId,
                    "Nueva empresa registrada: {$data['razon_social']}"
                );
                
                $_SESSION['success'] = 'Empresa creada exitosamente';
                header('Location: ' . BASE_URL . 'empresas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear empresa';
                header('Location: ' . BASE_URL . 'empresas/crear');
                exit;
            }
        }
        
        require APP_PATH . '/views/empresas/crear.php';
    }

    /**
     * Editar empresa
     */
    public function editar($id) {
        Auth::requireRole(['admin', 'supervisor']);
        
        $empresa = $this->empresaModel->getById($id);
        
        if (!$empresa) {
            $_SESSION['error'] = 'Empresa no encontrada';
            header('Location: ' . BASE_URL . 'empresas');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'razon_social' => $_POST['razon_social'] ?? '',
                'rfc' => strtoupper($_POST['rfc'] ?? ''),
                'direccion_fiscal' => $_POST['direccion_fiscal'] ?? '',
                'representante_legal' => $_POST['representante_legal'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'estado' => $_POST['estado'] ?? 'activa',
                'credito_autorizado' => $_POST['credito_autorizado'] ?? 0
            ];
            
            // Validar RFC único
            if ($this->empresaModel->rfcExists($data['rfc'], $id)) {
                $_SESSION['error'] = 'El RFC ya está registrado en otra empresa';
                header('Location: ' . BASE_URL . 'empresas/editar/' . $id);
                exit;
            }
            
            if ($this->empresaModel->update($id, $data)) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'UPDATE',
                    'empresas',
                    $id,
                    "Empresa actualizada: {$data['razon_social']}"
                );
                
                $_SESSION['success'] = 'Empresa actualizada exitosamente';
                header('Location: ' . BASE_URL . 'empresas');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar empresa';
                header('Location: ' . BASE_URL . 'empresas/editar/' . $id);
                exit;
            }
        }
        
        require APP_PATH . '/views/empresas/editar.php';
    }

    /**
     * Ver detalle de empresa
     */
    public function ver($id) {
        $empresa = $this->empresaModel->getById($id);
        
        if (!$empresa) {
            $_SESSION['error'] = 'Empresa no encontrada';
            header('Location: ' . BASE_URL . 'empresas');
            exit;
        }
        
        // Obtener estadísticas
        $estadisticas = $this->empresaModel->getEstadisticas($id);
        
        // Obtener pipas de la empresa
        $pipaModel = new Pipa();
        $pipas = $pipaModel->getByEmpresa($id);
        
        // Obtener suministros recientes
        $suministroModel = new Suministro();
        $suministros = $suministroModel->getByEmpresa($id);
        
        // Obtener pagos
        $pagoModel = new Pago();
        $pagos = $pagoModel->getByEmpresa($id);
        
        require APP_PATH . '/views/empresas/ver.php';
    }

    /**
     * Eliminar empresa
     */
    public function eliminar($id) {
        Auth::requireRole('admin');
        
        $empresa = $this->empresaModel->getById($id);
        
        if (!$empresa) {
            $_SESSION['error'] = 'Empresa no encontrada';
            header('Location: ' . BASE_URL . 'empresas');
            exit;
        }
        
        if ($this->empresaModel->delete($id)) {
            $this->auditoriaModel->registrar(
                $_SESSION['user_id'],
                'DELETE',
                'empresas',
                $id,
                "Empresa eliminada: {$empresa['razon_social']}"
            );
            
            $_SESSION['success'] = 'Empresa eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar empresa';
        }
        
        header('Location: ' . BASE_URL . 'empresas');
        exit;
    }
}
