<?php
class Model {
    protected $db;
    protected $table;
    protected $company_id;

    public function __construct() {
        $this->db = Database::getInstance();
        
        // Globally scope every instantiated model to the logged-in user's company (Multi-Tenant)
        if (Session::get('company_id')) {
            $this->company_id = Session::get('company_id');
        }
    }

    /**
     * Enterprise Multi-Tenant Helper
     * Dynamically injects company_id isolation into queries.
     */
    protected function applyTenantScope($sql) {
        if (!$this->company_id) return $sql;
        
        $injection = " company_id = {$this->company_id} ";
        if (stripos($sql, 'WHERE') !== false) {
            return str_ireplace('WHERE', "WHERE {$injection} AND", $sql);
        }
        return $sql . " WHERE {$injection}";
    }

    /**
     * Universal Find All
     * FIXED: Removed hardcoded 'deleted_at' to prevent 1054 errors on standard tables
     */
    public function findAll() {
        $sql = $this->applyTenantScope("SELECT * FROM {$this->table}");
        $this->db->query($sql);
        return $this->db->fetchAll();
    }

    /**
     * Universal Find By ID
     * FIXED: Removed hardcoded 'deleted_at'
     */
    public function findById($id) {
        $sql = $this->applyTenantScope("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->fetch();
    }
    
    /**
     * Universal Delete (Physical)
     */
    public function delete($id) {
        $sql = $this->applyTenantScope("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}