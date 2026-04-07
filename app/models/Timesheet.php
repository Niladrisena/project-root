<?php
class Timesheet extends Model {
    protected $table = 'timesheets';

    private function ensureSchema() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `timesheets` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `project_id` INT UNSIGNED NOT NULL,
            `date` DATE NOT NULL,
            `hours_worked` DECIMAL(5,2) NOT NULL,
            `description` TEXT,
            `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * GOD-LEVEL FIX: 
     * 1. Removed 't' and 'p' aliases to bypass MySQL Strict Mode crashes.
     * 2. Upgraded to LEFT JOIN so 'General Administrative' (ID 0) logs are never hidden!
     */
    public function getPersonalLogs($user_id) {
        $this->ensureSchema();
        
        $this->db->query("SELECT timesheets.*, projects.name as project_name 
                          FROM timesheets 
                          LEFT JOIN projects ON timesheets.project_id = projects.id 
                          WHERE timesheets.user_id = :uid 
                          ORDER BY timesheets.date DESC LIMIT 20");
        
        $this->db->bind(':uid', $user_id);
        return $this->db->fetchAll();
    }

    public function getWeeklyBreakdown($user_id) {
        $this->ensureSchema(); // Ensure table exists before chart data is fetched
        
        // Fetches hours per day for the last 7 days for the Chart
        $this->db->query("SELECT date, SUM(hours_worked) as total 
                          FROM timesheets 
                          WHERE user_id = :uid AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                          GROUP BY date ORDER BY date ASC");
        
        $this->db->bind(':uid', $user_id);
        return $this->db->fetchAll();
    }

   public function addEntry($data) {
        $this->ensureSchema();
        
        $this->db->query("INSERT INTO timesheets (user_id, project_id, date, hours_worked, description, status) 
                          VALUES (:uid, :pid, :date, :hours, :desc, :status)");
        
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':pid', $data['project_id']);
        $this->db->bind(':date', $data['date']);
        
        // ELITE FIX: Changed from $data['hours'] to $data['hours_worked'] to match the Controller payload perfectly!
        $this->db->bind(':hours', $data['hours_worked']); 
        
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }
}