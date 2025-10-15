<?php 
$pageTitle = 'Dashboard - ' . APP_NAME;
$currentPage = 'dashboard';
require APP_PATH . '/views/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-speedometer2"></i> Dashboard
    </h1>
    <p class="mb-0">Panel de Control y Monitoreo</p>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Litros Hoy</h6>
                        <h3 class="mb-0 fw-bold">
                            <?php echo number_format($estadisticasDia['total_litros'] ?? 0); ?>
                        </h3>
                    </div>
                    <div class="text-primary" style="font-size: 3rem; opacity: 0.3;">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Ingresos Hoy</h6>
                        <h3 class="mb-0 fw-bold">
                            $<?php echo number_format($estadisticasDia['total_cobrado'] ?? 0, 2); ?>
                        </h3>
                    </div>
                    <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Empresas Activas</h6>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $totalEmpresas ?? 0; ?>
                        </h3>
                    </div>
                    <div class="text-warning" style="font-size: 3rem; opacity: 0.3;">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pipas Activas</h6>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $totalPipasActivas ?? 0; ?>
                        </h3>
                    </div>
                    <div class="text-info" style="font-size: 3rem; opacity: 0.3;">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Suministros Últimos 7 Días
            </div>
            <div class="card-body">
                <canvas id="chartSuministros" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar3"></i> Resumen Mensual
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Suministros</span>
                        <strong><?php echo $estadisticasMes['total_suministros'] ?? 0; ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Litros Despachados</span>
                        <strong><?php echo number_format($estadisticasMes['total_litros'] ?? 0); ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 85%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Cobrado</span>
                        <strong>$<?php echo number_format($estadisticasMes['total_cobrado'] ?? 0, 2); ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Promedio por Carga</span>
                        <strong><?php echo number_format($estadisticasMes['promedio_litros'] ?? 0); ?> L</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Accesos Recientes
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Pipa</th>
                                <th>Estación</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($accesosRecientes)): ?>
                                <?php foreach (array_slice($accesosRecientes, 0, 5) as $acceso): ?>
                                    <tr>
                                        <td><?php echo date('H:i', strtotime($acceso['fecha_hora'])); ?></td>
                                        <td><?php echo htmlspecialchars($acceso['pipa_matricula']); ?></td>
                                        <td><?php echo htmlspecialchars($acceso['estacion_nombre']); ?></td>
                                        <td>
                                            <?php if ($acceso['tipo_acceso'] === 'entrada'): ?>
                                                <span class="badge bg-success">Entrada</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Salida</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay accesos recientes</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-water"></i> Suministros Recientes
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Empresa</th>
                                <th>Litros</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($suministrosRecientes)): ?>
                                <?php foreach (array_slice($suministrosRecientes, 0, 5) as $suministro): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($suministro['folio']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($suministro['empresa_nombre'], 0, 25)); ?>...</td>
                                        <td><?php echo number_format($suministro['litros_suministrados']); ?></td>
                                        <td>$<?php echo number_format($suministro['total_cobrado'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay suministros recientes</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart.js - Suministros Chart
const ctx = document.getElementById('chartSuministros');
const graficoData = <?php echo json_encode($graficoSuministros ?? []); ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: graficoData.map(d => d.fecha_formato),
        datasets: [{
            label: 'Litros',
            data: graficoData.map(d => d.litros),
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Cobrado ($)',
            data: graficoData.map(d => d.cobrado),
            borderColor: 'rgb(25, 135, 84)',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
