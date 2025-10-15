-- ============================================================================
-- Datos de Ejemplo para el Sistema de Control de Suministro de Agua
-- ============================================================================

USE `controlagua`;

-- ============================================================================
-- Insertar Usuarios (password: admin123, operador123, supervisor123)
-- ============================================================================
INSERT INTO `usuarios` (`nombre`, `email`, `password`, `rol`, `activo`) VALUES
('Administrador General', 'admin@controlagua.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Juan Pérez Operador', 'operador@controlagua.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operador', 1),
('María González Supervisor', 'supervisor@controlagua.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', 1);

-- ============================================================================
-- Insertar Empresas de Ejemplo
-- ============================================================================
INSERT INTO `empresas` (`razon_social`, `rfc`, `direccion_fiscal`, `representante_legal`, `telefono`, `email`, `estado`, `saldo_actual`, `credito_autorizado`) VALUES
('Transportes Hidráulicos del Norte S.A. de C.V.', 'THN950101ABC', 'Av. Insurgentes Norte 1234, Col. Industrial, CDMX, CP 07800', 'Roberto Martínez López', '5512345678', 'contacto@thnorte.com', 'activa', 5000.00, 10000.00),
('Distribuidora de Agua Potable S.A.', 'DAP920315XYZ', 'Calle Reforma 567, Col. Centro, Monterrey, NL, CP 64000', 'Laura Sánchez Ramírez', '8187654321', 'info@dapotable.com', 'activa', 3500.00, 8000.00),
('Servicios Acuíferos del Bajío S.C.', 'SAB880520DEF', 'Blvd. Adolfo López Mateos 890, León, Guanajuato, CP 37000', 'Carlos Hernández Vega', '4771234567', 'ventas@sabajio.com', 'activa', 2000.00, 5000.00),
('Grupo Hídrico del Sureste S.A. de C.V.', 'GHS910710GHI', 'Av. Universidad 234, Col. Magisterial, Tuxtla Gutiérrez, Chiapas, CP 29000', 'Ana Patricia Ruiz', '9612345678', 'ghsureste@gmail.com', 'revision', 0.00, 3000.00);

-- ============================================================================
-- Insertar Estaciones de Carga
-- ============================================================================
INSERT INTO `estaciones` (`nombre`, `ubicacion`, `capacidad_diaria`, `activa`) VALUES
('Estación Norte', 'Km 12.5 Carretera México-Pachuca', 150000, 1),
('Estación Sur', 'Av. Tláhuac 890, Zona Industrial', 120000, 1),
('Estación Centro', 'Calz. Ignacio Zaragoza 456', 100000, 1),
('Estación Oriente', 'Autopista México-Puebla Km 8', 80000, 1);

-- ============================================================================
-- Insertar Pipas
-- ============================================================================
INSERT INTO `pipas` (`empresa_id`, `matricula`, `capacidad_litros`, `operador_nombre`, `numero_serie`, `codigo_qr`, `estado`) VALUES
(1, 'ABC-123-MX', 10000, 'Pedro Ramírez García', 'SN-THN-001', 'QR-THN-001-2024', 'activa'),
(1, 'ABC-456-MX', 12000, 'José Luis Moreno', 'SN-THN-002', 'QR-THN-002-2024', 'activa'),
(1, 'ABC-789-MX', 15000, 'Miguel Ángel Torres', 'SN-THN-003', 'QR-THN-003-2024', 'mantenimiento'),
(2, 'DEF-111-NL', 10000, 'Raúl Mendoza Castro', 'SN-DAP-001', 'QR-DAP-001-2024', 'activa'),
(2, 'DEF-222-NL', 10000, 'Fernando López Díaz', 'SN-DAP-002', 'QR-DAP-002-2024', 'activa'),
(3, 'GHI-333-GTO', 8000, 'Alejandro Ruiz Pérez', 'SN-SAB-001', 'QR-SAB-001-2024', 'activa'),
(3, 'GHI-444-GTO', 12000, 'Jorge Alberto Vega', 'SN-SAB-002', 'QR-SAB-002-2024', 'activa'),
(4, 'JKL-555-CHP', 10000, 'Ricardo Gómez Silva', 'SN-GHS-001', 'QR-GHS-001-2024', 'bloqueada');

-- ============================================================================
-- Insertar Tarifas
-- ============================================================================
INSERT INTO `tarifas` (`empresa_id`, `precio_por_litro`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `activa`) VALUES
(NULL, 0.5000, 'Tarifa General 2024', 'Tarifa estándar para todos los clientes', '2024-01-01', NULL, 1),
(1, 0.4500, 'Tarifa Preferencial THN', 'Tarifa especial por volumen para Transportes Hidráulicos del Norte', '2024-01-01', '2024-12-31', 1),
(2, 0.4800, 'Tarifa Corporativa DAP', 'Tarifa corporativa para Distribuidora de Agua Potable', '2024-01-01', '2024-12-31', 1);

-- ============================================================================
-- Insertar Suministros de Ejemplo
-- ============================================================================
INSERT INTO `suministros` (`folio`, `pipa_id`, `estacion_id`, `empresa_id`, `litros_suministrados`, `tarifa_por_litro`, `total_cobrado`, `fecha_hora`, `usuario_id`, `ticket_impreso`) VALUES
('SUM-2024-0001', 1, 1, 1, 10000, 0.45, 4500.00, '2024-01-15 08:30:00', 2, 1),
('SUM-2024-0002', 2, 1, 1, 12000, 0.45, 5400.00, '2024-01-15 10:15:00', 2, 1),
('SUM-2024-0003', 4, 2, 2, 10000, 0.48, 4800.00, '2024-01-15 11:45:00', 2, 1),
('SUM-2024-0004', 1, 1, 1, 10000, 0.45, 4500.00, '2024-01-16 09:00:00', 2, 1),
('SUM-2024-0005', 5, 2, 2, 10000, 0.48, 4800.00, '2024-01-16 14:30:00', 2, 1),
('SUM-2024-0006', 6, 3, 3, 8000, 0.50, 4000.00, '2024-01-17 07:45:00', 2, 1),
('SUM-2024-0007', 7, 3, 3, 12000, 0.50, 6000.00, '2024-01-17 13:20:00', 2, 1),
('SUM-2024-0008', 1, 1, 1, 10000, 0.45, 4500.00, '2024-01-18 08:15:00', 2, 1);

-- ============================================================================
-- Insertar Accesos de Ejemplo
-- ============================================================================
INSERT INTO `accesos` (`pipa_id`, `estacion_id`, `tipo_acceso`, `fecha_hora`, `usuario_id`, `autorizado`) VALUES
(1, 1, 'entrada', '2024-01-15 08:25:00', 2, 1),
(1, 1, 'salida', '2024-01-15 08:45:00', 2, 1),
(2, 1, 'entrada', '2024-01-15 10:10:00', 2, 1),
(2, 1, 'salida', '2024-01-15 10:30:00', 2, 1),
(4, 2, 'entrada', '2024-01-15 11:40:00', 2, 1),
(4, 2, 'salida', '2024-01-15 12:00:00', 2, 1),
(1, 1, 'entrada', '2024-01-16 08:55:00', 2, 1),
(1, 1, 'salida', '2024-01-16 09:15:00', 2, 1);

-- ============================================================================
-- Insertar Pagos de Ejemplo
-- ============================================================================
INSERT INTO `pagos` (`empresa_id`, `monto`, `tipo_pago`, `metodo_pago`, `referencia`, `fecha_pago`, `usuario_id`) VALUES
(1, 15000.00, 'anticipado', 'transferencia', 'TRANS-001-2024', '2024-01-10 10:00:00', 1),
(2, 10000.00, 'anticipado', 'transferencia', 'TRANS-002-2024', '2024-01-10 11:30:00', 1),
(3, 5000.00, 'normal', 'efectivo', 'EFE-001-2024', '2024-01-12 09:00:00', 2),
(1, 5000.00, 'normal', 'cheque', 'CHE-12345', '2024-01-15 15:00:00', 1);

-- ============================================================================
-- Insertar Configuración del Sistema
-- ============================================================================
INSERT INTO `configuracion` (`clave`, `valor`, `descripcion`) VALUES
('tarifa_default', '0.50', 'Tarifa por defecto por litro de agua'),
('limite_credito_default', '5000.00', 'Límite de crédito por defecto para nuevas empresas'),
('dias_revision_pipa', '30', 'Días entre revisiones de pipas'),
('email_notificaciones', 'notificaciones@controlagua.com', 'Email para envío de notificaciones'),
('prefijo_folio', 'SUM', 'Prefijo para folios de suministro'),
('iva_aplicable', '16', 'Porcentaje de IVA aplicable'),
('moneda', 'MXN', 'Moneda del sistema');

-- ============================================================================
-- Insertar Auditoría de Ejemplo
-- ============================================================================
INSERT INTO `auditoria` (`usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `descripcion`, `ip_address`, `fecha_hora`) VALUES
(1, 'INSERT', 'empresas', 1, 'Registro de nueva empresa: Transportes Hidráulicos del Norte S.A. de C.V.', '192.168.1.100', '2024-01-10 08:00:00'),
(1, 'INSERT', 'empresas', 2, 'Registro de nueva empresa: Distribuidora de Agua Potable S.A.', '192.168.1.100', '2024-01-10 08:15:00'),
(2, 'INSERT', 'suministros', 1, 'Nuevo suministro: Folio SUM-2024-0001, 10000 litros', '192.168.1.101', '2024-01-15 08:30:00'),
(2, 'INSERT', 'suministros', 2, 'Nuevo suministro: Folio SUM-2024-0002, 12000 litros', '192.168.1.101', '2024-01-15 10:15:00');
