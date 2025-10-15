<?php 
$pageTitle = 'Pagos - ' . APP_NAME;
$currentPage = 'pagos';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-cash-coin"></i> Pagos
            </h1>
            <p class="mb-0">Gestión de Pagos y Créditos</p>
        </div>
        <?php if (Auth::hasRole(['admin', 'supervisor'])): ?>
        <a href="<?php echo BASE_URL; ?>pagos/registrar" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Registrar Pago
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>pagos" class="row g-3">
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                       value="<?php echo htmlspecialchars($fechaInicio ?? date('Y-m-d', strtotime('-30 days'))); ?>">
            </div>
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                       value="<?php echo htmlspecialchars($fechaFin ?? date('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <label for="empresa_id" class="form-label">Empresa</label>
                <select class="form-select" id="empresa_id" name="empresa_id">
                    <option value="">Todas las empresas</option>
                    <?php if (!empty($empresas)): ?>
                        <?php foreach ($empresas as $emp): ?>
                            <option value="<?php echo $emp['id']; ?>" 
                                    <?php echo (isset($empresaId) && $empresaId == $emp['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp['razon_social']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Pagos Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Empresa</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pagos)): ?>
                        <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td><?php echo $pago['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></td>
                                <td><?php echo htmlspecialchars(substr($pago['empresa_nombre'], 0, 30)); ?>...</td>
                                <td>
                                    <strong class="text-success">
                                        $<?php echo number_format($pago['monto'], 2); ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php
                                    $tipoBadge = [
                                        'anticipado' => 'bg-info',
                                        'normal' => 'bg-primary',
                                        'credito' => 'bg-warning'
                                    ][$pago['tipo_pago']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $tipoBadge; ?>">
                                        <?php echo ucfirst($pago['tipo_pago']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst($pago['metodo_pago']); ?>
                                    </span>
                                </td>
                                <td>
                                    <code><?php echo htmlspecialchars($pago['referencia'] ?? 'N/A'); ?></code>
                                </td>
                                <td><?php echo htmlspecialchars($pago['usuario_nombre'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>pagos/ver/<?php echo $pago['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                No se encontraron pagos
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($pagos)): ?>
    <div class="card-footer">
        <?php 
        $totalPagos = array_sum(array_column($pagos, 'monto'));
        ?>
        <div class="row">
            <div class="col-md-6">
                <strong>Total de pagos:</strong> <?php echo count($pagos); ?>
            </div>
            <div class="col-md-6 text-end">
                <strong>Monto total:</strong> 
                <span class="text-success">$<?php echo number_format($totalPagos, 2); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
