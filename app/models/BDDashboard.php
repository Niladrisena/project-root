<?php
class BDDashboard extends Model {

    /**
     * Reusable safe counter
     */
    private function countRows($query) {
        try {
            $this->db->query($query);
            $results = $this->db->fetchAll();
            return is_array($results) ? count($results) : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    // ==========================================
    // 📊 KPI METRICS
    // ==========================================

    public function getTotalLeads() {
        return $this->countRows("SELECT id FROM leads");
    }

    public function getActiveDeals() {
        return $this->countRows("SELECT id FROM leads WHERE status = 'Active'");
    }

    public function getClosedDeals() {
        return $this->countRows("SELECT id FROM leads WHERE status = 'Closed'");
    }

    public function getConversionRate() {
        $total = $this->getTotalLeads();
        $closed = $this->getClosedDeals();

        return $total > 0 ? round(($closed / $total) * 100) : 0;
    }

    // ==========================================
    // 📋 RECENT LEADS
    // ==========================================

    public function getRecentLeads() {
        try {
            $this->db->query("
                SELECT * FROM leads
                ORDER BY created_at DESC
                LIMIT 10
            ");

            $results = $this->db->fetchAll();
            return is_array($results) ? $results : [];

        } catch (\Throwable $e) {
            return [];
        }
    }
}