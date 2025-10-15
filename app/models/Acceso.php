<?php
/**
 * Modelo Acceso
 */

class Acceso extends Model {
    protected $table = 'accesos';

    /**
     * Registrar acceso
     */
    public function registrar($data) {
        return $this->insert($data);
    }

    /**
     * Obtener accesos recientes
     */
    public function getRecientes($limit = 20) {
        $sql = "SELECT a.*, 
                    p.matricula as pipa_matricula,
                    e.nombre as estacion_nombre,
                    em.razon_social as empresa_nombre,
                    u.nombre as usuario_nombre
                FROM {$this->table} a
                INNER JOIN pipas p ON a.pipa_id = p.id
                INNER JOIN empresas em ON p.empresa_id = em.id
                INNER JOIN estaciones e ON a.estacion_id = e.id
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                ORDER BY a.fecha_hora DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    /**
     * Obtener accesos por pipa
     */
    public function getByPipa($pipaId, $limit = 50) {
        $sql = "SELECT a.*, e.nombre as estacion_nombre, u.nombre as usuario_nombre
                FROM {$this->table} a
                INNER JOIN estaciones e ON a.estacion_id = e.id
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.pipa_id = ?
                ORDER BY a.fecha_hora DESC
                LIMIT ?";
        return $this->db->query($sql, [$pipaId, $limit]);
    }

    /**
     * Obtener accesos por estación
     */
    public function getByEstacion($estacionId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT a.*, 
                    p.matricula as pipa_matricula,
                    em.razon_social as empresa_nombre
                FROM {$this->table} a
                INNER JOIN pipas p ON a.pipa_id = p.id
                INNER JOIN empresas em ON p.empresa_id = em.id
                WHERE a.estacion_id = ?";
        
        $params = [$estacionId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(a.fecha_hora) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY a.fecha_hora DESC";
        return $this->db->query($sql, $params);
    }

    /**
     * Verificar si hay entrada sin salida
     */
    public function tieneEntradaSinSalida($pipaId, $estacionId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE pipa_id = ? AND estacion_id = ? AND tipo_acceso = 'entrada'
                AND NOT EXISTS (
                    SELECT 1 FROM {$this->table} a2 
                    WHERE a2.pipa_id = {$this->table}.pipa_id 
                    AND a2.estacion_id = {$this->table}.estacion_id
                    AND a2.tipo_acceso = 'salida'
                    AND a2.fecha_hora > {$this->table}.fecha_hora
                )
                ORDER BY fecha_hora DESC
                LIMIT 1";
        return $this->db->queryOne($sql, [$pipaId, $estacionId]);
    }
}
