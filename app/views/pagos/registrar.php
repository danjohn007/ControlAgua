<?php 
$pageTitle = 'Registrar Pago - ' . APP_NAME;
$currentPage = 'pagos';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-cash-coin"></i> Registrar Pago
    </h1>
    <p class="mb-0">Registrar nuevo pago de empresa</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-currency-dollar"></i> Información del Pago
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>pagos/registrar" id="formPago">
                    <div class="mb-3">
                        <label for="empresa_id" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select class="form-select" id="empresa_id" name="empresa_id" required>
                            <option value="">Seleccione una empresa</option>
                            <?php if (!empty($empresas)): ?>
                                <?php foreach ($empresas as $empresa): ?>
                                    <option value="<?php echo $empresa['id']; ?>" 
                                            data-saldo="<?php echo $empresa['saldo_actual']; ?>">
                                        <?php echo htmlspecialchars($empresa['razon_social']); ?>
                                        (Saldo: $<?php echo number_format($empresa['saldo_actual'], 2); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div id="saldo-info" class="alert alert-info" style="display: none;">
                        <strong>Saldo Actual:</strong> $<span id="saldo-actual">0.00</span><br>
                        <strong>Nuevo Saldo:</strong> $<span id="saldo-nuevo">0.00</span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="monto" class="form-label">Monto del Pago <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="monto" name="monto" 
                                       step="0.01" min="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_pago" class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                                <option value="normal">Normal</option>
                                <option value="anticipado">Anticipado</option>
                                <option value="credito">A Crédito</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="cheque">Cheque</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="referencia" class="form-label">Referencia/Folio</label>
                            <input type="text" class="form-control" id="referencia" name="referencia">
                            <small class="text-muted">Número de transacción, cheque, etc.</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-success">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> El saldo de la empresa se actualizará automáticamente al registrar el pago.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>pagos" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const empresaSelect = document.getElementById('empresa_id');
    const montoInput = document.getElementById('monto');
    const saldoInfo = document.getElementById('saldo-info');
    const saldoActual = document.getElementById('saldo-actual');
    const saldoNuevo = document.getElementById('saldo-nuevo');
    
    function actualizarSaldo() {
        const selectedOption = empresaSelect.options[empresaSelect.selectedIndex];
        const saldoEmpresa = parseFloat(selectedOption.getAttribute('data-saldo')) || 0;
        const monto = parseFloat(montoInput.value) || 0;
        
        if (empresaSelect.value && monto > 0) {
            saldoInfo.style.display = 'block';
            saldoActual.textContent = formatCurrency(saldoEmpresa);
            saldoNuevo.textContent = formatCurrency(saldoEmpresa + monto);
        } else if (empresaSelect.value) {
            saldoInfo.style.display = 'block';
            saldoActual.textContent = formatCurrency(saldoEmpresa);
            saldoNuevo.textContent = formatCurrency(saldoEmpresa);
        } else {
            saldoInfo.style.display = 'none';
        }
    }
    
    empresaSelect.addEventListener('change', actualizarSaldo);
    montoInput.addEventListener('input', actualizarSaldo);
});
</script>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
