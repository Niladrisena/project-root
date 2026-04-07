<?php
class Payroll extends Model {
    
    /**
     * God-Level Auto-Healer: 
     * Guarantees the payroll ledger table exists before querying it.
     */
    private function ensurePayrollTables() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `payroll_records` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `month` TINYINT NOT NULL,
            `year` YEAR NOT NULL,
            `base_salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `deductions` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `net_pay` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `status` ENUM('draft', 'processing', 'paid') DEFAULT 'draft',
            `payment_date` DATE NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * Fetches all active employees and their annual/monthly base salaries
     */
    public function getPayrollRoster() {
        $this->ensurePayrollTables();
        
        // Grab all active employees (excluding the System Owner, Role 1)
        $this->db->query("
            SELECT id, first_name, last_name, email, salary 
            FROM users 
            WHERE status = 'active' AND role_id != 1 
            ORDER BY first_name ASC
        ");
        $employees = $this->db->fetchAll();

        // Calculate dynamic monthly metrics for the UI
        $roster = [];
        foreach ($employees as $emp) {
            $annual = (float)$emp['salary'];
            $monthly = $annual / 12;
            
            // Simulating a standard 10% tax/benefit deduction for the Enterprise view
            $deductions = $monthly * 0.10; 
            
            $roster[] = [
                'id' => $emp['id'],
                'name' => $emp['first_name'] . ' ' . $emp['last_name'],
                'email' => $emp['email'],
                'annual_salary' => $annual,
                'monthly_base' => $monthly,
                'deductions' => $deductions,
                'net_pay' => $monthly - $deductions,
                'status' => 'draft' // Default status before payroll is run
            ];
        }
        return $roster;
    }
    
    /**
     * Calculates the total company payroll liability for the current month
     */
    public function getPayrollMetrics($roster) {
        $total_liability = 0;
        $total_deductions = 0;
        
        foreach ($roster as $emp) {
            $total_liability += $emp['monthly_base'];
            $total_deductions += $emp['deductions'];
        }
        
        return [
            'total_liability' => $total_liability,
            'total_deductions' => $total_deductions,
            'net_payout' => $total_liability - $total_deductions,
            'headcount' => count($roster)
        ];
    }
}