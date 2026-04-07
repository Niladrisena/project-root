<?php
class Search extends Model {
    
    /**
     * ==========================================
     * 🚀 OMNI-SEARCH ENGINE + COMMAND PALETTE
     * Bulletproof Keyword Tagging & Failsafe Parsing
     * ==========================================
     */
    public function globalSearch($raw_query, $role_id, $user_id = 0) {
        $results = [
            'modules'      => [],
            'employees'    => [],
            'projects'     => [],
            'invoices'     => [],
            'transactions' => [],
            'tasks'        => [],
            'timesheets'   => [],
            'leaves'       => [],
            'assets'       => []
        ];

        // 🚀 Failsafe 1: Strip whitespace and normalize to lowercase
        $query = trim(strtolower((string)$raw_query));
        if (empty($query)) return $results;

        // Ensure role is cast to an integer
        $role_id = (int) $role_id;

        // ---------------------------------------------------------
        // 🚀 1. SEARCH SYSTEM MODULES (Command Palette with Keywords)
        // ---------------------------------------------------------
        $system_modules = [
            ['name' => 'My Workspace', 'url' => '/dashboard/employee', 'roles' => [2], 'desc' => 'Back to your employee dashboard', 'tags' => 'workspace dashboard home overview employee panel'],
            ['name' => 'My Task Center', 'url' => '/task/board', 'roles' => [2], 'desc' => 'Track assigned tasks and priorities', 'tags' => 'tasks task board kanban todo priority assigned work'],
            ['name' => 'My Timesheet', 'url' => '/timesheet', 'roles' => [2], 'desc' => 'Log time and review weekly hours', 'tags' => 'timesheet time log hours weekly work entries'],
            ['name' => 'Leave Management', 'url' => '/leave', 'roles' => [2], 'desc' => 'Request and review time off', 'tags' => 'leave vacation holiday sick time off request balance'],
            ['name' => 'My Assets', 'url' => '/asset/my', 'roles' => [2], 'desc' => 'View company assets assigned to you', 'tags' => 'assets laptop device equipment hardware assigned'],
            ['name' => 'Payroll Engine', 'url' => '/finance/payroll', 'roles' => [1, 4], 'desc' => 'Manage salaries & payslips', 'tags' => 'payroll salary payslip wage money'],
            ['name' => 'Transactions Ledger', 'url' => '/finance/transactions', 'roles' => [1, 4], 'desc' => 'Income & Expense Ledger', 'tags' => 'transaction ledger income expense finance logs'],
            ['name' => 'Financial Reports', 'url' => '/finance/report', 'roles' => [1, 4], 'desc' => 'Export P&L & CSVs', 'tags' => 'report finance pl csv export statement'],
            ['name' => 'Salary Structures', 'url' => '/finance/salary_structures', 'roles' => [1, 4], 'desc' => 'Base, Bonus & Allowances', 'tags' => 'salary structure base bonus allowance deduction assign'],
            ['name' => 'My Security & Password', 'url' => '/finance/security', 'roles' => [1, 2, 3, 4], 'desc' => 'Change Account Credentials', 'tags' => 'security password account settings lock'],
            ['name' => 'Attendance & Timesheets', 'url' => '/hr/attendance', 'roles' => [1, 3, 4], 'desc' => 'Clock In and Monitor Timesheets', 'tags' => 'attendance timesheet clock in out time shift'],
            ['name' => 'Leave Management', 'url' => '/hr/leaves', 'roles' => [1, 3, 4], 'desc' => 'Request Time Off', 'tags' => 'leave vacation time off sick holiday'],
            ['name' => 'System Settings', 'url' => '/settings', 'roles' => [1], 'desc' => 'Localization & Currency', 'tags' => 'settings system configuration currency timezone language']
        ];

        foreach ($system_modules as $mod) {
            // 🚀 Failsafe 2: Owner (Role 1) always gets bypass access.
            if ($role_id === 1 || in_array($role_id, $mod['roles'])) {
                
                // Concatenate everything into one giant searchable string
                $searchable_text = strtolower($mod['name'] . ' ' . $mod['desc'] . ' ' . $mod['tags']);
                
                // If the search query exists ANYWHERE inside that string, it's a match!
                if (strpos($searchable_text, $query) !== false) {
                    $results['modules'][] = $mod;
                }
            }
        }

        // Prepare wildcard for SQL LIKE queries
        $q = "%{$query}%";

        // ---------------------------------------------------------
        // 2. SEARCH EMPLOYEES
        // ---------------------------------------------------------
        if ($role_id !== 2) {
            try {
                $this->db->query("SELECT id, first_name, last_name, email FROM users 
                                  WHERE (first_name LIKE :q OR last_name LIKE :q OR email LIKE :q) 
                                  AND role_id != 1 LIMIT 5");
                $this->db->bind(':q', $q);
                $results['employees'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}
        }

        // ---------------------------------------------------------
        // 3. SEARCH PROJECTS
        // ---------------------------------------------------------
        try {
            $this->db->query("SELECT id, project_name as name, status FROM projects 
                              WHERE project_name LIKE :q LIMIT 5");
            $this->db->bind(':q', $q);
            $results['projects'] = $this->db->fetchAll() ?: [];
        } catch (\Throwable $e) {}

        // ---------------------------------------------------------
        // 4. SEARCH INVOICES (RESTRICTED)
        // ---------------------------------------------------------
        if ($role_id === 1 || $role_id === 4) {
            try {
                $this->db->query("SELECT invoice_number, amount, status, issue_date 
                                  FROM invoices 
                                  WHERE invoice_number LIKE :q OR description LIKE :q LIMIT 5");
                $this->db->bind(':q', $q);
                $results['invoices'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}
        }

        // ---------------------------------------------------------
        // 5. SEARCH TRANSACTIONS (RESTRICTED)
        // ---------------------------------------------------------
        if ($role_id === 1 || $role_id === 4) {
            try {
                $this->db->query("SELECT type, category, amount, transaction_date, reference_no 
                                  FROM finance_transactions 
                                  WHERE reference_no LIKE :q OR category LIKE :q OR description LIKE :q LIMIT 5");
                $this->db->bind(':q', $q);
                $results['transactions'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}
        }

        if ($user_id > 0) {
            try {
                $this->db->query("SELECT id, title, status, deadline
                                  FROM tasks
                                  WHERE assigned_to = :uid
                                  AND (title LIKE :q OR status LIKE :q)
                                  ORDER BY deadline ASC
                                  LIMIT 5");
                $this->db->bind(':uid', $user_id);
                $this->db->bind(':q', $q);
                $results['tasks'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}

            try {
                $this->db->query("SELECT timesheets.id, timesheets.date, timesheets.hours_worked, timesheets.status, projects.name AS project_name
                                  FROM timesheets
                                  LEFT JOIN projects ON timesheets.project_id = projects.id
                                  WHERE timesheets.user_id = :uid
                                  AND (timesheets.description LIKE :q OR projects.name LIKE :q OR timesheets.status LIKE :q)
                                  ORDER BY timesheets.date DESC
                                  LIMIT 5");
                $this->db->bind(':uid', $user_id);
                $this->db->bind(':q', $q);
                $results['timesheets'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}

            try {
                $this->db->query("SELECT id, leave_type, start_date, end_date, status
                                  FROM leaves
                                  WHERE user_id = :uid
                                  AND (leave_type LIKE :q OR status LIKE :q OR reason LIKE :q)
                                  ORDER BY created_at DESC
                                  LIMIT 5");
                $this->db->bind(':uid', $user_id);
                $this->db->bind(':q', $q);
                $results['leaves'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}

            try {
                $this->db->query("SELECT id, asset_tag, category, brand_model, status
                                  FROM assets
                                  WHERE assigned_to = :uid
                                  AND (asset_tag LIKE :q OR category LIKE :q OR brand_model LIKE :q OR status LIKE :q)
                                  ORDER BY created_at DESC
                                  LIMIT 5");
                $this->db->bind(':uid', $user_id);
                $this->db->bind(':q', $q);
                $results['assets'] = $this->db->fetchAll() ?: [];
            } catch (\Throwable $e) {}
        }

        return $results;
    }
}
