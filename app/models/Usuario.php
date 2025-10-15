<?php
/**
 * Modelo Usuario
 */

class Usuario extends Model {
    protected $table = 'usuarios';

    /**
     * Autenticar usuario
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? AND activo = 1";
        $user = $this->db->queryOne($sql, [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            $this->updateLastAccess($user['id']);
            return $user;
        }
        return false;
    }

    /**
     * Actualizar último acceso
     */
    private function updateLastAccess($userId) {
        $sql = "UPDATE {$this->table} SET ultimo_acceso = NOW() WHERE id = ?";
        $this->db->execute($sql, [$userId]);
    }

    /**
     * Crear nuevo usuario
     */
    public function createUser($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    /**
     * Verificar si email existe
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->queryOne($sql, $params);
        return $result['total'] > 0;
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        return $this->db->execute($sql, [$hashedPassword, $userId]);
    }

    /**
     * Obtener usuarios por rol
     */
    public function getByRole($role) {
        $sql = "SELECT * FROM {$this->table} WHERE rol = ? AND activo = 1 ORDER BY nombre";
        return $this->db->query($sql, [$role]);
    }
}
