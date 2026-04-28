<?php

class Dashboard extends Model
{
    private array $dashboardStatusMeta = [
        'completed' => ['label' => 'Completed', 'color' => '#49c266'],
        'in_progress' => ['label' => 'In Progress', 'color' => '#4a86e8'],
        'on_hold' => ['label' => 'On Hold', 'color' => '#ffca28'],
        'at_risk' => ['label' => 'At Risk', 'color' => '#ff8a1f'],
        'not_started' => ['label' => 'Not Started', 'color' => '#ef4444'],
    ];

    private function safeFetch(string $sql, array $bindings = []): array
    {
        try {
            $this->db->query($sql);
            foreach ($bindings as $key => $value) {
                $this->db->bind($key, $value);
            }

            return $this->db->fetch() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeFetchAll(string $sql, array $bindings = []): array
    {
        try {
            $this->db->query($sql);
            foreach ($bindings as $key => $value) {
                $this->db->bind($key, $value);
            }

            return $this->db->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private function safeScalar(string $sql, array $bindings = [], string $column = '', $default = 0)
    {
        $row = $this->safeFetch($sql, $bindings);
        if (empty($row)) {
            return $default;
        }

        if ($column !== '' && array_key_exists($column, $row)) {
            return $row[$column];
        }

        $value = reset($row);
        return $value !== false ? $value : $default;
    }

    public function getAdminStats(): array
    {
        $statusBreakdown = $this->getProjectStatusBreakdown();

        $totalRevenue = (float) $this->safeScalar(
            "SELECT COALESCE(SUM(amount), 0) AS total FROM invoices WHERE status = 'paid'",
            [],
            'total',
            0
        );

        if ($totalRevenue <= 0) {
            $totalRevenue = (float) $this->safeScalar(
                "SELECT COALESCE(SUM(base_amount), 0) AS total FROM financial_transactions WHERE transaction_type = 'income'",
                [],
                'total',
                0
            );
        }

        $totalEmployees = (int) $this->safeScalar(
            "SELECT COUNT(*) AS count FROM users WHERE status = 'active'",
            [],
            'count',
            0
        );

        if ($totalEmployees <= 0) {
            $totalEmployees = (int) $this->safeScalar(
                "SELECT COUNT(*) AS count FROM employees",
                [],
                'count',
                0
            );
        }

        $pendingTasks = (int) $this->safeScalar(
            "SELECT COUNT(*) AS count FROM project_tasks WHERE status != 'done'",
            [],
            'count',
            0
        );

        return [
            'total_revenue' => $totalRevenue,
            'active_projects' => (int) (
                ($statusBreakdown['in_progress']['count'] ?? 0) +
                ($statusBreakdown['on_hold']['count'] ?? 0) +
                ($statusBreakdown['at_risk']['count'] ?? 0) +
                ($statusBreakdown['not_started']['count'] ?? 0)
            ),
            'total_projects' => (int) array_sum(array_column($statusBreakdown, 'count')),
            'total_employees' => $totalEmployees,
            'pending_tasks' => $pendingTasks,
            'completed_projects' => (int) ($statusBreakdown['completed']['count'] ?? 0),
            'in_progress_projects' => (int) ($statusBreakdown['in_progress']['count'] ?? 0),
            'on_hold_projects' => (int) ($statusBreakdown['on_hold']['count'] ?? 0),
            'at_risk_projects' => (int) ($statusBreakdown['at_risk']['count'] ?? 0),
            'not_started_projects' => (int) ($statusBreakdown['not_started']['count'] ?? 0),
        ];
    }

    public function getEmployeeStats($user_id): array
    {
        $stats = [];

        $stats['my_pending_tasks'] = (int) $this->safeScalar(
            "SELECT COUNT(*) AS count FROM project_tasks WHERE assigned_to = :uid AND status != 'done'",
            [':uid' => $user_id],
            'count',
            0
        );

        $stats['my_projects'] = (int) $this->safeScalar(
            "SELECT COUNT(DISTINCT p.id) AS count
             FROM projects p
             JOIN project_tasks pt ON p.id = pt.project_id
             WHERE pt.assigned_to = :uid",
            [':uid' => $user_id],
            'count',
            0
        );

        return $stats;
    }

    public function getRecentActivity(int $limit = 5): array
    {
        try {
            $this->db->query(
                "SELECT al.*, u.first_name, u.last_name, u.avatar
                 FROM activity_logs al
                 JOIN users u ON al.user_id = u.id
                 ORDER BY al.created_at DESC
                 LIMIT :limit"
            );
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            return $this->db->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getProjectStatusBreakdown(): array
    {
        $counts = $this->safeFetch(
            "SELECT
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                SUM(CASE WHEN status = 'in_progress' AND (deadline IS NULL OR deadline >= CURDATE()) THEN 1 ELSE 0 END) AS in_progress,
                SUM(CASE WHEN status = 'on_hold' THEN 1 ELSE 0 END) AS on_hold,
                SUM(CASE WHEN status = 'in_progress' AND deadline IS NOT NULL AND deadline < CURDATE() AND COALESCE(progress_pct, 0) < 100 THEN 1 ELSE 0 END) AS at_risk,
                SUM(CASE WHEN status = 'planning' THEN 1 ELSE 0 END) AS not_started
             FROM projects
             WHERE status != 'cancelled'"
        );

        $total = 0;
        foreach ($this->dashboardStatusMeta as $key => $meta) {
            $total += (int) ($counts[$key] ?? 0);
        }

        $breakdown = [];
        foreach ($this->dashboardStatusMeta as $key => $meta) {
            $count = (int) ($counts[$key] ?? 0);
            $breakdown[$key] = [
                'key' => $key,
                'label' => $meta['label'],
                'color' => $meta['color'],
                'count' => $count,
                'percentage' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
            ];
        }

        return $breakdown;
    }

    public function getRecentProjectUpdates(int $limit = 7): array
    {
        try {
            $this->db->query(
                "SELECT
                    p.id,
                    p.name,
                    p.status,
                    p.progress_pct,
                    p.updated_at,
                    p.deadline,
                    CASE
                        WHEN p.status = 'completed' THEN 'completed'
                        WHEN p.status = 'on_hold' THEN 'on_hold'
                        WHEN p.status = 'planning' THEN 'not_started'
                        WHEN p.status = 'in_progress' AND p.deadline IS NOT NULL AND p.deadline < CURDATE() AND COALESCE(p.progress_pct, 0) < 100 THEN 'at_risk'
                        ELSE 'in_progress'
                    END AS dashboard_status,
                    CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) AS manager_name
                 FROM projects p
                 LEFT JOIN users u ON p.project_manager_id = u.id
                 WHERE p.status != 'cancelled'
                 ORDER BY p.updated_at DESC, p.id DESC
                 LIMIT :limit"
            );
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            return $this->db->fetchAll() ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getChartData(): array
    {
        return [
            'status_breakdown' => array_values($this->getProjectStatusBreakdown()),
            'recent_projects' => $this->getRecentProjectUpdates(7),
            'stats' => $this->getAdminStats(),
        ];
    }

    public function getRevenueChartData(): array
    {
        return $this->safeFetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(amount) AS total_income
             FROM invoices
             WHERE status = 'paid'
             GROUP BY month
             ORDER BY month DESC
             LIMIT 6"
        );
    }

    public function getRecentAdminDocuments($limit = 8): array
    {
        $documentModel = new AdminDocument();
        return $documentModel->getRecentDocuments($limit, 'bd');
    }
}
