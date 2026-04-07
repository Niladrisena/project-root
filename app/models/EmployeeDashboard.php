<?php
class EmployeeDashboard extends Model {
    
    // 1. Assigned Projects
    public function getAssignedProjects($user_id) {
        try {
            $this->db->query("SELECT p.*, (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status = 'completed') / NULLIF((SELECT COUNT(*) FROM tasks WHERE project_id = p.id), 0) * 100 as progress 
                              FROM projects p 
                              JOIN project_members pm ON p.id = pm.project_id 
                              WHERE pm.user_id = :uid LIMIT 4");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }

    // 2. Active Tasks
    public function getActiveTasks($user_id) {
        try {
            $this->db->query("SELECT t.*, p.name as project_name 
                              FROM tasks t 
                              LEFT JOIN projects p ON t.project_id = p.id 
                              WHERE t.assigned_to = :uid AND t.status != 'completed' 
                              ORDER BY t.priority DESC, t.deadline ASC LIMIT 5");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }

    // 3. Today's Attendance
    public function getTodayAttendance($user_id) {
        try {
            $this->db->query("SELECT * FROM attendance WHERE user_id = :uid AND date = CURRENT_DATE LIMIT 1");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetch();
        } catch (Exception $e) { return null; }
    }

    // 4. Weekly Timesheet Summary
    public function getWeeklyTimesheet($user_id) {
        try {
            $this->db->query("SELECT SUM(hours_worked) as total_hours FROM timesheets 
                              WHERE user_id = :uid AND YEARWEEK(date, 1) = YEARWEEK(CURRENT_DATE, 1)");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetch()['total_hours'] ?? 0;
        } catch (Exception $e) { return 0; }
    }

    // 5. Upcoming Deadlines (Next 7 Days)
    public function getUpcomingDeadlines($user_id) {
        try {
            $this->db->query("SELECT title, deadline, 'Task' as type FROM tasks 
                              WHERE assigned_to = :uid AND deadline BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY) AND status != 'completed'
                              ORDER BY deadline ASC LIMIT 4");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }

    // 6. Leave Balance
    public function getLeaveBalance($user_id) {
        try {
            $this->db->query("SELECT total_leaves, used_leaves FROM leave_balances WHERE user_id = :uid LIMIT 1");
            $this->db->bind(':uid', $user_id);
            $balance = $this->db->fetch();
            return $balance ? ($balance['total_leaves'] - $balance['used_leaves']) : 12; // Fallback to 12 days
        } catch (Exception $e) { return 12; }
    }

    // 7. Assigned Assets
    public function getAssignedAssets($user_id) {
        try {
            $this->db->query("SELECT name, asset_tag, status FROM assets WHERE assigned_to = :uid LIMIT 3");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }

    // 8. Notifications Summary
    public function getNotifications($user_id) {
        try {
            $this->db->query("SELECT message, created_at, is_read FROM notifications WHERE user_id = :uid ORDER BY created_at DESC LIMIT 4");
            $this->db->bind(':uid', $user_id);
            return $this->db->fetchAll();
        } catch (Exception $e) { return []; }
    }
}