<?php
/**
 * Modelo Auditoria
 */

class Auditoria extends Model {
    protected $table = 'auditoria';

    /**
     * Registrar evento de auditoría
     */
    public function registrar($usuarioId, $accion, $tabla, $registroId, $descripcion) {
        $data = [
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'tabla_afectada' => $tabla,
            'registro_id' => $registroId,
            'descripcion' => $descripcion,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        return $this->insert($data);
    }

    /**
     * Obtener auditoría con detalles
     */
    public function getAllWithDetails($limit = 100) {
        $sql = "SELECT a.*, u.nombre as usuario_nombre, u.email as usuario_email
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                ORDER BY a.fecha_hora DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    /**
     * Obtener auditoría por usuario
     */
    public function getByUsuario($usuarioId, $limit = 50) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE usuario_id = ? 
                ORDER BY fecha_hora DESC 
                LIMIT ?";
        return $this->db->query($sql, [$usuarioId, $limit]);
    }

    /**
     * Obtener auditoría por tabla
     */
    public function getByTabla($tabla, $registroId = null, $limit = 50) {
        $sql = "SELECT a.*, u.nombre as usuario_nombre
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.tabla_afectada = ?";
        
        $params = [$tabla];
        
        if ($registroId) {
            $sql .= " AND a.registro_id = ?";
            $params[] = $registroId;
        }
        
        $sql .= " ORDER BY a.fecha_hora DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->query($sql, $params);
    }

    /**
     * Obtener auditoría por rango de fechas
     */
    public function getByFechas($fechaInicio, $fechaFin) {
        $sql = "SELECT a.*, u.nombre as usuario_nombre
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                WHERE DATE(a.fecha_hora) BETWEEN ? AND ?
                ORDER BY a.fecha_hora DESC";
        return $this->db->query($sql, [$fechaInicio, $fechaFin]);
    }
}
