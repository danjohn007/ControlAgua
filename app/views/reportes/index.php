<?php 
$pageTitle = 'Reportes - ' . APP_NAME;
$currentPage = 'reportes';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-file-earmark-bar-graph"></i> Reportes y Estadísticas
    </h1>
    <p class="mb-0">Análisis y reportes del sistema</p>
</div>

<div class="row">
    <!-- Reportes de Suministros -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-water"></i> Reportes de Suministros
            </div>
            <div class="card-body">
                <h5 class="card-title">Suministros por Periodo</h5>
                <p class="card-text">
                    Consulte el historial completo de suministros realizados en un rango de fechas específico.
                </p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Filtrar por fechas</li>
                    <li><i class="bi bi-check-circle text-success"></i> Detalle por empresa</li>
                    <li><i class="bi bi-check-circle text-success"></i> Exportar a CSV</li>
                </ul>
                <a href="<?php echo BASE_URL; ?>reportes/suministros" class="btn btn-primary">
                    <i class="bi bi-graph-up"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>
    
    <!-- Reportes de Empresas -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <i class="bi bi-building"></i> Reportes de Empresas
            </div>
            <div class="card-body">
                <h5 class="card-title">Empresas con Mayor Consumo</h5>
                <p class="card-text">
                    Analice las empresas con mayor consumo de agua y su comportamiento de compra.
                </p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Ranking de empresas</li>
                    <li><i class="bi bi-check-circle text-success"></i> Total de litros consumidos</li>
                    <li><i class="bi bi-check-circle text-success"></i> Estadísticas comparativas</li>
                </ul>
                <a href="<?php echo BASE_URL; ?>reportes/empresasConsumo" class="btn btn-success">
                    <i class="bi bi-trophy"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>
    
    <!-- Reportes de Pagos -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-cash-coin"></i> Reportes Financieros
            </div>
            <div class="card-body">
                <h5 class="card-title">Cuentas por Cobrar</h5>
                <p class="card-text">
                    Revise el estado de cuentas de todas las empresas y pagos pendientes.
                </p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Saldos pendientes</li>
                    <li><i class="bi bi-check-circle text-success"></i> Créditos autorizados</li>
                    <li><i class="bi bi-check-circle text-success"></i> Estado de cuenta</li>
                </ul>
                <a href="<?php echo BASE_URL; ?>reportes/pagosPendientes" class="btn btn-warning">
                    <i class="bi bi-currency-dollar"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>
    
    <!-- Reportes de Accesos -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <i class="bi bi-door-open"></i> Reportes de Accesos
            </div>
            <div class="card-body">
                <h5 class="card-title">Control de Entradas y Salidas</h5>
                <p class="card-text">
                    Analice el flujo de vehículos en las diferentes estaciones de carga.
                </p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Por estación</li>
                    <li><i class="bi bi-check-circle text-success"></i> Por periodo</li>
                    <li><i class="bi bi-check-circle text-success"></i> Estadísticas de ocupación</li>
                </ul>
                <a href="<?php echo BASE_URL; ?>reportes/accesos" class="btn btn-info">
                    <i class="bi bi-list-check"></i> Ver Reporte
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard de Reportes -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-speedometer"></i> Dashboard de Reportes
    </div>
    <div class="card-body">
        <p>
            Acceda al dashboard interactivo con gráficas y métricas en tiempo real del sistema.
        </p>
        <a href="<?php echo BASE_URL; ?>reportes/dashboard" class="btn btn-lg btn-primary">
            <i class="bi bi-graph-up-arrow"></i> Ir al Dashboard de Reportes
        </a>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
