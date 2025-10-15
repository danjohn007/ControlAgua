<?php
/**
 * Modelo Estacion
 */

class Estacion extends Model {
    protected $table = 'estaciones';

    /**
     * Obtener estaciones activas
     */
    public function getActivas() {
        $sql = "SELECT * FROM {$this->table} WHERE activa = 1 ORDER BY nombre";
        return $this->db->query($sql);
    }

    /**
     * Obtener estadísticas de estación
     */
    public function getEstadisticas($estacionId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT 
                    e.id,
                    e.nombre,
                    e.capacidad_diaria,
                    COUNT(DISTINCT s.id) as total_suministros,
                    COALESCE(SUM(s.litros_suministrados), 0) as total_litros,
                    COUNT(DISTINCT a.id) as total_accesos
                FROM {$this->table} e
                LEFT JOIN suministros s ON s.estacion_id = e.id
                LEFT JOIN accesos a ON a.estacion_id = e.id
                WHERE e.id = ?";
        
        $params = [$estacionId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(s.fecha_hora) BETWEEN ? AND ?
                      AND DATE(a.fecha_hora) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " GROUP BY e.id";
        return $this->db->queryOne($sql, $params);
    }
}
