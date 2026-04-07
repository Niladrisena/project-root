<?php
class Role extends Model {
    protected $table = 'roles';

    public function getAllRoles() {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY hierarchy_level ASC");
        return $this->db->fetchAll();
    }

    public function createRole($name, $slug, $description, $hierarchy_level = 10) {
        $this->db->query("INSERT INTO {$this->table} (name, slug, is_custom, hierarchy_level, description) VALUES (:name, :slug, 1, :level, :desc)");
        $this->db->bind(':name', $name);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':level', $hierarchy_level);
        $this->db->bind(':desc', $description);
        return $this->db->execute();
    }

    // Protect system roles from deletion
    public function deleteRole($id) {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id AND is_custom = 1");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}