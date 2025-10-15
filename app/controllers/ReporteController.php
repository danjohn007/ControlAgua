<?php
/**
 * Controlador de Reportes
 */

class ReporteController {
    private $suministroModel;
    private $empresaModel;
    private $pipaModel;
    private $pagoModel;
    private $accesoModel;

    public function __construct() {
        Auth::require();
        $this->suministroModel = new Suministro();
        $this->empresaModel = new Empresa();
        $this->pipaModel = new Pipa();
        $this->pagoModel = new Pago();
        $this->accesoModel = new Acceso();
    }

    /**
     * Página principal de reportes
     */
    public function index() {
        require APP_PATH . '/views/reportes/index.php';
    }

    /**
     * Reporte de suministros por periodo
     */
    public function suministros() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $formato = $_GET['formato'] ?? 'web';
        
        $suministros = $this->suministroModel->getByFecha($fechaInicio, $fechaFin);
        
        // Calcular totales
        $totalLitros = array_sum(array_column($suministros, 'litros_suministrados'));
        $totalCobrado = array_sum(array_column($suministros, 'total_cobrado'));
        
        // Agrupar por empresa
        $porEmpresa = [];
        foreach ($suministros as $sum) {
            $empresaId = $sum['empresa_id'];
            if (!isset($porEmpresa[$empresaId])) {
                $porEmpresa[$empresaId] = [
                    'nombre' => $sum['empresa_nombre'],
                    'total_suministros' => 0,
                    'total_litros' => 0,
                    'total_cobrado' => 0
                ];
            }
            $porEmpresa[$empresaId]['total_suministros']++;
            $porEmpresa[$empresaId]['total_litros'] += $sum['litros_suministrados'];
            $porEmpresa[$empresaId]['total_cobrado'] += $sum['total_cobrado'];
        }
        
        if ($formato === 'csv') {
            $this->exportarCSV($suministros, 'suministros_' . date('Ymd'));
        } else {
            require APP_PATH . '/views/reportes/suministros.php';
        }
    }

    /**
     * Reporte de empresas con mayor consumo
     */
    public function empresasConsumo() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        $empresas = $this->empresaModel->getAll();
        $estadisticas = [];
        
        foreach ($empresas as $empresa) {
            $suministros = $this->suministroModel->getByEmpresa($empresa['id'], $fechaInicio, $fechaFin);
            
            if (!empty($suministros)) {
                $estadisticas[] = [
                    'empresa' => $empresa,
                    'total_suministros' => count($suministros),
                    'total_litros' => array_sum(array_column($suministros, 'litros_suministrados')),
                    'total_cobrado' => array_sum(array_column($suministros, 'total_cobrado'))
                ];
            }
        }
        
        // Ordenar por total de litros descendente
        usort($estadisticas, function($a, $b) {
            return $b['total_litros'] - $a['total_litros'];
        });
        
        require APP_PATH . '/views/reportes/empresas_consumo.php';
    }

    /**
     * Reporte de pagos pendientes
     */
    public function pagosPendientes() {
        $empresas = $this->empresaModel->getAll();
        $pendientes = [];
        
        foreach ($empresas as $empresa) {
            $suministros = $this->suministroModel->getByEmpresa($empresa['id']);
            $pagos = $this->pagoModel->getByEmpresa($empresa['id']);
            
            $totalSuministrado = array_sum(array_column($suministros, 'total_cobrado'));
            $totalPagado = array_sum(array_column($pagos, 'monto'));
            $saldoEmpresa = $empresa['saldo_actual'];
            
            $totalPendiente = $totalSuministrado - $totalPagado - $saldoEmpresa;
            
            if ($totalPendiente > 0 || $empresa['saldo_actual'] < 0) {
                $pendientes[] = [
                    'empresa' => $empresa,
                    'total_suministrado' => $totalSuministrado,
                    'total_pagado' => $totalPagado,
                    'saldo_actual' => $saldoEmpresa,
                    'pendiente' => $totalPendiente
                ];
            }
        }
        
        require APP_PATH . '/views/reportes/pagos_pendientes.php';
    }

    /**
     * Reporte de accesos
     */
    public function accesos() {
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $estacionId = $_GET['estacion_id'] ?? null;
        
        if ($estacionId) {
            $accesos = $this->accesoModel->getByEstacion($estacionId, $fechaInicio, $fechaFin);
        } else {
            $accesos = $this->accesoModel->getRecientes(500);
        }
        
        $estacionModel = new Estacion();
        $estaciones = $estacionModel->getAll();
        
        require APP_PATH . '/views/reportes/accesos.php';
    }

    /**
     * Exportar a CSV
     */
    private function exportarCSV($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if (!empty($data)) {
            // Headers
            fputcsv($output, array_keys($data[0]));
            
            // Data
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }

    /**
     * Dashboard de reportes con gráficas
     */
    public function dashboard() {
        // Estadísticas generales
        $estadisticasMes = $this->suministroModel->getEstadisticas('mes');
        
        // Top 5 empresas
        $empresas = $this->empresaModel->getAll();
        $topEmpresas = [];
        foreach ($empresas as $empresa) {
            $stats = $this->empresaModel->getEstadisticas($empresa['id']);
            if ($stats && $stats['total_litros'] > 0) {
                $topEmpresas[] = $stats;
            }
        }
        usort($topEmpresas, function($a, $b) {
            return $b['total_litros'] - $a['total_litros'];
        });
        $topEmpresas = array_slice($topEmpresas, 0, 5);
        
        require APP_PATH . '/views/reportes/dashboard.php';
    }
}
