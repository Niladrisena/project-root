<?php
class ActivityLog extends Model {
    protected $table = 'activity_logs';

    /**
     * Enterprise Audit Log
     * Records all critical user actions across the system securely.
     */
    public function log($user_id, $action, $module) {
        // Securely fetch IP Address (handling proxies if necessary)
        $ip = $_SERVER['HTTP_CLIENT_IP'] 
            ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? 'UNKNOWN';

        $this->db->query("INSERT INTO {$this->table} (user_id, action, module, ip_address) VALUES (:user_id, :action, :module, :ip)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':module', $module);
        $this->db->bind(':ip', $ip);
        $this->db->execute();
    }
}