<?php
class TimeManager extends Model {
    
    /**
     * ==========================================
     * ENTERPRISE DUAL SCHEMA ARCHIVER & HEALER
     * Safely backs up incompatible legacy tables and rebuilds them.
     * ==========================================
     */
    private function ensureTables() {
        // ---------------------------------------------------------
        // 1. DIAGNOSTICS & REBUILD: ATTENDANCE TABLE
        // ---------------------------------------------------------
        $attendance_needs_rebuild = false;
        try {
            $this->db->query("SELECT work_date, clock_in FROM attendance LIMIT 1");
            $this->db->execute();
        } catch (\Throwable $e) {
            $attendance_needs_rebuild = true; // Legacy schema detected
        }

        if ($attendance_needs_rebuild) {
            try {
                $backup_name = "attendance_legacy_" . time();
                $this->db->query("RENAME TABLE attendance TO {$backup_name}");
                $this->db->execute();
            } catch (\Throwable $e) {}

            $this->db->query("CREATE TABLE `attendance` (
                `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `work_date` DATE NOT NULL,
                `clock_in` DATETIME NULL,
                `clock_out` DATETIME NULL,
                `status` ENUM('present', 'late', 'half_day') DEFAULT 'present',
                UNIQUE KEY `unique_attendance` (`user_id`, `work_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $this->db->execute();
        }

        // ---------------------------------------------------------
        // 2. DIAGNOSTICS & REBUILD: LEAVE REQUESTS TABLE
        // ---------------------------------------------------------
        $leaves_needs_rebuild = false;
        try {
            // 🚀 ELITE FIX: Testing the Leave Requests table for Enterprise columns
            $this->db->query("SELECT user_id, leave_type FROM leave_requests LIMIT 1");
            $this->db->execute();
        } catch (\Throwable $e) {
            $leaves_needs_rebuild = true; // Legacy schema detected
        }

        if ($leaves_needs_rebuild) {
            try {
                $backup_name = "leave_requests_legacy_" . time();
                $this->db->query("RENAME TABLE leave_requests TO {$backup_name}");
                $this->db->execute();
            } catch (\Throwable $e) {}

            $this->db->query("CREATE TABLE `leave_requests` (
                `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `leave_type` VARCHAR(50) NOT NULL,
                `start_date` DATE NOT NULL,
                `end_date` DATE NOT NULL,
                `reason` TEXT NOT NULL,
                `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                `reviewed_by` INT UNSIGNED NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $this->db->execute();
        }
    }

    /**
     * ==========================================
     * ATTENDANCE ENGINE
     * ==========================================
     */
    public function getTodayAttendance($user_id) {
        $this->ensureTables();
        $this->db->query("SELECT * FROM attendance WHERE user_id = :uid AND work_date = CURDATE()");
        $this->db->bind(':uid', $user_id);
        return $this->db->fetch();
    }

    public function getGlobalAttendanceToday() {
        $this->ensureTables();
        $this->db->query("
            SELECT a.*, u.first_name, u.last_name, u.email 
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            WHERE a.work_date = CURDATE()
            ORDER BY a.clock_in DESC
        ");
        return $this->db->fetchAll();
    }

    public function logClockEvent($user_id) {
        $this->ensureTables();
        $today = $this->getTodayAttendance($user_id);

        if (!$today) {
            // Clock In
            $this->db->query("INSERT INTO attendance (user_id, work_date, clock_in) VALUES (:uid, CURDATE(), NOW())");
            $this->db->bind(':uid', $user_id);
            return $this->db->execute() ? 'clocked_in' : false;
        } elseif (!$today['clock_out']) {
            // Clock Out
            $this->db->query("UPDATE attendance SET clock_out = NOW() WHERE id = :id");
            $this->db->bind(':id', $today['id']);
            return $this->db->execute() ? 'clocked_out' : false;
        }
        return 'already_completed'; 
    }

    /**
     * ==========================================
     * LEAVE MANAGEMENT ENGINE
     * ==========================================
     */
    public function submitLeaveRequest($data) {
        $this->ensureTables();
        $this->db->query("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason) 
                          VALUES (:uid, :type, :start, :end, :reason)");
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':type', $data['leave_type']);
        $this->db->bind(':start', $data['start_date']);
        $this->db->bind(':end', $data['end_date']);
        $this->db->bind(':reason', $data['reason']);
        return $this->db->execute();
    }

    public function getGlobalLeaves() {
        $this->ensureTables();
        $this->db->query("
            SELECT l.*, u.first_name, u.last_name, u.email,
                   DATEDIFF(l.end_date, l.start_date) + 1 AS total_days
            FROM leave_requests l
            JOIN users u ON l.user_id = u.id
            ORDER BY 
                CASE WHEN l.status = 'pending' THEN 1 ELSE 2 END ASC,
                l.created_at DESC
        ");
        return $this->db->fetchAll();
    }

    public function processLeave($leave_id, $status, $manager_id) {
        $this->ensureTables();
        $this->db->query("UPDATE leave_requests SET status = :status, reviewed_by = :mid WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':mid', $manager_id);
        $this->db->bind(':id', $leave_id);
        return $this->db->execute();
    }
    /**
     * ==========================================
     * 🚀 HR EXCLUSIVE: DYNAMIC LEAVE BALANCE ENGINE
     * Calculates remaining PTO (20 days) and Sick Leave (10 days) per employee
     * ==========================================
     */
    public function getLeaveBalances() {
        $this->ensureTables();
        
        // Elite Aggregation Query: Calculates taken leaves and subtracts from yearly allowance
        $this->db->query("
            SELECT u.id, u.first_name, u.last_name, u.email,
                   (20 - COALESCE(SUM(CASE WHEN l.leave_type = 'paid_time_off' AND l.status = 'approved' THEN DATEDIFF(l.end_date, l.start_date) + 1 ELSE 0 END), 0)) as pto_balance,
                   (10 - COALESCE(SUM(CASE WHEN l.leave_type = 'sick_leave' AND l.status = 'approved' THEN DATEDIFF(l.end_date, l.start_date) + 1 ELSE 0 END), 0)) as sick_balance,
                   COALESCE(SUM(CASE WHEN l.leave_type = 'unpaid_leave' AND l.status = 'approved' THEN DATEDIFF(l.end_date, l.start_date) + 1 ELSE 0 END), 0) as unpaid_taken
            FROM users u
            LEFT JOIN leave_requests l ON u.id = l.user_id AND YEAR(l.start_date) = YEAR(CURDATE())
            WHERE u.status = 'active' AND u.role_id != 1
            GROUP BY u.id, u.first_name, u.last_name, u.email
            ORDER BY u.first_name ASC
        ");
        
        return $this->db->fetchAll();
    }
}