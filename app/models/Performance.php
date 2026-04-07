<?php
class Performance extends Model {
    
    /**
     * God-Level Auto-Healer: 
     * Guarantees the performance_reviews table exists before querying.
     */
    private function ensurePerformanceTables() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `performance_reviews` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `employee_id` INT UNSIGNED NOT NULL,
            `reviewer_id` INT UNSIGNED NOT NULL,
            `review_period` VARCHAR(50) NOT NULL,
            `rating` DECIMAL(3,1) NOT NULL DEFAULT 0.0,
            `comments` TEXT NULL,
            `status` ENUM('draft', 'submitted', 'acknowledged') DEFAULT 'draft',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * Fetches all performance reviews with employee and reviewer details
     */
    public function getAllReviews() {
        $this->ensurePerformanceTables();
        
        $this->db->query("
            SELECT p.*, 
                   emp.first_name as emp_first, emp.last_name as emp_last, emp.email as emp_email,
                   rev.first_name as rev_first, rev.last_name as rev_last
            FROM performance_reviews p
            JOIN users emp ON p.employee_id = emp.id
            JOIN users rev ON p.reviewer_id = rev.id
            ORDER BY p.created_at DESC
        ");
        
        return $this->db->fetchAll();
    }

    /**
     * Get company-wide average rating for the dashboard
     * (Upgraded with PHP 8+ Strict Type Safety)
     */
    public function getCompanyAverage() {
        $this->ensurePerformanceTables();
        $this->db->query("SELECT AVG(rating) as avg_rating FROM performance_reviews WHERE status != 'draft'");
        $result = $this->db->fetch();
        
        // ELITE FIX: Explicitly check for NULL before passing to the round() function
        if ($result && $result['avg_rating'] !== null) {
            return round((float) $result['avg_rating'], 1);
        }
        
        // Safe fallback if the table is empty
        return 0.0; 
    }
    /**
     * Fetches all eligible employees to be evaluated (Excludes Owner)
     */
    public function getEligibleEmployees() {
        $this->ensurePerformanceTables();
        $this->db->query("
            SELECT id, first_name, last_name, email 
            FROM users 
            WHERE status = 'active' AND role_id != 1 
            ORDER BY first_name ASC
        ");
        return $this->db->fetchAll();
    }

    /**
     * Securely inserts a new performance appraisal
     */
    public function createReview($data) {
        $this->ensurePerformanceTables();
        $this->db->query("
            INSERT INTO performance_reviews 
            (employee_id, reviewer_id, review_period, rating, comments, status) 
            VALUES (:employee_id, :reviewer_id, :review_period, :rating, :comments, :status)
        ");
        
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':reviewer_id', $data['reviewer_id']);
        $this->db->bind(':review_period', $data['review_period']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comments', $data['comments']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }
}