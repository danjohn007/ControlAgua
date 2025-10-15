<?php 
$pageTitle = 'Nuevo Usuario - ' . APP_NAME;
$currentPage = 'usuarios';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-person-plus"></i> Nuevo Usuario
    </h1>
    <p class="mb-0">Crear nuevo usuario del sistema</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-circle"></i> Información del Usuario
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>usuarios/crear">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['nombre'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['email'] ?? ''); ?>" required>
                        <small class="text-muted">El email será el nombre de usuario</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" 
                               minlength="6" required>
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="operador" selected>Operador</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <small class="text-muted">
                            <strong>Operador:</strong> Registro básico | 
                            <strong>Supervisor:</strong> Gestión y reportes | 
                            <strong>Admin:</strong> Control total
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label" for="activo">
                                Usuario Activo
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>usuarios" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
unset($_SESSION['old_data']);
require APP_PATH . '/views/layouts/footer.php'; 
?>
