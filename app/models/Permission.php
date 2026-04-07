<?php
class Permission extends Model {
    protected $table = 'permissions';

    public function getAllGroupedByModule() {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY module ASC, action ASC");
        $results = $this->db->fetchAll();
        
        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row['module']][] = $row;
        }
        return $grouped;
    }

    public function getRolePermissions($role_id) {
        $this->db->query("SELECT permission_id FROM role_permissions WHERE role_id = :role_id");
        $this->db->bind(':role_id', $role_id);
        return array_column($this->db->fetchAll(), 'permission_id');
    }

    public function syncRolePermissions($role_id, $permission_ids) {
        try {
            $this->db->beginTransaction();
            
            // Clear existing
            $this->db->query("DELETE FROM role_permissions WHERE role_id = :role_id");
            $this->db->bind(':role_id', $role_id);
            $this->db->execute();

            // Insert new
            if (!empty($permission_ids)) {
                $this->db->query("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
                foreach ($permission_ids as $pid) {
                    $this->db->bind(':role_id', $role_id);
                    $this->db->bind(':permission_id', $pid);
                    $this->db->execute();
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
}