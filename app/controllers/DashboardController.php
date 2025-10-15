<?php
/**
 * Controlador Dashboard
 */

class DashboardController {
    private $suministroModel;
    private $empresaModel;
    private $pipaModel;
    private $accesoModel;

    public function __construct() {
        Auth::require();
        $this->suministroModel = new Suministro();
        $this->empresaModel = new Empresa();
        $this->pipaModel = new Pipa();
        $this->accesoModel = new Acceso();
    }

    /**
     * Mostrar dashboard principal
     */
    public function index() {
        // Obtener estadísticas del día
        $estadisticasDia = $this->suministroModel->getEstadisticas('dia');
        $estadisticasSemana = $this->suministroModel->getEstadisticas('semana');
        $estadisticasMes = $this->suministroModel->getEstadisticas('mes');
        
        // Contar empresas activas
        $totalEmpresas = $this->empresaModel->count("estado = 'activa'");
        
        // Contar pipas activas
        $totalPipasActivas = $this->pipaModel->count("estado = 'activa'");
        
        // Obtener accesos recientes
        $accesosRecientes = $this->accesoModel->getRecientes(10);
        
        // Obtener suministros recientes
        $suministrosRecientes = $this->suministroModel->getAllWithDetails(10);
        
        // Obtener datos para gráficas - últimos 7 días
        $graficoSuministros = $this->getGraficoSuministros();
        
        $data = [
            'estadisticasDia' => $estadisticaDia ?? $estadisticasDia,
            'estadisticasSemana' => $estadisticasSemana,
            'estadisticasMes' => $estadisticasMes,
            'totalEmpresas' => $totalEmpresas,
            'totalPipasActivas' => $totalPipasActivas,
            'accesosRecientes' => $accesosRecientes,
            'suministrosRecientes' => $suministrosRecientes,
            'graficoSuministros' => $graficoSuministros
        ];
        
        require APP_PATH . '/views/dashboard/index.php';
    }

    /**
     * Obtener datos para gráfico de suministros
     */
    private function getGraficoSuministros() {
        $datos = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = date('Y-m-d', strtotime("-{$i} days"));
            $suministros = $this->suministroModel->getByFecha($fecha, $fecha);
            
            $totalLitros = 0;
            $totalCobrado = 0;
            
            foreach ($suministros as $sum) {
                $totalLitros += $sum['litros_suministrados'];
                $totalCobrado += $sum['total_cobrado'];
            }
            
            $datos[] = [
                'fecha' => $fecha,
                'fecha_formato' => date('d/m', strtotime($fecha)),
                'litros' => $totalLitros,
                'cobrado' => $totalCobrado,
                'cantidad' => count($suministros)
            ];
        }
        
        return $datos;
    }
}
