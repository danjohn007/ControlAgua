<?php 
$pageTitle = 'Usuarios - ' . APP_NAME;
$currentPage = 'usuarios';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-people"></i> Usuarios del Sistema
            </h1>
            <p class="mb-0">Gestión de Usuarios y Permisos</p>
        </div>
        <a href="<?php echo BASE_URL; ?>usuarios/crear" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Usuarios Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td>
                                    <?php
                                    $rolBadge = [
                                        'admin' => 'bg-danger',
                                        'supervisor' => 'bg-warning',
                                        'operador' => 'bg-info'
                                    ][$usuario['rol']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $rolBadge; ?>">
                                        <?php echo ucfirst($usuario['rol']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    echo $usuario['ultimo_acceso'] 
                                        ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) 
                                        : 'Nunca'; 
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>usuarios/editar/<?php echo $usuario['id']; ?>" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>usuarios/toggleEstado/<?php echo $usuario['id']; ?>" 
                                           class="btn btn-<?php echo $usuario['activo'] ? 'secondary' : 'success'; ?>" 
                                           title="<?php echo $usuario['activo'] ? 'Desactivar' : 'Activar'; ?>">
                                            <i class="bi bi-<?php echo $usuario['activo'] ? 'x-circle' : 'check-circle'; ?>"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                No hay usuarios registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!empty($usuarios)): ?>
    <div class="card-footer">
        <small class="text-muted">
            Total de usuarios: <?php echo count($usuarios); ?>
        </small>
    </div>
    <?php endif; ?>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
