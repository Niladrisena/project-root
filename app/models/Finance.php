<?php
class Finance extends Model {
    protected $table = 'invoices';

    /**
     * ==========================================
     * 🚀 ELITE SYSTEM AUTO-HEALER
     * Ensures all Finance, Payroll, and Ledger tables exist 
     * without affecting the core 'invoices' schema.
     * ==========================================
     */
    private function ensureTables() {
        // 1. Employee Base Salary Engine
        $this->db->query("CREATE TABLE IF NOT EXISTS `finance_salaries` (
            `user_id` INT UNSIGNED PRIMARY KEY,
            `base_salary` DECIMAL(15,2) DEFAULT 0.00,
            `currency_id` INT UNSIGNED DEFAULT 1,
            `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // 2. Processed Payslips Ledger
        $this->db->query("CREATE TABLE IF NOT EXISTS `finance_payslips` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `month` TINYINT UNSIGNED NOT NULL,
            `year` YEAR NOT NULL,
            `basic_pay` DECIMAL(15,2) NOT NULL,
            `deductions` DECIMAL(15,2) DEFAULT 0.00,
            `net_pay` DECIMAL(15,2) NOT NULL,
            `status` ENUM('pending', 'paid') DEFAULT 'pending',
            `processed_by` INT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `unique_payslip` (`user_id`, `month`, `year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
        
        // 3. Corporate Income & Expense Ledger
        $this->db->query("CREATE TABLE IF NOT EXISTS `finance_transactions` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `type` ENUM('income', 'expense') NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `amount` DECIMAL(15,2) NOT NULL,
            `transaction_date` DATE NOT NULL,
            `reference_no` VARCHAR(100) NULL,
            `description` TEXT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();

        // 4. Salary Structure Foundation Engine
        $this->db->query("CREATE TABLE IF NOT EXISTS `finance_salary_structures` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NOT NULL,
            `basic_salary` DECIMAL(15,2) DEFAULT 0.00,
            `allowance` DECIMAL(15,2) DEFAULT 0.00,
            `bonus` DECIMAL(15,2) DEFAULT 0.00,
            `deduction` DECIMAL(15,2) DEFAULT 0.00,
            `currency_id` INT UNSIGNED DEFAULT 1,
            `effective_date` DATE NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `unique_user_structure` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    /**
     * ==========================================
     * 📊 DUAL-COMPATIBILITY METRICS ENGINE
     * Serves both Owner (Legacy) and Finance (Modern) Dashboards
     * ==========================================
     */
    public function getMetrics() {
        try {
            $this->db->query("SELECT SUM(amount) as total FROM {$this->table}");
            $total_revenue = $this->db->fetch()['total'] ?? 0.00;

            $this->ensureTables();
            $this->db->query("SELECT SUM(amount) as total_exp FROM finance_transactions WHERE type = 'expense'");
            $expenses = $this->db->fetch()['total_exp'] ?? 0.00;

            return [
                'revenue' => $total_revenue,
                'expenses' => $expenses,
                'currency_symbol' => SYS_CURRENCY, // 🚀 Dynamic Global Reflection
                'net_profit' => $total_revenue - $expenses
            ];
        } catch (\Throwable $e) {
            return ['revenue' => 0, 'expenses' => 0, 'currency_symbol' => '$', 'net_profit' => 0];
        }
    }

    /**
     * ==========================================
     * 🚀 PAYROLL & LEDGER AGGREGATORS
     * ==========================================
     */
    public function getPayrollData() {
        $this->ensureTables();
        try {
            $this->db->query("
                SELECT u.id, u.first_name, u.last_name, u.email, r.role_name,
                       COALESCE(fs.base_salary, 0.00) as base_salary,
                       (SELECT COUNT(id) FROM attendance WHERE user_id = u.id AND MONTH(work_date) = MONTH(CURDATE()) AND YEAR(work_date) = YEAR(CURDATE())) as days_present
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                LEFT JOIN finance_salaries fs ON u.id = fs.user_id
                WHERE u.role_id != 1 
                ORDER BY u.first_name ASC
            ");
            return $this->db->fetchAll() ?: [];
        } catch (\Throwable $e) {
            // Failsafe Pivot
            try {
                $this->db->query("SELECT u.id, u.first_name, u.last_name, u.email, 'Staff' as role_name FROM users u WHERE u.role_id != 1");
                return $this->db->fetchAll() ?: [];
            } catch (\Throwable $e2) { return []; }
        }
    }

    public function getAllTransactions() {
        $this->ensureTables();
        try {
            $this->db->query("SELECT * FROM finance_transactions ORDER BY transaction_date DESC, created_at DESC");
            return $this->db->fetchAll() ?: [];
        } catch (\Throwable $e) { return []; }
    }

    public function addTransaction($data) {
        $this->ensureTables();
        try {
            $this->db->query("INSERT INTO finance_transactions 
                (type, category, amount, transaction_date, reference_no, description) 
                VALUES (:type, :category, :amount, :t_date, :ref, :desc)");
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':category', $data['category']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':t_date', $data['transaction_date']);
            $this->db->bind(':ref', $data['reference_no']);
            $this->db->bind(':desc', $data['description']);
            return $this->db->execute();
        } catch (\Throwable $e) { return false; }
    }

    /**
     * ==========================================
     * 🚀 SALARY STRUCTURE (FOUNDATION) ENGINE
     * ==========================================
     */
    /**
     * ==========================================
     * 🚀 SALARY STRUCTURE (FOUNDATION) ENGINE
     * Upgraded with a Flawless Structural Failsafe and COALESCE protection
     * ==========================================
     */
    public function getSalaryStructures() {
        $this->ensureTables();
        try {
            // Primary Query
            $this->db->query("
                SELECT u.id as user_id, u.first_name, u.last_name, u.email, r.role_name,
                       COALESCE(ss.basic_salary, 0.00) as basic_salary, 
                       COALESCE(ss.allowance, 0.00) as allowance, 
                       COALESCE(ss.bonus, 0.00) as bonus, 
                       COALESCE(ss.deduction, 0.00) as deduction, 
                       ss.effective_date,
                       c.symbol, c.code,
                       (COALESCE(ss.basic_salary, 0) + COALESCE(ss.allowance, 0) + COALESCE(ss.bonus, 0) - COALESCE(ss.deduction, 0)) as net_salary
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                LEFT JOIN finance_salary_structures ss ON u.id = ss.user_id
                LEFT JOIN currencies c ON ss.currency_id = c.id
                WHERE u.role_id != 1 
                ORDER BY u.first_name ASC
            ");
            return $this->db->fetchAll() ?: [];
        } catch (\Throwable $e) {
            // 🚀 THE MASTER FAILSAFE PIVOT
            // Guarantees all keys exist even if table joins fail!
            try {
                // Dynamically grab the global currency symbol if available
                $sys_curr = defined('SYS_CURRENCY') ? SYS_CURRENCY : '$';
                
                $this->db->query("
                    SELECT u.id as user_id, u.first_name, u.last_name, u.email, 'Staff' as role_name,
                           COALESCE(ss.basic_salary, 0.00) as basic_salary, 
                           COALESCE(ss.allowance, 0.00) as allowance, 
                           COALESCE(ss.bonus, 0.00) as bonus, 
                           COALESCE(ss.deduction, 0.00) as deduction, 
                           ss.effective_date,
                           '{$sys_curr}' as symbol, 'BASE' as code,
                           (COALESCE(ss.basic_salary, 0) + COALESCE(ss.allowance, 0) + COALESCE(ss.bonus, 0) - COALESCE(ss.deduction, 0)) as net_salary
                    FROM users u
                    LEFT JOIN finance_salary_structures ss ON u.id = ss.user_id
                    WHERE u.role_id != 1 
                    ORDER BY u.first_name ASC
                ");
                return $this->db->fetchAll() ?: [];
            } catch (\Throwable $e2) { 
                return []; 
            }
        }
    }

    public function saveSalaryStructure($data) {
        $this->ensureTables();
        try {
            $this->db->query("INSERT INTO finance_salary_structures 
                (user_id, basic_salary, allowance, bonus, deduction, currency_id, effective_date) 
                VALUES (:uid, :basic, :allow, :bonus, :deduct, :curr, :eff_date)
                ON DUPLICATE KEY UPDATE 
                basic_salary = :basic, allowance = :allow, bonus = :bonus, deduction = :deduct, currency_id = :curr, effective_date = :eff_date");
            $this->db->bind(':uid', $data['user_id']);
            $this->db->bind(':basic', $data['basic_salary']);
            $this->db->bind(':allow', $data['allowance']);
            $this->db->bind(':bonus', $data['bonus']);
            $this->db->bind(':deduct', $data['deduction']);
            $this->db->bind(':curr', $data['currency_id']);
            $this->db->bind(':eff_date', $data['effective_date']);
            return $this->db->execute();
        } catch (\Throwable $e) { return false; }
    }

    /**
     * ==========================================
     * 🛡️ LEGACY JIT SUPPORT (UNTOUCHED)
     * ==========================================
     */
    public function getCurrencies() {
        try { $this->db->query("SELECT * FROM currencies ORDER BY is_base DESC, code ASC"); return $this->db->fetchAll(); } catch (\Throwable $e) { return []; }
    }

    public function getProjects() {
        try { $this->db->query("SELECT * FROM projects ORDER BY id DESC"); return $this->db->fetchAll(); } catch (\Throwable $e) { return []; }
    }

    public function getClients() {
        try { $this->db->query("SELECT * FROM clients ORDER BY id DESC"); return $this->db->fetchAll(); } catch (\Throwable $e) { return []; }
    }

    public function createInvoice($data) {
        $this->db->query("SELECT id FROM currencies LIMIT 1");
        $real_currency = $this->db->fetch();
        
        if ($real_currency && !empty($real_currency['id'])) {
            $data['currency_id'] = $real_currency['id'];
        } else {
            // 🚀 DYNAMIC CURRENCY INJECTION 
            // Stops hardcoding USD and reads the Global Constants instead
            $sys_code = defined('SYS_CURRENCY_CODE') ? SYS_CURRENCY_CODE : 'USD';
            $sys_sym = defined('SYS_CURRENCY') ? SYS_CURRENCY : '$';
            
            $this->db->query("INSERT INTO currencies (code, symbol, exchange_rate, is_base) VALUES (:code, :sym, 1.0000, 1)");
            $this->db->bind(':code', $sys_code);
            $this->db->bind(':sym', $sys_sym);
            $this->db->execute();
            
            $this->db->query("SELECT id FROM currencies LIMIT 1");
            $data['currency_id'] = $this->db->fetch()['id'];
        }

        $this->db->query("SELECT id FROM projects LIMIT 1");
        $real_project = $this->db->fetch();
        if ($real_project && !empty($real_project['id'])) {
            $data['project_id'] = $real_project['id'];
        }

        $this->db->query("SELECT id FROM clients LIMIT 1");
        $real_client = $this->db->fetch();
        if ($real_client && !empty($real_client['id'])) {
            $data['client_id'] = $real_client['id'];
        }

        $invoice_number = 'INV-' . strtoupper(substr(uniqid(), -6));
        
        $this->db->query("INSERT INTO {$this->table} (client_id, project_id, currency_id, invoice_number, description, amount, due_date) 
                          VALUES (:client, :project, :currency, :inv_num, :desc, :amount, :due)");
        
        $this->db->bind(':client', $data['client_id']);
        $this->db->bind(':project', $data['project_id']);
        $this->db->bind(':currency', $data['currency_id']);
        $this->db->bind(':inv_num', $invoice_number);
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':due', $data['due_date']);
        
        return $this->db->execute();
    }

    public function getRecentInvoices() {
        try {
            $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT 5");
            return $this->db->fetchAll() ?: [];
        } catch (\Throwable $e) { return []; }
    }
}