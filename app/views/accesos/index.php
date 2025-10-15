<?php 
$pageTitle = 'Control de Accesos - ' . APP_NAME;
$currentPage = 'accesos';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-door-open"></i> Control de Accesos
            </h1>
            <p class="mb-0">Registro de Entradas y Salidas</p>
        </div>
        <?php if (Auth::hasRole(['admin', 'operador', 'supervisor'])): ?>
        <a href="<?php echo BASE_URL; ?>accesos/registrar" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Registrar Acceso
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

<!-- Recent Access Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-clock-history"></i> Accesos Recientes
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha/Hora</th>
                        <th>Pipa</th>
                        <th>Empresa</th>
                        <th>Estación</th>
                        <th>Tipo</th>
                        <th>Autorizado</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($accesos)): ?>
                        <?php foreach ($accesos as $acceso): ?>
                            <tr>
                                <td><?php echo $acceso['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($acceso['fecha_hora'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($acceso['pipa_matricula']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars(substr($acceso['empresa_nombre'], 0, 25)); ?>...</td>
                                <td><?php echo htmlspecialchars($acceso['estacion_nombre']); ?></td>
                                <td>
                                    <?php if ($acceso['tipo_acceso'] === 'entrada'): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-right"></i> Entrada
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-left"></i> Salida
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($acceso['autorizado']): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Sí
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> No
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($acceso['usuario_nombre'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                No hay accesos registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($accesos)): ?>
    <div class="card-footer">
        <small class="text-muted">
            Mostrando los últimos <?php echo count($accesos); ?> accesos
        </small>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
