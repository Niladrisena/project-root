<?php
class AnalyticsModel {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function getHeaderStats() {
        $query = "SELECT 
                    (SELECT COUNT(*) FROM projects WHERE status='Active') as active,
                    (SELECT COUNT(*) FROM users) as team_size,
                    (SELECT COUNT(*) FROM projects WHERE status='Completed') as completed,
                    (SELECT SUM(amount) FROM revenue_transactions WHERE status='Paid') as revenue";
        $result = $this->db->query($query);
        
        // Return defaults if query fails or returns null
        $data = $result->fetch_assoc();
        return [
            'active' => $data['active'] ?? 0,
            'team_size' => $data['team_size'] ?? 0,
            'completed' => $data['completed'] ?? 0,
            'revenue' => $data['revenue'] ?? 0
        ];
    }

    public function getCriticalInsight() {
        $query = "SELECT project_name, time_elapsed_pct, budget_spent_pct 
                  FROM project_analytics 
                  WHERE budget_spent_pct > time_elapsed_pct 
                  LIMIT 1";
        $result = $this->db->query($query);
        
        return $result->fetch_assoc() ?: [
            'project_name' => 'General System',
            'time_elapsed_pct' => 0,
            'budget_spent_pct' => 0
        ];
    }
}