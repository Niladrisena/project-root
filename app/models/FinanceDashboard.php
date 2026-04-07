<?php
class FinanceDashboard extends Model {
    
    public function getKPIs() {
        // Assuming base currency rate is 1.0000
        $this->db->query("SELECT 
            SUM(CASE WHEN transaction_type = 'income' THEN base_amount ELSE 0 END) as total_revenue,
            SUM(CASE WHEN transaction_type = 'expense' THEN base_amount ELSE 0 END) as total_expense
            FROM financial_transactions WHERE MONTH(transaction_date) = MONTH(CURRENT_DATE())");
        $current_month = $this->db->fetch();

        $profit = ($current_month['total_revenue'] ?? 0) - ($current_month['total_expense'] ?? 0);

        return [
            'revenue' => $current_month['total_revenue'] ?? 0,
            'expense' => $current_month['total_expense'] ?? 0,
            'profit' => $profit
        ];
    }

    public function getCashFlowData() {
        $this->db->query("
            SELECT DATE_FORMAT(transaction_date, '%b') as month,
            SUM(CASE WHEN transaction_type = 'income' THEN base_amount ELSE 0 END) as income,
            SUM(CASE WHEN transaction_type = 'expense' THEN base_amount ELSE 0 END) as expense
            FROM financial_transactions 
            GROUP BY MONTH(transaction_date) 
            ORDER BY transaction_date ASC LIMIT 6
        ");
        return $this->db->fetchAll();
    }
    
}