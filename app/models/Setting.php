<?php
class Setting extends Model {
    protected $table = 'settings';

    /**
     * ==========================================
     * ENTERPRISE CONFIGURATION AUTO-HEALER
     * Upgrades legacy table and seeds safe defaults
     * ==========================================
     */
    private function ensureTable() {
        // 1. Ensure base table exists (Legacy Support)
        $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `setting_key` VARCHAR(100) PRIMARY KEY,
            `setting_value` TEXT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // 2. Active Schema Injection (Adds Enterprise tracking columns without losing data)
        try { $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `updated_by` INT UNSIGNED NULL"); $this->db->execute(); } catch (Exception $e) {}
        try { $this->db->query("ALTER TABLE `{$this->table}` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"); $this->db->execute(); } catch (Exception $e) {}

        // 3. 🚀 Seed Enterprise Defaults (Includes NEW Localization Keys)
        $defaults = [
            'company_name' => 'Enterprise ERP Solutions',
            'support_email' => 'it-support@enterprise.com',
            'system_timezone' => 'UTC',
            'currency_symbol' => '$',     // NEW
            'currency_code' => 'USD',     // NEW
            'system_language' => 'en',    // NEW
            'maintenance_mode' => '0',
            'session_timeout_minutes' => '120',
            'max_file_upload_mb' => '10',
            'password_policy' => 'strict'
        ];

        foreach($defaults as $key => $val) {
            $this->db->query("INSERT IGNORE INTO `{$this->table}` (setting_key, setting_value) VALUES (:k, :v)");
            $this->db->bind(':k', $key);
            $this->db->bind(':v', $val);
            $this->db->execute();
        }
    }

    /**
     * Fetch all settings and format them into a clean Key => Value array
     */
    public function getAllSettings() {
        $this->ensureTable(); // Trigger the Healer before fetching

        try {
            $this->db->query("SELECT * FROM {$this->table}");
            $results = $this->db->fetchAll();
            
            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        } catch (Exception $e) {
            return []; // Failsafe
        }
    }

    /**
     * Elite Update Engine
     */
    public function updateSettings($data, $admin_id = null) {
        $this->ensureTable(); // Trigger the Healer

        foreach ($data as $key => $value) {
            if ($admin_id !== null) {
                $this->db->query("INSERT INTO {$this->table} (setting_key, setting_value, updated_by) 
                                  VALUES (:key, :value, :admin) 
                                  ON DUPLICATE KEY UPDATE setting_value = :val2, updated_by = :admin2");
                $this->db->bind(':key', $key);
                $this->db->bind(':value', $value);
                $this->db->bind(':admin', $admin_id);
                $this->db->bind(':val2', $value);
                $this->db->bind(':admin2', $admin_id);
            } else {
                $this->db->query("INSERT INTO {$this->table} (setting_key, setting_value) 
                                  VALUES (:key, :value) 
                                  ON DUPLICATE KEY UPDATE setting_value = :val2");
                $this->db->bind(':key', $key);
                $this->db->bind(':value', $value);
                $this->db->bind(':val2', $value);
            }
            $this->db->execute();
        }
        return true;
    }
}