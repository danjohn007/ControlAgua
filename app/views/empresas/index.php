<?php 
$pageTitle = 'Empresas - ' . APP_NAME;
$currentPage = 'empresas';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-building"></i> Empresas
            </h1>
            <p class="mb-0">Gestión de Empresas Registradas</p>
        </div>
        <?php if (Auth::hasRole(['admin', 'supervisor'])): ?>
        <a href="<?php echo BASE_URL; ?>empresas/crear" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nueva Empresa
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

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>empresas" class="row g-3">
            <div class="col-md-10">
                <input type="text" class="form-control" name="buscar" 
                       placeholder="Buscar por razón social, RFC o representante..." 
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

<!-- Empresas Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razón Social</th>
                        <th>RFC</th>
                        <th>Representante</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Saldo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($empresas)): ?>
                        <?php foreach ($empresas as $empresa): ?>
                            <tr>
                                <td><?php echo $empresa['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($empresa['razon_social']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($empresa['rfc']); ?></td>
                                <td><?php echo htmlspecialchars($empresa['representante_legal']); ?></td>
                                <td><?php echo htmlspecialchars($empresa['telefono']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = [
                                        'activa' => 'bg-success',
                                        'suspendida' => 'bg-danger',
                                        'revision' => 'bg-warning'
                                    ][$empresa['estado']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo ucfirst($empresa['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong class="<?php echo $empresa['saldo_actual'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                        $<?php echo number_format($empresa['saldo_actual'], 2); ?>
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>empresas/ver/<?php echo $empresa['id']; ?>" 
                                           class="btn btn-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (Auth::hasRole(['admin', 'supervisor'])): ?>
                                        <a href="<?php echo BASE_URL; ?>empresas/editar/<?php echo $empresa['id']; ?>" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (Auth::hasRole('admin')): ?>
                                        <a href="<?php echo BASE_URL; ?>empresas/eliminar/<?php echo $empresa['id']; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirmDelete('¿Eliminar empresa <?php echo htmlspecialchars($empresa['razon_social']); ?>?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
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
                                No se encontraron empresas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($empresas)): ?>
    <div class="card-footer">
        <small class="text-muted">
            Total de empresas: <?php echo $total ?? count($empresas); ?>
        </small>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
