<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? APP_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .stat-card {
            border-left: 4px solid;
            padding: 20px;
        }
        
        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.danger { border-left-color: var(--danger-color); }
        .stat-card.info { border-left-color: var(--info-color); }
        
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            margin: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e3c72;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">
                            <i class="bi bi-droplet-fill"></i> ControlAgua
                        </h4>
                    </div>
                    
                    <!-- User Info -->
                    <div class="user-info text-white">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <div>
                            <div style="font-size: 0.9rem; font-weight: 600;">
                                <?php echo $_SESSION['user_name'] ?? 'Usuario'; ?>
                            </div>
                            <div style="font-size: 0.75rem; opacity: 0.8;">
                                <?php echo ucfirst($_SESSION['user_role'] ?? 'usuario'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'empresas' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>empresas">
                                <i class="bi bi-building"></i> Empresas
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'pipas' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>pipas">
                                <i class="bi bi-truck"></i> Pipas
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'suministros' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>suministros">
                                <i class="bi bi-water"></i> Suministros
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'accesos' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>accesos">
                                <i class="bi bi-door-open"></i> Control Accesos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'pagos' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>pagos">
                                <i class="bi bi-cash-coin"></i> Pagos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'reportes' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>reportes">
                                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                            </a>
                        </li>
                        
                        <?php if (Auth::hasRole('admin')): ?>
                        <li class="nav-item mt-3">
                            <div class="text-white px-3 py-2" style="font-size: 0.8rem; opacity: 0.6;">
                                ADMINISTRACIÓN
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'usuarios' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>usuarios">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage ?? '') === 'estaciones' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>estaciones">
                                <i class="bi bi-geo-alt"></i> Estaciones
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>logout">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-4 py-4">
