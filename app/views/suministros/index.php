<?php 
$pageTitle = 'Suministros - ' . APP_NAME;
$currentPage = 'suministros';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-water"></i> Suministros
            </h1>
            <p class="mb-0">Registro de Cargas de Agua</p>
        </div>
        <?php if (Auth::hasRole(['admin', 'operador', 'supervisor'])): ?>
        <a href="<?php echo BASE_URL; ?>suministros/registrar" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nuevo Suministro
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>suministros" class="row g-3">
            <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                       value="<?php echo htmlspecialchars($fechaInicio ?? date('Y-m-d', strtotime('-7 days'))); ?>">
            </div>
            <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                       value="<?php echo htmlspecialchars($fechaFin ?? date('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suministros Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha/Hora</th>
                        <th>Empresa</th>
                        <th>Pipa</th>
                        <th>Estación</th>
                        <th>Litros</th>
                        <th>Tarifa</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($suministros)): ?>
                        <?php foreach ($suministros as $suministro): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($suministro['folio']); ?></strong>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($suministro['fecha_hora'])); ?></td>
                                <td><?php echo htmlspecialchars(substr($suministro['empresa_nombre'], 0, 25)); ?>...</td>
                                <td><?php echo htmlspecialchars($suministro['pipa_matricula']); ?></td>
                                <td><?php echo htmlspecialchars($suministro['estacion_nombre']); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo number_format($suministro['litros_suministrados']); ?> L
                                    </span>
                                </td>
                                <td>$<?php echo number_format($suministro['tarifa_por_litro'], 2); ?></td>
                                <td>
                                    <strong class="text-success">
                                        $<?php echo number_format($suministro['total_cobrado'], 2); ?>
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>suministros/ver/<?php echo $suministro['id']; ?>" 
                                           class="btn btn-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>suministros/ticket/<?php echo $suministro['id']; ?>" 
                                           class="btn btn-secondary" title="Ver Ticket" target="_blank">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                No se encontraron suministros en el periodo seleccionado
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($suministros)): ?>
    <div class="card-footer">
        <?php 
        $totalLitros = array_sum(array_column($suministros, 'litros_suministrados'));
        $totalCobrado = array_sum(array_column($suministros, 'total_cobrado'));
        ?>
        <div class="row">
            <div class="col-md-4">
                <strong>Total Registros:</strong> <?php echo count($suministros); ?>
            </div>
            <div class="col-md-4">
                <strong>Total Litros:</strong> <?php echo number_format($totalLitros); ?> L
            </div>
            <div class="col-md-4">
                <strong>Total Cobrado:</strong> $<?php echo number_format($totalCobrado, 2); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
