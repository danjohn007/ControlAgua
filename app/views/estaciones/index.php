<?php 
$pageTitle = 'Estaciones - ' . APP_NAME;
$currentPage = 'estaciones';
require APP_PATH . '/views/layouts/header.php'; 
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-geo-alt"></i> Estaciones de Carga
            </h1>
            <p class="mb-0">Gestión de Puntos de Suministro</p>
        </div>
        <a href="<?php echo BASE_URL; ?>estaciones/crear" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nueva Estación
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Estaciones Cards -->
<div class="row">
    <?php if (!empty($estaciones)): ?>
        <?php foreach ($estaciones as $estacion): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header <?php echo $estacion['activa'] ? 'bg-success' : 'bg-secondary'; ?> text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt-fill"></i> 
                                <?php echo htmlspecialchars($estacion['nombre']); ?>
                            </h5>
                            <?php if ($estacion['activa']): ?>
                                <span class="badge bg-light text-success">
                                    <i class="bi bi-check-circle"></i> Activa
                                </span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary">
                                    <i class="bi bi-x-circle"></i> Inactiva
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <i class="bi bi-pin-map"></i>
                            <strong>Ubicación:</strong><br>
                            <?php echo htmlspecialchars($estacion['ubicacion']); ?>
                        </p>
                        
                        <p class="card-text">
                            <i class="bi bi-speedometer2"></i>
                            <strong>Capacidad Diaria:</strong><br>
                            <span class="badge bg-info">
                                <?php echo number_format($estacion['capacidad_diaria']); ?> litros
                            </span>
                        </p>
                        
                        <p class="card-text">
                            <i class="bi bi-calendar-event"></i>
                            <strong>Fecha de Creación:</strong><br>
                            <?php echo date('d/m/Y', strtotime($estacion['fecha_creacion'])); ?>
                        </p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="btn-group w-100">
                            <a href="<?php echo BASE_URL; ?>estaciones/ver/<?php echo $estacion['id']; ?>" 
                               class="btn btn-info">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            <a href="<?php echo BASE_URL; ?>estaciones/editar/<?php echo $estacion['id']; ?>" 
                               class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="<?php echo BASE_URL; ?>estaciones/toggleEstado/<?php echo $estacion['id']; ?>" 
                               class="btn btn-<?php echo $estacion['activa'] ? 'secondary' : 'success'; ?>">
                                <i class="bi bi-<?php echo $estacion['activa'] ? 'x-circle' : 'check-circle'; ?>"></i>
                                <?php echo $estacion['activa'] ? 'Desactivar' : 'Activar'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
                    <h3 class="text-muted mt-3">No hay estaciones registradas</h3>
                    <p class="text-muted">Agregue la primera estación de carga al sistema</p>
                    <a href="<?php echo BASE_URL; ?>estaciones/crear" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Estación
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($estaciones)): ?>
<div class="card">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <h4><?php echo count($estaciones); ?></h4>
                <p class="text-muted">Total Estaciones</p>
            </div>
            <div class="col-md-4">
                <h4><?php echo count(array_filter($estaciones, fn($e) => $e['activa'])); ?></h4>
                <p class="text-muted">Estaciones Activas</p>
            </div>
            <div class="col-md-4">
                <h4><?php echo number_format(array_sum(array_column($estaciones, 'capacidad_diaria'))); ?> L</h4>
                <p class="text-muted">Capacidad Total Diaria</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
