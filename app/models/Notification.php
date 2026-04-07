<?php
class Notification extends Model {
    
    /**
     * ==========================================
     * AUTO-HEALER: Builds the Notification ledger
     * ==========================================
     */
    private function ensureTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `notifications` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `link` VARCHAR(255) DEFAULT '#',
            `is_read` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * Fetches up to 10 unread notifications for the active user
     */
    public function getUnread($user_id) {
        $this->ensureTable();
        $this->db->query("SELECT * FROM notifications WHERE user_id = :uid AND is_read = 0 ORDER BY created_at DESC LIMIT 10");
        $this->db->bind(':uid', $user_id);
        return $this->db->fetchAll();
    }

    /**
     * Marks a specific notification as read securely
     */
    public function markAsRead($id, $user_id) {
        $this->ensureTable();
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :uid");
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }

    /**
     * Clears the entire notification tray
     */
    public function markAllAsRead($user_id) {
        $this->ensureTable();
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE user_id = :uid");
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }

    /**
     * ELITE HELPER: Use this anywhere in your other controllers to trigger an alert!
     * Example: $this->model('Notification')->send($pm_id, 'New Ticket', 'A laptop was requested.');
     */
    public function send($user_id, $title, $message, $link = '#') {
        $this->ensureTable();
        $this->db->query("INSERT INTO notifications (user_id, title, message, link) VALUES (:uid, :title, :msg, :link)");
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':title', $title);
        $this->db->bind(':msg', $message);
        $this->db->bind(':link', $link);
        return $this->db->execute();
    }
}