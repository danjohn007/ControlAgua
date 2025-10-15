<?php
/**
 * Controlador de Pagos
 */

class PagoController {
    private $pagoModel;
    private $empresaModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::require();
        $this->pagoModel = new Pago();
        $this->empresaModel = new Empresa();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar pagos
     */
    public function index() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $empresaId = $_GET['empresa_id'] ?? null;
        
        if ($empresaId) {
            $pagos = $this->pagoModel->getByEmpresa($empresaId, $fechaInicio, $fechaFin);
            $empresa = $this->empresaModel->getById($empresaId);
        } else {
            $pagos = $this->pagoModel->getAllWithDetails(100);
        }
        
        $empresas = $this->empresaModel->getAll('razon_social', 'ASC');
        
        require APP_PATH . '/views/pagos/index.php';
    }

    /**
     * Registrar nuevo pago
     */
    public function registrar() {
        Auth::requireRole(['admin', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresaId = $_POST['empresa_id'] ?? 0;
            $monto = $_POST['monto'] ?? 0;
            
            $data = [
                'empresa_id' => $empresaId,
                'monto' => $monto,
                'tipo_pago' => $_POST['tipo_pago'] ?? 'normal',
                'metodo_pago' => $_POST['metodo_pago'] ?? 'efectivo',
                'referencia' => $_POST['referencia'] ?? null,
                'usuario_id' => $_SESSION['user_id'],
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $pagoId = $this->pagoModel->registrarPago($data);
            
            if ($pagoId) {
                $empresa = $this->empresaModel->getById($empresaId);
                
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'pagos',
                    $pagoId,
                    "Pago registrado: {$empresa['razon_social']} - $" . number_format($monto, 2)
                );
                
                $_SESSION['success'] = 'Pago registrado exitosamente. Nuevo saldo actualizado.';
                header('Location: ' . BASE_URL . 'pagos');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar pago';
                header('Location: ' . BASE_URL . 'pagos/registrar');
                exit;
            }
        }
        
        $empresas = $this->empresaModel->getActivas();
        require APP_PATH . '/views/pagos/registrar.php';
    }

    /**
     * Ver detalle de pago
     */
    public function ver($id) {
        $pago = $this->pagoModel->getById($id);
        
        if (!$pago) {
            $_SESSION['error'] = 'Pago no encontrado';
            header('Location: ' . BASE_URL . 'pagos');
            exit;
        }
        
        $empresa = $this->empresaModel->getById($pago['empresa_id']);
        
        require APP_PATH . '/views/pagos/ver.php';
    }

    /**
     * Reporte de pagos
     */
    public function reporte() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        $pagos = $this->pagoModel->getTotalByPeriodo($fechaInicio, $fechaFin);
        
        require APP_PATH . '/views/pagos/reporte.php';
    }
}
