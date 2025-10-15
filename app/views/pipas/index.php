<?php 
$pageTitle = 'Pipas - ' . APP_NAME;
$currentPage = 'pipas';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-truck"></i> Pipas
            </h1>
            <p class="mb-0">Gestión de Vehículos (Pipas)</p>
        </div>
        <?php if (Auth::hasRole(['admin', 'supervisor'])): ?>
        <a href="<?php echo BASE_URL; ?>pipas/crear" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nueva Pipa
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

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>pipas" class="row g-3">
            <div class="col-md-10">
                <input type="text" class="form-control" name="buscar" 
                       placeholder="Buscar por matrícula, operador o número de serie..." 
                       value="<?php echo htmlspecialchars($buscar ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Pipas Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matrícula</th>
                        <th>Empresa</th>
                        <th>Capacidad</th>
                        <th>Operador</th>
                        <th>Código QR</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pipas)): ?>
                        <?php foreach ($pipas as $pipa): ?>
                            <tr>
                                <td><?php echo $pipa['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($pipa['matricula']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($pipa['empresa_nombre'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo number_format($pipa['capacidad_litros']); ?> L
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($pipa['operador_nombre']); ?></td>
                                <td>
                                    <code><?php echo htmlspecialchars($pipa['codigo_qr']); ?></code>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = [
                                        'activa' => 'bg-success',
                                        'bloqueada' => 'bg-danger',
                                        'mantenimiento' => 'bg-warning'
                                    ][$pipa['estado']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo ucfirst($pipa['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>pipas/ver/<?php echo $pipa['id']; ?>" 
                                           class="btn btn-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>pipas/generarQR/<?php echo $pipa['id']; ?>" 
                                           class="btn btn-secondary" title="Ver QR">
                                            <i class="bi bi-qr-code"></i>
                                        </a>
                                        <?php if (Auth::hasRole(['admin', 'supervisor'])): ?>
                                        <a href="<?php echo BASE_URL; ?>pipas/editar/<?php echo $pipa['id']; ?>" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                No se encontraron pipas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($pipas)): ?>
    <div class="card-footer">
        <small class="text-muted">
            Total de pipas: <?php echo $total ?? count($pipas); ?>
        </small>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
