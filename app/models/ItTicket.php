<?php
class ItTicket extends Model {
    
    /**
     * ==========================================
     * ENTERPRISE ITSM AUTO-HEALER
     * Automatically builds and upgrades the IT Help Desk table
     * ==========================================
     */
    private function ensureTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `it_tickets` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `ticket_type` ENUM('technical_issue', 'asset_request', 'software_access', 'other') DEFAULT 'technical_issue',
            `asset_type` VARCHAR(100) NULL,
            `subject` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `priority` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
            `status` ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // 🚀 ELITE FIX: Auto-Healer to inject the asset column into existing tables
        try { 
            $this->db->query("ALTER TABLE it_tickets ADD COLUMN asset_type VARCHAR(100) NULL AFTER ticket_type"); 
            $this->db->execute(); 
        } catch (Exception $e) {}
    }

    public function createTicket($data) {
        $this->ensureTable();
        $this->db->query("INSERT INTO it_tickets 
            (user_id, ticket_type, asset_type, subject, description, priority, status) 
            VALUES (:uid, :type, :asset, :subject, :desc, :priority, 'open')");
            
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':type', $data['ticket_type']);
        $this->db->bind(':asset', $data['asset_type']); // 🚀 Asset Tracker
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':priority', $data['priority']);
        
        return $this->db->execute();
    }

    public function getUserTickets($user_id) {
        $this->ensureTable();
        $this->db->query("SELECT * FROM it_tickets WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $user_id);
        return $this->db->fetchAll();
    }

    public function getTicketStats($user_id) {
        $this->ensureTable();
        $this->db->query("SELECT 
            COUNT(id) as total,
            SUM(CASE WHEN status IN ('open', 'in_progress') THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status IN ('resolved', 'closed') THEN 1 ELSE 0 END) as resolved
            FROM it_tickets WHERE user_id = :uid");
        $this->db->bind(':uid', $user_id);
        $result = $this->db->fetch();
        
        return [
            'total' => $result['total'] ?? 0,
            'active' => $result['active'] ?? 0,
            'resolved' => $result['resolved'] ?? 0
        ];
    }
    /**
     * ==========================================
     * GLOBAL IT ADMIN ENGINE
     * For the IT Manager Dashboard (Role 5)
     * ==========================================
     */

    // Fetches every ticket in the company, joining the user data
    public function getAllTickets() {
        $this->ensureTable();
        $this->db->query("
            SELECT t.*, u.first_name, u.last_name, u.email, u.role_id 
            FROM it_tickets t
            JOIN users u ON t.user_id = u.id
            ORDER BY 
                CASE WHEN t.priority = 'critical' THEN 1
                     WHEN t.priority = 'high' THEN 2
                     WHEN t.priority = 'medium' THEN 3
                     ELSE 4 END ASC,
                t.created_at DESC
        ");
        return $this->db->fetchAll();
    }

    // Fetches company-wide IT statistics
    public function getGlobalStats() {
        $this->ensureTable();
        $this->db->query("SELECT 
            COUNT(id) as total,
            SUM(CASE WHEN status IN ('open', 'in_progress') THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status IN ('resolved', 'closed') THEN 1 ELSE 0 END) as resolved,
            SUM(CASE WHEN ticket_type = 'asset_request' AND status != 'resolved' THEN 1 ELSE 0 END) as pending_assets,
            SUM(CASE WHEN priority = 'critical' AND status != 'resolved' THEN 1 ELSE 0 END) as critical_issues
            FROM it_tickets");
        $result = $this->db->fetch();
        
        return [
            'total' => $result['total'] ?? 0,
            'active' => $result['active'] ?? 0,
            'resolved' => $result['resolved'] ?? 0,
            'pending_assets' => $result['pending_assets'] ?? 0,
            'critical_issues' => $result['critical_issues'] ?? 0
        ];
    }

    // Allows IT to update the status of a ticket
    public function updateStatus($ticket_id, $status) {
        $this->db->query("UPDATE it_tickets SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $ticket_id);
        return $this->db->execute();
    }
}