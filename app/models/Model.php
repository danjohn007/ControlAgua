<?php
/**
 * Clase Base Model
 * Proporciona funcionalidad común para todos los modelos
 */

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todos los registros
     */
    public function getAll($orderBy = 'id', $order = 'DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        return $this->db->query($sql);
    }

    /**
     * Obtener registro por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    /**
     * Obtener registros con paginación
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE, $orderBy = 'id', $order = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order} LIMIT {$perPage} OFFSET {$offset}";
        return $this->db->query($sql);
    }

    /**
     * Contar registros totales
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->queryOne($sql, $params);
        return $result ? $result['total'] : 0;
    }

    /**
     * Insertar registro
     */
    public function insert($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        
        if ($this->db->execute($sql, $values)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualizar registro
     */
    public function update($id, $data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $values[] = $id;
        
        $set = implode(' = ?, ', $fields) . ' = ?';
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = ?";
        
        return $this->db->execute($sql, $values);
    }

    /**
     * Eliminar registro
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Buscar registros
     */
    public function search($field, $value, $operator = '=') {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} {$operator} ?";
        return $this->db->query($sql, [$value]);
    }
}
