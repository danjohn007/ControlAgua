<?php 
$pageTitle = 'Nueva Estación - ' . APP_NAME;
$currentPage = 'estaciones';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-geo-alt"></i> Nueva Estación
    </h1>
    <p class="mb-0">Registrar nuevo punto de carga</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Información de la Estación
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>estaciones/crear">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Estación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <small class="text-muted">Ejemplo: Estación Norte, Estación Principal, etc.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Ubicación/Dirección <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ubicacion" name="ubicacion" rows="2" required></textarea>
                        <small class="text-muted">Dirección completa del punto de carga</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="capacidad_diaria" class="form-label">Capacidad Diaria (Litros) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="capacidad_diaria" name="capacidad_diaria" 
                               value="100000" min="1000" step="1000" required>
                        <small class="text-muted">Capacidad máxima de suministro diario en litros</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" checked>
                            <label class="form-check-label" for="activa">
                                Estación Activa
                            </label>
                        </div>
                        <small class="text-muted">Las estaciones inactivas no aparecerán en los formularios de registro</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Información:</strong> Una vez creada la estación, podrá monitorear su actividad y estadísticas en el panel de administración.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>estaciones" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Estación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
