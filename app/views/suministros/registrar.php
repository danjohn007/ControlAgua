<?php 
$pageTitle = 'Registrar Suministro - ' . APP_NAME;
$currentPage = 'suministros';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-water"></i> Registrar Suministro
    </h1>
    <p class="mb-0">Registrar nueva carga de agua</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-droplet-fill"></i> Formulario de Suministro
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>suministros/registrar" id="formSuministro">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pipa_id" class="form-label">Pipa <span class="text-danger">*</span></label>
                            <select class="form-select" id="pipa_id" name="pipa_id" required>
                                <option value="">Seleccione una pipa</option>
                                <?php if (!empty($pipas)): ?>
                                    <?php foreach ($pipas as $pipa): ?>
                                        <option value="<?php echo $pipa['id']; ?>" 
                                                data-capacidad="<?php echo $pipa['capacidad_litros']; ?>"
                                                data-empresa="<?php echo htmlspecialchars($pipa['empresa_nombre']); ?>">
                                            <?php echo htmlspecialchars($pipa['matricula']); ?> 
                                            - <?php echo htmlspecialchars($pipa['empresa_nombre']); ?>
                                            (<?php echo number_format($pipa['capacidad_litros']); ?> L)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estacion_id" class="form-label">Estación de Carga <span class="text-danger">*</span></label>
                            <select class="form-select" id="estacion_id" name="estacion_id" required>
                                <option value="">Seleccione una estación</option>
                                <?php if (!empty($estaciones)): ?>
                                    <?php foreach ($estaciones as $estacion): ?>
                                        <option value="<?php echo $estacion['id']; ?>">
                                            <?php echo htmlspecialchars($estacion['nombre']); ?>
                                            - <?php echo htmlspecialchars($estacion['ubicacion']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="litros_suministrados" class="form-label">Litros a Suministrar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="litros_suministrados" name="litros_suministrados" 
                                   min="100" step="100" required>
                            <small class="text-muted">Cantidad en litros</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tarifa_por_litro" class="form-label">Tarifa por Litro <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="tarifa_por_litro" name="tarifa_por_litro" 
                                       value="<?php echo DEFAULT_TARIFF; ?>" step="0.01" min="0" required>
                            </div>
                            <small class="text-muted">Precio por litro</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="total_preview" class="form-label">Total a Cobrar</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control bg-light" id="total_preview" readonly value="0.00">
                        </div>
                        <small class="text-muted">Cálculo automático</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Nota:</strong> El folio será generado automáticamente y el saldo de la empresa se actualizará.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>suministros" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Registrar Suministro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Calcular total automáticamente
document.addEventListener('DOMContentLoaded', function() {
    const litrosInput = document.getElementById('litros_suministrados');
    const tarifaInput = document.getElementById('tarifa_por_litro');
    const totalPreview = document.getElementById('total_preview');
    
    function calcularTotal() {
        const litros = parseFloat(litrosInput.value) || 0;
        const tarifa = parseFloat(tarifaInput.value) || 0;
        const total = litros * tarifa;
        totalPreview.value = formatCurrency(total);
    }
    
    litrosInput.addEventListener('input', calcularTotal);
    tarifaInput.addEventListener('input', calcularTotal);
    
    // Validar capacidad de la pipa
    const pipaSelect = document.getElementById('pipa_id');
    pipaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const capacidad = selectedOption.getAttribute('data-capacidad');
        if (capacidad) {
            litrosInput.max = capacidad;
            litrosInput.placeholder = 'Máximo: ' + formatNumber(capacidad) + ' litros';
        }
    });
});
</script>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
