<?php
class Dashboard extends Model {
    
    public function getAdminStats() {
        $stats = [];
        
        // Total Employees
        $this->db->query("SELECT COUNT(*) as count FROM employees");
        $stats['total_employees'] = $this->db->fetch()['count'];
        
        // Active Projects
        $this->db->query("SELECT COUNT(*) as count FROM projects WHERE status IN ('planning', 'in_progress')");
        $stats['active_projects'] = $this->db->fetch()['count'];
        
        // Total Revenue (assuming paid invoices)
        $this->db->query("SELECT SUM(amount) as total FROM income");
        $stats['total_revenue'] = $this->db->fetch()['total'] ?? 0;

        // Pending Tasks
        $this->db->query("SELECT COUNT(*) as count FROM project_tasks WHERE status != 'done'");
        $stats['pending_tasks'] = $this->db->fetch()['count'];

        return $stats;
    }

    public function getEmployeeStats($user_id) {
        $stats = [];
        
        $this->db->query("SELECT COUNT(*) as count FROM project_tasks WHERE assigned_to = :uid AND status != 'done'");
        $this->db->bind(':uid', $user_id);
        $stats['my_pending_tasks'] = $this->db->fetch()['count'];

        $this->db->query("SELECT COUNT(DISTINCT p.id) as count FROM projects p JOIN project_tasks pt ON p.id = pt.project_id WHERE pt.assigned_to = :uid");
        $this->db->bind(':uid', $user_id);
        $stats['my_projects'] = $this->db->fetch()['count'];

        return $stats;
    }

    public function getRecentActivity($limit = 5) {
        $this->db->query("SELECT al.*, u.first_name, u.last_name, u.avatar 
                          FROM activity_logs al 
                          JOIN users u ON al.user_id = u.id 
                          ORDER BY al.created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->fetchAll();
    }
    
    public function getRevenueChartData() {
        // Example: Last 6 months revenue vs expenses
        $this->db->query("
            SELECT DATE_FORMAT(received_date, '%Y-%m') as month, SUM(amount) as total_income 
            FROM income GROUP BY month ORDER BY month DESC LIMIT 6
        ");
        return array_reverse($this->db->fetchAll());
    }

    public function getRecentAdminDocuments($limit = 8) {
        $documentModel = new AdminDocument();
        return $documentModel->getRecentDocuments($limit, 'bd');
    }
}
