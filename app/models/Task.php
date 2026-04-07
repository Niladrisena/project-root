<?php
class Task extends Model {
    protected $table = 'tasks';

    /* ========================================================
       ADMIN / OWNER MODULE (EXISTING CODE)
       ======================================================== */

    /**
     * Enterprise Task Creation
     * Safely binds parameters to prevent SQL injection before saving.
     */
    public function createTask($data) {
        $this->db->query("INSERT INTO {$this->table} (project_id, title, status, deadline, assigned_to) 
                          VALUES (:pid, :title, :status, :deadline, :assignee)");
        
        $this->db->bind(':pid', $data['project_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':deadline', $data['deadline']);
        $this->db->bind(':assignee', $data['assigned_to']);
        
        return $this->db->execute();
    }

    /**
     * Enterprise Kanban Fetcher
     * Grabs all tasks for a specific project so the UI can sort them into columns.
     */
    public function getTasksByProject($project_id) {
        $this->db->query("SELECT * FROM {$this->table} WHERE project_id = :pid ORDER BY created_at DESC");
        $this->db->bind(':pid', $project_id);
        return $this->db->fetchAll();
    }

    /* ========================================================
       EMPLOYEE WORKSPACE & AJAX MODULE (NEW CODE)
       ======================================================== */

    /**
     * GOD-LEVEL FIX: Auto-Healing Schema Engine
     * Generates advanced task tables (timers, comments, subtasks) if they don't exist.
     */
    private function ensureAdvancedTaskTables() {
        // Task Timers
        $this->db->query("CREATE TABLE IF NOT EXISTS `task_timers` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `start_time` DATETIME NOT NULL,
            `end_time` DATETIME NULL,
            `total_minutes` INT DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // Task Comments
        $this->db->query("CREATE TABLE IF NOT EXISTS `task_comments` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `comment` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
        
        // Subtasks
        $this->db->query("CREATE TABLE IF NOT EXISTS `subtasks` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `task_id` INT UNSIGNED NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `is_completed` TINYINT(1) DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * Fetch all tasks assigned to the employee
     */
    public function getMyTasks($user_id) {
        $this->ensureAdvancedTaskTables(); // Heal DB automatically
        
        try {
            $this->db->query("SELECT t.*, p.name as project_name, 
                             (SELECT COUNT(*) FROM subtasks WHERE task_id = t.id) as total_subtasks,
                             (SELECT COUNT(*) FROM subtasks WHERE task_id = t.id AND is_completed = 1) as completed_subtasks,
                             (SELECT id FROM task_timers WHERE task_id = t.id AND user_id = :uid AND end_time IS NULL LIMIT 1) as active_timer_id
                              FROM {$this->table} t
                              LEFT JOIN projects p ON t.project_id = p.id
                              WHERE t.assigned_to = :uid
                              ORDER BY t.priority DESC, t.deadline ASC");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }

    /**
     * AJAX: Update Task Status
     */
    public function updateStatus($task_id, $user_id, $status) {
        $this->db->query("UPDATE {$this->table} SET status = :status WHERE id = :tid AND assigned_to = :uid");
        $this->db->bind(':status', $status);
        $this->db->bind(':tid', $task_id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }

    /**
     * AJAX: Start / Stop Timer
     */
    public function toggleTimer($task_id, $user_id) {
        $this->ensureAdvancedTaskTables();
        
        // Check if an active timer exists for this task & user
        $this->db->query("SELECT id, start_time FROM task_timers WHERE task_id = :tid AND user_id = :uid AND end_time IS NULL LIMIT 1");
        $this->db->bind(':tid', $task_id);
        $this->db->bind(':uid', $user_id);
        $activeTimer = $this->db->fetch();

        if ($activeTimer) {
            // STOP TIMER logic
            $end_time = date('Y-m-d H:i:s');
            $start = strtotime($activeTimer['start_time']);
            $end = strtotime($end_time);
            $minutes = round(abs($end - $start) / 60, 2);

            $this->db->query("UPDATE task_timers SET end_time = :end_time, total_minutes = :mins WHERE id = :id");
            $this->db->bind(':end_time', $end_time);
            $this->db->bind(':mins', $minutes);
            $this->db->bind(':id', $activeTimer['id']);
            $this->db->execute();
            return ['status' => 'stopped', 'minutes' => $minutes];
        } else {
            // START TIMER logic
            $start_time = date('Y-m-d H:i:s');
            $this->db->query("INSERT INTO task_timers (task_id, user_id, start_time) VALUES (:tid, :uid, :start_time)");
            $this->db->bind(':tid', $task_id);
            $this->db->bind(':uid', $user_id);
            $this->db->bind(':start_time', $start_time);
            $this->db->execute();
            return ['status' => 'started'];
        }
    }
}