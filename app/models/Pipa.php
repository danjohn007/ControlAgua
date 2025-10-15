<?php
/**
 * Modelo Pipa
 */

class Pipa extends Model {
    protected $table = 'pipas';

    /**
     * Obtener pipas con información de empresa
     */
    public function getAllWithEmpresa() {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre 
                FROM {$this->table} p
                INNER JOIN empresas e ON p.empresa_id = e.id
                ORDER BY p.id DESC";
        return $this->db->query($sql);
    }

    /**
     * Obtener pipas por empresa
     */
    public function getByEmpresa($empresaId) {
        $sql = "SELECT * FROM {$this->table} WHERE empresa_id = ? ORDER BY matricula";
        return $this->db->query($sql, [$empresaId]);
    }

    /**
     * Obtener pipas activas
     */
    public function getActivas() {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre 
                FROM {$this->table} p
                INNER JOIN empresas e ON p.empresa_id = e.id
                WHERE p.estado = 'activa'
                ORDER BY p.matricula";
        return $this->db->query($sql);
    }

    /**
     * Verificar si matrícula existe
     */
    public function matriculaExists($matricula, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE matricula = ?";
        $params = [$matricula];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->queryOne($sql, $params);
        return $result['total'] > 0;
    }

    /**
     * Obtener pipa por código QR
     */
    public function getByCodigoQR($codigoQR) {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre, e.estado as empresa_estado
                FROM {$this->table} p
                INNER JOIN empresas e ON p.empresa_id = e.id
                WHERE p.codigo_qr = ?";
        return $this->db->queryOne($sql, [$codigoQR]);
    }

    /**
     * Generar código QR único
     */
    public function generateUniqueQR($empresaId) {
        do {
            $codigoQR = 'QR-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $exists = $this->search('codigo_qr', $codigoQR);
        } while (!empty($exists));
        
        return $codigoQR;
    }

    /**
     * Obtener historial de suministros de una pipa
     */
    public function getHistorialSuministros($pipaId, $limit = 10) {
        $sql = "SELECT s.*, e.nombre as estacion_nombre, u.nombre as usuario_nombre
                FROM suministros s
                LEFT JOIN estaciones e ON s.estacion_id = e.id
                LEFT JOIN usuarios u ON s.usuario_id = u.id
                WHERE s.pipa_id = ?
                ORDER BY s.fecha_hora DESC
                LIMIT ?";
        return $this->db->query($sql, [$pipaId, $limit]);
    }

    /**
     * Buscar pipas
     */
    public function buscar($termino) {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre 
                FROM {$this->table} p
                INNER JOIN empresas e ON p.empresa_id = e.id
                WHERE p.matricula LIKE ? OR p.operador_nombre LIKE ? OR p.numero_serie LIKE ?
                ORDER BY p.matricula";
        $param = "%{$termino}%";
        return $this->db->query($sql, [$param, $param, $param]);
    }
}
