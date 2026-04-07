<?php
class Asset extends Model {
    
    /**
     * ==========================================
     * ENTERPRISE EAM AUTO-HEALER
     * Guarantees the Asset & History tables exist
     * ==========================================
     */
    private function ensureTables() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `assets` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `asset_tag` VARCHAR(50) UNIQUE NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `brand_model` VARCHAR(255) NOT NULL,
            `serial_number` VARCHAR(100) NULL,
            `status` ENUM('available', 'deployed', 'maintenance', 'damaged', 'retired') DEFAULT 'available',
            `assigned_to` INT UNSIGNED NULL,
            `purchase_cost` DECIMAL(10,2) DEFAULT 0.00,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        $this->db->query("CREATE TABLE IF NOT EXISTS `asset_history` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `asset_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NULL,
            `action_type` VARCHAR(100) NOT NULL,
            `notes` TEXT NULL,
            `logged_by` INT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    public function getStats() {
        $this->ensureTables();
        $this->db->query("SELECT 
            COUNT(id) as total,
            SUM(CASE WHEN status = 'deployed' THEN 1 ELSE 0 END) as deployed,
            SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
            SUM(CASE WHEN status IN ('maintenance', 'damaged') THEN 1 ELSE 0 END) as repairs
            FROM assets");
        return $this->db->fetch();
    }

    public function getAllAssets() {
        $this->ensureTables();
        $this->db->query("
            SELECT a.*, u.first_name, u.last_name, u.email 
            FROM assets a
            LEFT JOIN users u ON a.assigned_to = u.id
            ORDER BY a.created_at DESC
        ");
        return $this->db->fetchAll();
    }

    public function getActiveEmployees() {
        $this->db->query("SELECT id, first_name, last_name, email FROM users WHERE status = 'active' ORDER BY first_name ASC");
        return $this->db->fetchAll();
    }

    // 1. REGISTER NEW ASSET
    public function registerAsset($data, $admin_id) {
        $this->ensureTables();
        $this->db->query("INSERT INTO assets (asset_tag, category, brand_model, serial_number, purchase_cost) 
                          VALUES (:tag, :cat, :model, :serial, :cost)");
        
        $tag = 'AST-' . strtoupper(substr(uniqid(), -6)); // Auto-generate tag
        $this->db->bind(':tag', $tag);
        $this->db->bind(':cat', $data['category']);
        $this->db->bind(':model', $data['brand_model']);
        $this->db->bind(':serial', $data['serial_number']);
        $this->db->bind(':cost', $data['purchase_cost']);
        
        if ($this->db->execute()) {
            $asset_id = $this->db->lastInsertId();
            $this->logHistory($asset_id, null, 'Registered', 'Initial entry into corporate inventory.', $admin_id);
            return true;
        }
        return false;
    }

    // 2. ASSIGN ASSET TO EMPLOYEE
    public function assignAsset($asset_id, $user_id, $notes, $admin_id) {
        $this->db->query("UPDATE assets SET status = 'deployed', assigned_to = :uid WHERE id = :aid");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':aid', $asset_id);
        
        if ($this->db->execute()) {
            $this->logHistory($asset_id, $user_id, 'Assigned', $notes, $admin_id);
            return true;
        }
        return false;
    }

    // 3. PROCESS RETURN OR DAMAGE REPORT
    public function processReturn($asset_id, $new_status, $notes, $admin_id) {
        // Fetch who had it last before wiping it
        $this->db->query("SELECT assigned_to FROM assets WHERE id = :aid");
        $this->db->bind(':aid', $asset_id);
        $last_user = $this->db->fetch()['assigned_to'] ?? null;

        $this->db->query("UPDATE assets SET status = :status, assigned_to = NULL WHERE id = :aid");
        $this->db->bind(':status', $new_status);
        $this->db->bind(':aid', $asset_id);
        
        if ($this->db->execute()) {
            $action = ($new_status === 'damaged') ? 'Damage Reported' : 'Returned to IT';
            $this->logHistory($asset_id, $last_user, $action, $notes, $admin_id);
            return true;
        }
        return false;
    }

    // UTILITY: Writes to the immutable ledger
    private function logHistory($asset_id, $user_id, $action, $notes, $admin_id) {
        $this->db->query("INSERT INTO asset_history (asset_id, user_id, action_type, notes, logged_by) 
                          VALUES (:aid, :uid, :act, :notes, :admin)");
        $this->db->bind(':aid', $asset_id);
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':act', $action);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':admin', $admin_id);
        $this->db->execute();
    }
     /**
     * Get assets assigned to the logged-in user
     */
    public function getMyAssets($user_id)
    {
        try {

            $this->db->query("
                SELECT id, name, asset_tag, status, assigned_to, created_at
                FROM {$this->table}
                WHERE assigned_to = :uid
                ORDER BY created_at DESC
            ");

            $this->db->bind(':uid', $user_id);

            return $this->db->fetchAll();

        } catch (Exception $e) {

            error_log("Asset Model Error: " . $e->getMessage());

            return [];

        }
    }
}