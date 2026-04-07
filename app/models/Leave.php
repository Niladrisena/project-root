<?php
class Leave extends Model {
    protected $table = 'leave_requests';

    public function getPendingRequests($manager_user_id = null) {
        // If manager_id is passed, only show requests for their direct reports
        $sql = "SELECT lr.*, lt.name as leave_type, u.first_name, u.last_name 
                FROM {$this->table} lr 
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                WHERE lr.status = 'pending'";
                
        if ($manager_user_id) {
            $sql .= " AND e.manager_id = :mgr";
            $this->db->query($sql);
            $this->db->bind(':mgr', $manager_user_id);
        } else {
            $this->db->query($sql);
        }
        
        return $this->db->fetchAll();
    }

    public function updateStatus($id, $status, $reviewer_id) {
        $this->db->query("UPDATE {$this->table} SET status = :status, reviewed_by = :uid, reviewed_at = NOW() WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':uid', $reviewer_id);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    /**
     * ==========================================
     * EMPLOYEE WORKSPACE MODULE
     * Self-Healing Schema & Employee Leave Methods
     * ==========================================
     */

    private function ensureLeaveTables() {
        // Guarantee Leave Applications Table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `leaves` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `leave_type` VARCHAR(50) NOT NULL,
            `start_date` DATE NOT NULL,
            `end_date` DATE NOT NULL,
            `reason` TEXT,
            `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // Guarantee Leave Balances Table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `leave_balances` (
            `user_id` INT UNSIGNED PRIMARY KEY,
            `total_leaves` INT DEFAULT 12,
            `used_leaves` INT DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    public function getLeaveHistory($user_id) {
        $this->ensureLeaveTables();
        $this->db->query("SELECT * FROM leaves WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $user_id);
        return $this->db->fetchAll();
    }

    public function getLeaveBalance($user_id) {
        $this->ensureLeaveTables();
        
        // Ensure a balance row exists for this user, if not, Auto-Heal and create it!
        $this->db->query("SELECT * FROM leave_balances WHERE user_id = :uid LIMIT 1");
        $this->db->bind(':uid', $user_id);
        $balance = $this->db->fetch();

        if (!$balance) {
            $this->db->query("INSERT INTO leave_balances (user_id, total_leaves, used_leaves) VALUES (:uid, 12, 0)");
            $this->db->bind(':uid', $user_id);
            $this->db->execute();
            return ['total_leaves' => 12, 'used_leaves' => 0];
        }
        return $balance;
    }

    public function applyForLeave($data) {
        $this->ensureLeaveTables();
        $this->db->query("INSERT INTO leaves (user_id, leave_type, start_date, end_date, reason, status) 
                          VALUES (:uid, :type, :start, :end, :reason, 'pending')");
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':type', $data['leave_type']);
        $this->db->bind(':start', $data['start_date']);
        $this->db->bind(':end', $data['end_date']);
        $this->db->bind(':reason', $data['reason']);
        return $this->db->execute();
    }
    /**
     * ==========================================
     * HR LEAVE APPROVAL ENGINE
     * ==========================================
     */
    
    // Fetches all leaves across the company for HR to review
    public function getAllLeaves() {
        $this->ensureLeaveTables();
        $this->db->query("SELECT l.*, u.first_name, u.last_name, u.email 
                          FROM leaves l 
                          JOIN users u ON l.user_id = u.id 
                          ORDER BY 
                            CASE WHEN l.status = 'pending' THEN 1 ELSE 2 END, 
                            l.created_at DESC");
        return $this->db->fetchAll();
    }

    // Processes the leave and automatically deducts the balance if approved
    public function updateLeaveStatus($leave_id, $status) {
        try {
            $this->db->beginTransaction();

            // 1. Update the leave status
            $this->db->query("UPDATE leaves SET status = :status WHERE id = :id");
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $leave_id);
            $this->db->execute();

            // 2. SMART BALANCING: If approved, deduct days from the employee's balance
            if ($status === 'approved') {
                $this->db->query("SELECT user_id, start_date, end_date FROM leaves WHERE id = :id");
                $this->db->bind(':id', $leave_id);
                $leave = $this->db->fetch();

                if ($leave) {
                    // Calculate total days (inclusive)
                    $start = new DateTime($leave['start_date']);
                    $end = new DateTime($leave['end_date']);
                    $days = $end->diff($start)->days + 1; 

                    $this->db->query("UPDATE leave_balances 
                                      SET used_leaves = used_leaves + :days 
                                      WHERE user_id = :uid");
                    $this->db->bind(':days', $days);
                    $this->db->bind(':uid', $leave['user_id']);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}