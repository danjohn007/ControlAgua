<?php
/**
 * Controlador de Suministros
 */

class SuministroController {
    private $suministroModel;
    private $pipaModel;
    private $empresaModel;
    private $estacionModel;
    private $auditoriaModel;

    public function __construct() {
        Auth::require();
        $this->suministroModel = new Suministro();
        $this->pipaModel = new Pipa();
        $this->empresaModel = new Empresa();
        $this->estacionModel = new Estacion();
        $this->auditoriaModel = new Auditoria();
    }

    /**
     * Listar suministros
     */
    public function index() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-7 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        if ($fechaInicio && $fechaFin) {
            $suministros = $this->suministroModel->getByFecha($fechaInicio, $fechaFin);
        } else {
            $suministros = $this->suministroModel->getAllWithDetails(50);
        }
        
        require APP_PATH . '/views/suministros/index.php';
    }

    /**
     * Registrar nuevo suministro
     */
    public function registrar() {
        Auth::requireRole(['admin', 'operador', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pipaId = $_POST['pipa_id'] ?? 0;
            $estacionId = $_POST['estacion_id'] ?? 0;
            $litros = $_POST['litros_suministrados'] ?? 0;
            $tarifa = $_POST['tarifa_por_litro'] ?? DEFAULT_TARIFF;
            
            // Obtener información de la pipa
            $pipa = $this->pipaModel->getById($pipaId);
            
            if (!$pipa) {
                $_SESSION['error'] = 'Pipa no encontrada';
                header('Location: ' . BASE_URL . 'suministros/registrar');
                exit;
            }
            
            // Verificar que la pipa esté activa
            if ($pipa['estado'] !== 'activa') {
                $_SESSION['error'] = 'La pipa no está activa';
                header('Location: ' . BASE_URL . 'suministros/registrar');
                exit;
            }
            
            $data = [
                'pipa_id' => $pipaId,
                'estacion_id' => $estacionId,
                'empresa_id' => $pipa['empresa_id'],
                'litros_suministrados' => $litros,
                'tarifa_por_litro' => $tarifa,
                'total_cobrado' => $litros * $tarifa,
                'usuario_id' => $_SESSION['user_id'],
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $suministroId = $this->suministroModel->registrar($data);
            
            if ($suministroId) {
                // Descontar del saldo de la empresa
                $this->empresaModel->updateSaldo($pipa['empresa_id'], $data['total_cobrado'], 'restar');
                
                // Registrar auditoría
                $folio = $this->suministroModel->getById($suministroId)['folio'];
                $this->auditoriaModel->registrar(
                    $_SESSION['user_id'],
                    'INSERT',
                    'suministros',
                    $suministroId,
                    "Nuevo suministro registrado: Folio {$folio}, {$litros} litros"
                );
                
                $_SESSION['success'] = "Suministro registrado exitosamente. Folio: {$folio}";
                header('Location: ' . BASE_URL . 'suministros/ver/' . $suministroId);
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar suministro';
                header('Location: ' . BASE_URL . 'suministros/registrar');
                exit;
            }
        }
        
        $pipas = $this->pipaModel->getActivas();
        $estaciones = $this->estacionModel->getActivas();
        
        require APP_PATH . '/views/suministros/registrar.php';
    }

    /**
     * Ver detalle de suministro
     */
    public function ver($id) {
        $suministro = $this->suministroModel->getById($id);
        
        if (!$suministro) {
            $_SESSION['error'] = 'Suministro no encontrado';
            header('Location: ' . BASE_URL . 'suministros');
            exit;
        }
        
        $pipa = $this->pipaModel->getById($suministro['pipa_id']);
        $empresa = $this->empresaModel->getById($suministro['empresa_id']);
        $estacion = $this->estacionModel->getById($suministro['estacion_id']);
        
        require APP_PATH . '/views/suministros/ver.php';
    }

    /**
     * Imprimir ticket de suministro
     */
    public function ticket($id) {
        $suministro = $this->suministroModel->getById($id);
        
        if (!$suministro) {
            $_SESSION['error'] = 'Suministro no encontrado';
            header('Location: ' . BASE_URL . 'suministros');
            exit;
        }
        
        $pipa = $this->pipaModel->getById($suministro['pipa_id']);
        $empresa = $this->empresaModel->getById($suministro['empresa_id']);
        $estacion = $this->estacionModel->getById($suministro['estacion_id']);
        
        // Marcar como impreso
        $this->suministroModel->update($id, ['ticket_impreso' => 1]);
        
        require APP_PATH . '/views/suministros/ticket.php';
    }
}
