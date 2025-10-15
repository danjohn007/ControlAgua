<?php 
$pageTitle = 'Nueva Pipa - ' . APP_NAME;
$currentPage = 'pipas';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-truck"></i> Nueva Pipa
    </h1>
    <p class="mb-0">Registrar nuevo vehículo en el sistema</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Formulario de Registro
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>pipas/crear">
                    <div class="mb-3">
                        <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select class="form-select" id="empresa_id" name="empresa_id" required>
                            <option value="">Seleccione una empresa</option>
                            <?php if (!empty($empresas)): ?>
                                <?php foreach ($empresas as $empresa): ?>
                                    <option value="<?php echo $empresa['id']; ?>">
                                        <?php echo htmlspecialchars($empresa['razon_social']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricula" class="form-label">Matrícula/Placa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="matricula" name="matricula" 
                                   style="text-transform: uppercase;" required>
                            <small class="text-muted">Ejemplo: ABC-123-MX</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="capacidad_litros" class="form-label">Capacidad (Litros) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="capacidad_litros" name="capacidad_litros" 
                                   min="1000" step="100" required>
                            <small class="text-muted">Capacidad en litros</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="operador_nombre" class="form-label">Nombre del Operador <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="operador_nombre" name="operador_nombre" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie/Folio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   style="text-transform: uppercase;" required>
                            <small class="text-muted">Folio interno único</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="activa" selected>Activa</option>
                                <option value="mantenimiento">En Mantenimiento</option>
                                <option value="bloqueada">Bloqueada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        El código QR será generado automáticamente al registrar la pipa.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>pipas" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Pipa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
