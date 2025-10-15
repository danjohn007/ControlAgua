<?php 
$pageTitle = 'Nueva Empresa - ' . APP_NAME;
$currentPage = 'empresas';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-building"></i> Nueva Empresa
    </h1>
    <p class="mb-0">Registrar nueva empresa en el sistema</p>
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
                
                <form method="POST" action="<?php echo BASE_URL; ?>empresas/crear" id="formEmpresa">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="razon_social" name="razon_social" 
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['razon_social'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="rfc" class="form-label">RFC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rfc" name="rfc" maxlength="13"
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['rfc'] ?? ''); ?>" 
                                   style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion_fiscal" class="form-label">Dirección Fiscal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="direccion_fiscal" name="direccion_fiscal" 
                                  rows="2" required><?php echo htmlspecialchars($_SESSION['old_data']['direccion_fiscal'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="representante_legal" class="form-label">Representante Legal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="representante_legal" name="representante_legal"
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['representante_legal'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['telefono'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="activa" selected>Activa</option>
                                <option value="revision">En Revisión</option>
                                <option value="suspendida">Suspendida</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="credito_autorizado" class="form-label">Crédito Autorizado</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="credito_autorizado" name="credito_autorizado"
                                       value="<?php echo htmlspecialchars($_SESSION['old_data']['credito_autorizado'] ?? '0'); ?>" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>empresas" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Empresa
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
