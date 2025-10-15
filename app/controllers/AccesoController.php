<?php
/**
 * Controlador de Control de Accesos
 */

class AccesoController {
    private $accesoModel;
    private $pipaModel;
    private $estacionModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::require();
        $this->accesoModel = new Acceso();
        $this->pipaModel = new Pipa();
        $this->estacionModel = new Estacion();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar accesos
     */
    public function index() {
        $limit = $_GET['limit'] ?? 50;
        $accesos = $this->accesoModel->getRecientes($limit);
        
        require APP_PATH . '/views/accesos/index.php';
    }

    /**
     * Registrar acceso (entrada/salida)
     */
    public function registrar() {
        Auth::requireRole(['admin', 'operador', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigoQR = $_POST['codigo_qr'] ?? '';
            $estacionId = $_POST['estacion_id'] ?? 0;
            $tipoAcceso = $_POST['tipo_acceso'] ?? 'entrada';
            
            // Buscar pipa por código QR
            $pipa = $this->pipaModel->getByCodigoQR($codigoQR);
            
            if (!$pipa) {
                $_SESSION['error'] = 'Código QR no válido o pipa no encontrada';
                header('Location: ' . BASE_URL . 'accesos/registrar');
                exit;
            }
            
            // Verificar que la pipa esté activa
            if ($pipa['estado'] !== 'activa') {
                $_SESSION['error'] = 'La pipa no está activa. Estado: ' . $pipa['estado'];
                header('Location: ' . BASE_URL . 'accesos/registrar');
                exit;
            }
            
            // Verificar que la empresa esté activa
            if ($pipa['empresa_estado'] !== 'activa') {
                $_SESSION['error'] = 'La empresa no está activa. Estado: ' . $pipa['empresa_estado'];
                header('Location: ' . BASE_URL . 'accesos/registrar');
                exit;
            }
            
            // Verificar si hay entrada sin salida (solo para salidas)
            if ($tipoAcceso === 'salida') {
                $entradaSinSalida = $this->accesoModel->tieneEntradaSinSalida($pipa['id'], $estacionId);
                if (!$entradaSinSalida) {
                    $_SESSION['error'] = 'No hay registro de entrada para esta pipa en esta estación';
                    header('Location: ' . BASE_URL . 'accesos/registrar');
                    exit;
                }
            }
            
            $data = [
                'pipa_id' => $pipa['id'],
                'estacion_id' => $estacionId,
                'tipo_acceso' => $tipoAcceso,
                'usuario_id' => $_SESSION['user_id'],
                'autorizado' => 1,
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $accesoId = $this->accesoModel->registrar($data);
            
            if ($accesoId) {
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'accesos',
                    $accesoId,
                    "Acceso registrado: {$tipoAcceso} - Pipa: {$pipa['matricula']}"
                );
                
                $_SESSION['success'] = "Acceso registrado: {$tipoAcceso} autorizado para {$pipa['matricula']}";
                header('Location: ' . BASE_URL . 'accesos');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar acceso';
                header('Location: ' . BASE_URL . 'accesos/registrar');
                exit;
            }
        }
        
        $estaciones = $this->estacionModel->getActivas();
        require APP_PATH . '/views/accesos/registrar.php';
    }

    /**
     * Escanear código QR (simulado)
     */
    public function escanear() {
        $codigoQR = $_GET['codigo'] ?? '';
        
        if (!empty($codigoQR)) {
            $pipa = $this->pipaModel->getByCodigoQR($codigoQR);
            
            if ($pipa) {
                $response = [
                    'success' => true,
                    'pipa' => $pipa
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Código QR no válido'
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        require APP_PATH . '/views/accesos/escanear.php';
    }
}
