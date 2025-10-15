<?php
/**
 * Modelo Suministro
 */

class Suministro extends Model {
    protected $table = 'suministros';

    /**
     * Obtener suministros con detalles
     */
    public function getAllWithDetails($limit = 50) {
        $sql = "SELECT s.*, 
                    p.matricula as pipa_matricula,
                    e.razon_social as empresa_nombre,
                    est.nombre as estacion_nombre,
                    u.nombre as usuario_nombre
                FROM {$this->table} s
                INNER JOIN pipas p ON s.pipa_id = p.id
                INNER JOIN empresas e ON s.empresa_id = e.id
                INNER JOIN estaciones est ON s.estacion_id = est.id
                LEFT JOIN usuarios u ON s.usuario_id = u.id
                ORDER BY s.fecha_hora DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    /**
     * Generar folio único
     */
    public function generateFolio() {
        $prefix = 'SUM-' . date('Y') . '-';
        $sql = "SELECT folio FROM {$this->table} WHERE folio LIKE ? ORDER BY id DESC LIMIT 1";
        $lastFolio = $this->db->queryOne($sql, [$prefix . '%']);
        
        if ($lastFolio) {
            $lastNumber = intval(str_replace($prefix, '', $lastFolio['folio']));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Registrar suministro
     */
    public function registrar($data) {
        // Generar folio si no existe
        if (!isset($data['folio'])) {
            $data['folio'] = $this->generateFolio();
        }
        
        // Calcular total si no existe
        if (!isset($data['total_cobrado'])) {
            $data['total_cobrado'] = $data['litros_suministrados'] * $data['tarifa_por_litro'];
        }
        
        return $this->insert($data);
    }

    /**
     * Obtener suministros por empresa
     */
    public function getByEmpresa($empresaId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT s.*, p.matricula as pipa_matricula, est.nombre as estacion_nombre
                FROM {$this->table} s
                INNER JOIN pipas p ON s.pipa_id = p.id
                INNER JOIN estaciones est ON s.estacion_id = est.id
                WHERE s.empresa_id = ?";
        
        $params = [$empresaId];
        
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(s.fecha_hora) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY s.fecha_hora DESC";
        return $this->db->query($sql, $params);
    }

    /**
     * Obtener estadísticas de suministro
     */
    public function getEstadisticas($periodo = 'dia') {
        switch ($periodo) {
            case 'dia':
                $where = "DATE(fecha_hora) = CURDATE()";
                break;
            case 'semana':
                $where = "YEARWEEK(fecha_hora) = YEARWEEK(NOW())";
                break;
            case 'mes':
                $where = "YEAR(fecha_hora) = YEAR(NOW()) AND MONTH(fecha_hora) = MONTH(NOW())";
                break;
            default:
                $where = "1=1";
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_suministros,
                    SUM(litros_suministrados) as total_litros,
                    SUM(total_cobrado) as total_cobrado,
                    AVG(litros_suministrados) as promedio_litros
                FROM {$this->table}
                WHERE {$where}";
        
        return $this->db->queryOne($sql);
    }

    /**
     * Obtener suministros por fecha
     */
    public function getByFecha($fechaInicio, $fechaFin) {
        $sql = "SELECT s.*, 
                    p.matricula as pipa_matricula,
                    e.razon_social as empresa_nombre,
                    est.nombre as estacion_nombre
                FROM {$this->table} s
                INNER JOIN pipas p ON s.pipa_id = p.id
                INNER JOIN empresas e ON s.empresa_id = e.id
                INNER JOIN estaciones est ON s.estacion_id = est.id
                WHERE DATE(s.fecha_hora) BETWEEN ? AND ?
                ORDER BY s.fecha_hora DESC";
        return $this->db->query($sql, [$fechaInicio, $fechaFin]);
    }
}
