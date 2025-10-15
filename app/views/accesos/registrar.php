<?php 
$pageTitle = 'Registrar Acceso - ' . APP_NAME;
$currentPage = 'accesos';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <h1 class="mb-2">
        <i class="bi bi-door-open"></i> Registrar Acceso
    </h1>
    <p class="mb-0">Control de entrada/salida de pipas</p>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-qr-code-scan"></i> Escanear Código QR
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>accesos/registrar" id="formAcceso">
                    <div class="mb-3">
                        <label for="codigo_qr" class="form-label">Código QR de la Pipa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="codigo_qr" name="codigo_qr" 
                               placeholder="Escanee o ingrese el código QR" required autofocus>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Escanee el código QR de la pipa o ingréselo manualmente
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estacion_id" class="form-label">Estación <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="estacion_id" name="estacion_id" required>
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
                    
                    <div class="mb-3">
                        <label for="tipo_acceso" class="form-label">Tipo de Acceso <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="tipo_acceso" id="entrada" 
                                   value="entrada" checked>
                            <label class="btn btn-outline-success btn-lg" for="entrada">
                                <i class="bi bi-arrow-right"></i> Entrada
                            </label>
                            
                            <input type="radio" class="btn-check" name="tipo_acceso" id="salida" 
                                   value="salida">
                            <label class="btn btn-outline-danger btn-lg" for="salida">
                                <i class="bi bi-arrow-left"></i> Salida
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>accesos" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Registrar Acceso
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <i class="bi bi-info-circle"></i> Instrucciones
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li>Escanee el código QR de la pipa o ingréselo manualmente</li>
                    <li>Seleccione la estación donde se registra el acceso</li>
                    <li>Elija el tipo de acceso (Entrada o Salida)</li>
                    <li>Opcionalmente agregue observaciones</li>
                    <li>Presione "Registrar Acceso" para completar</li>
                </ol>
                
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>Nota:</strong> El sistema validará automáticamente que:
                    <ul class="mb-0 mt-2">
                        <li>La pipa esté activa</li>
                        <li>La empresa esté activa</li>
                        <li>Para salidas, que exista una entrada previa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('codigo_qr').focus();
</script>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
