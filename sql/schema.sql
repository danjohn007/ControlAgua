-- ============================================================================
-- Sistema de Control de Suministro de Agua a Pipas
-- Base de Datos: controlagua
-- MySQL 5.7+
-- ============================================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `controlagua` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `controlagua`;

-- ============================================================================
-- Tabla de Usuarios del Sistema
-- ============================================================================
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('admin','operador','supervisor') NOT NULL DEFAULT 'operador',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acceso` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Empresas
-- ============================================================================
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `razon_social` VARCHAR(200) NOT NULL,
  `rfc` VARCHAR(13) NOT NULL,
  `direccion_fiscal` TEXT NOT NULL,
  `representante_legal` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `estado` ENUM('activa','suspendida','revision') NOT NULL DEFAULT 'activa',
  `saldo_actual` DECIMAL(10,2) DEFAULT 0.00,
  `credito_autorizado` DECIMAL(10,2) DEFAULT 0.00,
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfc_unique` (`rfc`),
  INDEX `estado_idx` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Pipas (Vehículos)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `pipas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` INT(11) NOT NULL,
  `matricula` VARCHAR(20) NOT NULL,
  `capacidad_litros` INT(11) NOT NULL,
  `operador_nombre` VARCHAR(100) NOT NULL,
  `numero_serie` VARCHAR(50) NOT NULL,
  `codigo_qr` VARCHAR(100) NOT NULL,
  `estado` ENUM('activa','bloqueada','mantenimiento') NOT NULL DEFAULT 'activa',
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula_unique` (`matricula`),
  UNIQUE KEY `codigo_qr_unique` (`codigo_qr`),
  UNIQUE KEY `numero_serie_unique` (`numero_serie`),
  INDEX `empresa_idx` (`empresa_id`),
  INDEX `estado_idx` (`estado`),
  CONSTRAINT `fk_pipas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Estaciones de Carga (Plumas)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `estaciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `ubicacion` VARCHAR(200) NOT NULL,
  `capacidad_diaria` INT(11) NOT NULL DEFAULT 100000,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Control de Acceso (Entradas/Salidas)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `accesos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pipa_id` INT(11) NOT NULL,
  `estacion_id` INT(11) NOT NULL,
  `tipo_acceso` ENUM('entrada','salida') NOT NULL,
  `fecha_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` INT(11) NULL,
  `autorizado` TINYINT(1) NOT NULL DEFAULT 1,
  `observaciones` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `pipa_idx` (`pipa_id`),
  INDEX `estacion_idx` (`estacion_id`),
  INDEX `fecha_idx` (`fecha_hora`),
  CONSTRAINT `fk_accesos_pipa` FOREIGN KEY (`pipa_id`) REFERENCES `pipas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_accesos_estacion` FOREIGN KEY (`estacion_id`) REFERENCES `estaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_accesos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Suministros de Agua
-- ============================================================================
CREATE TABLE IF NOT EXISTS `suministros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `folio` VARCHAR(20) NOT NULL,
  `pipa_id` INT(11) NOT NULL,
  `estacion_id` INT(11) NOT NULL,
  `empresa_id` INT(11) NOT NULL,
  `litros_suministrados` INT(11) NOT NULL,
  `tarifa_por_litro` DECIMAL(10,2) NOT NULL,
  `total_cobrado` DECIMAL(10,2) NOT NULL,
  `fecha_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` INT(11) NULL,
  `ticket_impreso` TINYINT(1) DEFAULT 0,
  `observaciones` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `folio_unique` (`folio`),
  INDEX `pipa_idx` (`pipa_id`),
  INDEX `empresa_idx` (`empresa_id`),
  INDEX `estacion_idx` (`estacion_id`),
  INDEX `fecha_idx` (`fecha_hora`),
  CONSTRAINT `fk_suministros_pipa` FOREIGN KEY (`pipa_id`) REFERENCES `pipas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_suministros_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_suministros_estacion` FOREIGN KEY (`estacion_id`) REFERENCES `estaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_suministros_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Pagos
-- ============================================================================
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` INT(11) NOT NULL,
  `monto` DECIMAL(10,2) NOT NULL,
  `tipo_pago` ENUM('anticipado','normal','credito') NOT NULL DEFAULT 'normal',
  `metodo_pago` ENUM('efectivo','transferencia','cheque','tarjeta') NOT NULL,
  `referencia` VARCHAR(100) NULL,
  `fecha_pago` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` INT(11) NULL,
  `observaciones` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `empresa_idx` (`empresa_id`),
  INDEX `fecha_idx` (`fecha_pago`),
  CONSTRAINT `fk_pagos_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pagos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Tarifas
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tarifas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` INT(11) NULL,
  `precio_por_litro` DECIMAL(10,4) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `empresa_idx` (`empresa_id`),
  INDEX `activa_idx` (`activa`),
  CONSTRAINT `fk_tarifas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Auditoría (Bitácora del Sistema)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NULL,
  `accion` VARCHAR(100) NOT NULL,
  `tabla_afectada` VARCHAR(50) NOT NULL,
  `registro_id` INT(11) NULL,
  `descripcion` TEXT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `fecha_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `usuario_idx` (`usuario_id`),
  INDEX `fecha_idx` (`fecha_hora`),
  INDEX `tabla_idx` (`tabla_afectada`),
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tabla de Configuración del Sistema
-- ============================================================================
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(50) NOT NULL,
  `valor` TEXT NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
