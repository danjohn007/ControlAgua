<?php
/**
 * Modelo Pago
 */

class Pago extends Model {
    protected $table = 'pagos';

    /**
     * Obtener pagos con detalles
     */
    public function getAllWithDetails($limit = 50) {
        $sql = "SELECT p.*, 
                    e.razon_social as empresa_nombre,
                    u.nombre as usuario_nombre
                FROM {$this->table} p
                INNER JOIN empresas e ON p.empresa_id = e.id
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.fecha_pago DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    /**
     * Obtener pagos por empresa
     */
    public function getByEmpresa($empresaId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT p.*, u.nombre as usuario_nombre
                FROM {$this->table} p
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.empresa_id = ?";
        
        $params = [$empresaId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(p.fecha_pago) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY p.fecha_pago DESC";
        return $this->db->query($sql, $params);
    }

    /**
     * Registrar pago y actualizar saldo de empresa
     */
    public function registrarPago($data) {
        $db = $this->db;
        
        try {
            $db->beginTransaction();
            
            // Insertar pago
            $pagoId = $this->insert($data);
            
            if (!$pagoId) {
                throw new Exception("Error al registrar pago");
            }
            
            // Actualizar saldo de empresa
            $empresaModel = new Empresa();
            $empresaModel->updateSaldo($data['empresa_id'], $data['monto'], 'sumar');
            
            $db->commit();
            return $pagoId;
            
        } catch (Exception $e) {
            $db->rollback();
            error_log("Error en registrarPago: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener total de pagos por periodo
     */
    public function getTotalByPeriodo($fechaInicio, $fechaFin) {
        $sql = "SELECT 
                    COUNT(*) as total_pagos,
                    SUM(monto) as total_monto,
                    tipo_pago,
                    metodo_pago
                FROM {$this->table}
                WHERE DATE(fecha_pago) BETWEEN ? AND ?
                GROUP BY tipo_pago, metodo_pago";
        return $this->db->query($sql, [$fechaInicio, $fechaFin]);
    }
}
