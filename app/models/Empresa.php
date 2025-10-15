<?php
/**
 * Modelo Empresa
 */

class Empresa extends Model {
    protected $table = 'empresas';

    /**
     * Obtener empresas activas
     */
    public function getActivas() {
        $sql = "SELECT * FROM {$this->table} WHERE estado = 'activa' ORDER BY razon_social";
        return $this->db->query($sql);
    }

    /**
     * Verificar si RFC existe
     */
    public function rfcExists($rfc, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE rfc = ?";
        $params = [$rfc];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->queryOne($sql, $params);
        return $result['total'] > 0;
    }

    /**
     * Actualizar saldo
     */
    public function updateSaldo($empresaId, $monto, $operacion = 'sumar') {
        if ($operacion === 'sumar') {
            $sql = "UPDATE {$this->table} SET saldo_actual = saldo_actual + ? WHERE id = ?";
        } else {
            $sql = "UPDATE {$this->table} SET saldo_actual = saldo_actual - ? WHERE id = ?";
        }
        return $this->db->execute($sql, [$monto, $empresaId]);
    }

    /**
     * Obtener empresas con saldo
     */
    public function getWithSaldo() {
        $sql = "SELECT * FROM {$this->table} WHERE saldo_actual > 0 ORDER BY razon_social";
        return $this->db->query($sql);
    }

    /**
     * Obtener estadísticas de empresa
     */
    public function getEstadisticas($empresaId) {
        $sql = "SELECT 
                    e.id,
                    e.razon_social,
                    e.saldo_actual,
                    e.credito_autorizado,
                    COUNT(DISTINCT p.id) as total_pipas,
                    COUNT(DISTINCT s.id) as total_suministros,
                    COALESCE(SUM(s.litros_suministrados), 0) as total_litros,
                    COALESCE(SUM(s.total_cobrado), 0) as total_cobrado
                FROM {$this->table} e
                LEFT JOIN pipas p ON p.empresa_id = e.id
                LEFT JOIN suministros s ON s.empresa_id = e.id
                WHERE e.id = ?
                GROUP BY e.id";
        return $this->db->queryOne($sql, [$empresaId]);
    }

    /**
     * Buscar empresas
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE razon_social LIKE ? OR rfc LIKE ? OR representante_legal LIKE ?
                ORDER BY razon_social";
        $param = "%{$termino}%";
        return $this->db->query($sql, [$param, $param, $param]);
    }
}
