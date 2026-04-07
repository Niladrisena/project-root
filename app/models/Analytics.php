<?php
class Analytics extends Model {
    
    private function getCached($key) {
        $this->db->query("SELECT cache_data FROM analytics_cache WHERE cache_key = :key AND expires_at > NOW()");
        $this->db->bind(':key', $key);
        $result = $this->db->fetch();
        return $result ? json_decode($result['cache_data'], true) : false;
    }

    private function setCache($key, $data, $ttl_minutes = 15) {
        $json_data = json_encode($data);
        $this->db->query("INSERT INTO analytics_cache (cache_key, cache_data, expires_at) 
                          VALUES (:key, :data, DATE_ADD(NOW(), INTERVAL :ttl MINUTE))
                          ON DUPLICATE KEY UPDATE cache_data = :data, expires_at = DATE_ADD(NOW(), INTERVAL :ttl MINUTE)");
        $this->db->bind(':key', $key);
        $this->db->bind(':data', $json_data);
        $this->db->bind(':ttl', $ttl_minutes);
        $this->db->execute();
    }

    public function getCEODashboardStats() {
        $cache_key = 'ceo_master_stats';
        if ($cached = $this->getCached($cache_key)) return $cached;

        $stats = [];
        
        // Financials (Base Currency)
        $this->db->query("SELECT 
            SUM(CASE WHEN transaction_type = 'income' THEN base_amount ELSE 0 END) as revenue,
            SUM(CASE WHEN transaction_type = 'expense' THEN base_amount ELSE 0 END) as expense
            FROM financial_transactions WHERE YEAR(transaction_date) = YEAR(CURRENT_DATE())");
        $fin = $this->db->fetch();
        $stats['ytd_revenue'] = $fin['revenue'] ?? 0;
        $stats['ytd_expense'] = $fin['expense'] ?? 0;
        $stats['ytd_profit'] = $stats['ytd_revenue'] - $stats['ytd_expense'];

        // Project Health
        $this->db->query("SELECT 
            COUNT(*) as total_active,
            SUM(CASE WHEN deadline < CURRENT_DATE() AND status != 'completed' THEN 1 ELSE 0 END) as delayed_projects
            FROM projects WHERE status IN ('planning', 'in_progress')");
        $proj = $this->db->fetch();
        $stats['active_projects'] = $proj['total_active'];
        $stats['delayed_projects'] = $proj['delayed_projects'] ?? 0;

        // HR & Workforce
        $this->db->query("SELECT COUNT(*) as headcount FROM users WHERE status = 'active' AND deleted_at IS NULL");
        $stats['total_employees'] = $this->db->fetch()['headcount'];

        $this->setCache($cache_key, $stats, 30); // Cache for 30 mins
        return $stats;
    }

    public function getFinancialGrowthChart() {
        $this->db->query("
            SELECT DATE_FORMAT(transaction_date, '%b') as month,
            SUM(CASE WHEN transaction_type = 'income' THEN base_amount ELSE 0 END) as revenue,
            SUM(CASE WHEN transaction_type = 'expense' THEN base_amount ELSE 0 END) as expense
            FROM financial_transactions 
            WHERE transaction_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY MONTH(transaction_date)
            ORDER BY MIN(transaction_date) ASC
        ");
        return $this->db->fetchAll();
    }
}