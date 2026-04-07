<?php

class Attendance extends Model {

    protected $table = 'attendance';

    private function ensureTableExists() {

        $this->db->query("CREATE TABLE IF NOT EXISTS `attendance` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `clock_in` DATETIME DEFAULT NULL,
            `clock_out` DATETIME DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT 'present',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_clockin (user_id, clock_in)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->db->execute();
    }

    public function clock_in($user_id) {

        $this->ensureTableExists();

        $time = date('Y-m-d H:i:s');

        // Prevent double clock-in
        $this->db->query("
            SELECT id FROM {$this->table}
            WHERE user_id = :uid
            AND DATE(clock_in) = CURDATE()
        ");

        $this->db->bind(':uid', $user_id);

        if ($this->db->fetch()) {
            return false;
        }

        $this->db->query("
            INSERT INTO {$this->table}
            (user_id, clock_in, status)
            VALUES (:uid, :time, 'present')
        ");

        $this->db->bind(':uid', $user_id);
        $this->db->bind(':time', $time);

        return $this->db->execute();
    }

    public function clock_out($user_id) {

        $this->ensureTableExists();

        $time = date('Y-m-d H:i:s');

        $this->db->query("
            UPDATE {$this->table}
            SET clock_out = :time
            WHERE user_id = :uid
            AND DATE(clock_in) = CURDATE()
        ");

        $this->db->bind(':uid', $user_id);
        $this->db->bind(':time', $time);

        return $this->db->execute();
    }

    public function getTodayStatus($user_id) {

        $this->ensureTableExists();

        $this->db->query("
            SELECT clock_in, clock_out
            FROM {$this->table}
            WHERE user_id = :uid
            AND DATE(clock_in) = CURDATE()
            LIMIT 1
        ");

        $this->db->bind(':uid', $user_id);

        return $this->db->fetch();
    }
}