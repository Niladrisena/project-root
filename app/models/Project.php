<?php
class Project extends Model {
    protected $table = 'projects';

    private function ensureResourcePlanningTables() {
        try {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS project_resource_plans (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    project_id INT NOT NULL,
                    project_manager_label VARCHAR(255) NULL,
                    start_date DATE NULL,
                    end_date DATE NULL,
                    priority VARCHAR(50) NOT NULL DEFAULT 'medium',
                    plan_status VARCHAR(50) NOT NULL DEFAULT 'draft',
                    updated_by INT NULL,
                    submitted_at DATETIME NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_project_plan (project_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            $this->db->execute();
        } catch (Exception $e) {
            // Table may already exist
        }

        try {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS project_resource_plan_rows (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    plan_id INT NOT NULL,
                    department VARCHAR(255) NOT NULL,
                    required_employees INT NOT NULL DEFAULT 0,
                    assigned_employees INT NOT NULL DEFAULT 0,
                    experience_preferred VARCHAR(100) NULL,
                    notes TEXT NULL,
                    display_order INT NOT NULL DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    KEY idx_plan_id (plan_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            $this->db->execute();
        } catch (Exception $e) {
            // Table may already exist
        }
    }

    /**
     * ==========================================
     * ENTERPRISE RESOURCE UPGRADE (AUTO-HEALER)
     * Safely injects hour tracking columns if they don't exist
     * ==========================================
     */
    private function ensureResourceColumns() {
        try { 
            $this->db->query("ALTER TABLE projects ADD COLUMN total_hours DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER estimated_budget"); 
            $this->db->execute(); 
        } catch (Exception $e) {
            // Silently catch if the column already exists
        }
        
        try { 
            $this->db->query("ALTER TABLE project_members ADD COLUMN allocated_hours DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER role_in_project"); 
            $this->db->execute(); 
        } catch (Exception $e) {
            // Silently catch if the column already exists
        }
    }

    /**
     * ==========================================
     * CORE PROJECT METHODS
     * ==========================================
     */
    public function getAllForUser($user_id, $role_slug) {
        // Owners and Admins see all projects. Others see only assigned projects.
        if ($role_slug === 'owner' || $role_slug === 'admin') {
            $this->db->query("SELECT p.*, c.company_name, u.first_name, u.last_name 
                              FROM {$this->table} p
                              LEFT JOIN clients c ON p.client_id = c.id
                              LEFT JOIN users u ON p.project_manager_id = u.id
                              ORDER BY p.created_at DESC");
        } else {
            $this->db->query("SELECT p.*, c.company_name, u.first_name, u.last_name 
                              FROM {$this->table} p
                              JOIN project_members pm ON p.id = pm.project_id
                              LEFT JOIN clients c ON p.client_id = c.id
                              LEFT JOIN users u ON p.project_manager_id = u.id
                              WHERE pm.user_id = :uid
                              ORDER BY p.created_at DESC");
            $this->db->bind(':uid', $user_id);
        }
        return $this->db->fetchAll();
    }

    /** * Fetches all active personnel to be assigned to the project
     * ELITE FIX: Guaranteed 'email' and 'role_id' columns
     */
    public function getAvailableResources() {
        $this->db->query("
            SELECT id, first_name, last_name, email, role_id 
            FROM users 
            WHERE status = 'active' AND role_id != 1 
            ORDER BY first_name ASC
        ");
        return $this->db->fetchAll();
    }

    public function createProject($data) {
        $this->ensureResourceColumns(); // Trigger the auto-healer

        $this->db->query("INSERT INTO {$this->table} 
            (project_code, client_id, currency_id, project_manager_id, name, description, status, priority, estimated_budget, total_hours, start_date, deadline) 
            VALUES (:code, :client, :curr, :pm, :name, :desc, :status, :priority, :budget, :total_hours, :start, :deadline)");
        
        $this->db->bind(':code', 'PRJ-' . strtoupper(uniqid()));
        $this->db->bind(':client', $data['client_id']);
        $this->db->bind(':curr', $data['currency_id']);
        $this->db->bind(':pm', $data['project_manager_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':budget', $data['estimated_budget']);
        $this->db->bind(':total_hours', $data['total_hours']); 
        $this->db->bind(':start', $data['start_date']);
        $this->db->bind(':deadline', $data['deadline']);
        
        if ($this->db->execute()) {
            $project_id = $this->db->lastInsertId();
            // Auto-assign PM to project_members with zero hours
            if ($data['project_manager_id']) {
                $this->assignMember($project_id, $data['project_manager_id'], 'manager', 0);
            }
            return $project_id;
        }
        return false;
    }

    // Upgraded to accept allocated hours per employee
    public function assignMember($project_id, $user_id, $role = 'member', $allocated_hours = 0) {
        $this->db->query("INSERT IGNORE INTO project_members (project_id, user_id, role_in_project, allocated_hours) VALUES (:pid, :uid, :role, :hours)");
        $this->db->bind(':pid', $project_id);
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':role', $role);
        $this->db->bind(':hours', $allocated_hours);
        return $this->db->execute();
    }

    public function saveFileRecord($project_id, $saved_name, $original_name, $uploaded_by) {
        $this->db->query("INSERT INTO project_files (project_id, uploaded_by, original_name, saved_name) 
                          VALUES (:pid, :uid, :orig, :saved)");
        $this->db->bind(':pid', $project_id);
        $this->db->bind(':uid', $uploaded_by);
        $this->db->bind(':orig', $original_name);
        $this->db->bind(':saved', $saved_name);
        return $this->db->execute();
    }

    public function calculateProgress($project_id) {
        $this->db->query("SELECT 
            COUNT(id) as total_tasks, 
            SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as completed_tasks 
            FROM project_tasks WHERE project_id = :pid");
        $this->db->bind(':pid', $project_id);
        $stats = $this->db->fetch();
        
        $pct = ($stats['total_tasks'] > 0) ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0;
        
        $this->db->query("UPDATE {$this->table} SET progress_pct = :pct WHERE id = :pid");
        $this->db->bind(':pct', $pct);
        $this->db->bind(':pid', $project_id);
        $this->db->execute();
        
        return $pct;
    }

    /**
     * Fetch all files attached to a specific project
     */
    public function getProjectFiles($project_id) {
        $this->db->query("SELECT pf.*, u.first_name, u.last_name 
                          FROM project_files pf 
                          LEFT JOIN users u ON pf.uploaded_by = u.id 
                          WHERE pf.project_id = :pid 
                          ORDER BY pf.created_at DESC");
        $this->db->bind(':pid', $project_id);
        return $this->db->fetchAll();
    }

    /**
     * Fetch team members allocated to a specific project along with workload stats.
     */
    public function getProjectResources($project_id) {
        $this->ensureResourceColumns();

        try {
            $this->db->query("
                SELECT
                    u.id,
                    u.first_name,
                    u.last_name,
                    u.email,
                    pm.role_in_project,
                    pm.allocated_hours,
                    COUNT(t.id) AS total_tasks,
                    SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks,
                    SUM(CASE WHEN t.status IN ('todo', 'in_progress', 'review') THEN 1 ELSE 0 END) AS active_tasks,
                    MIN(CASE WHEN t.status != 'completed' THEN t.deadline END) AS next_deadline
                FROM project_members pm
                JOIN users u ON pm.user_id = u.id
                LEFT JOIN tasks t ON t.project_id = pm.project_id AND t.assigned_to = pm.user_id
                WHERE pm.project_id = :pid
                GROUP BY
                    u.id, u.first_name, u.last_name, u.email,
                    pm.role_in_project, pm.allocated_hours
                ORDER BY pm.role_in_project = 'manager' DESC, pm.allocated_hours DESC, u.first_name ASC
            ");
            $this->db->bind(':pid', $project_id);
            return $this->db->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getResourcePlan($project_id, $project = [], $resources = []) {
        $this->ensureResourcePlanningTables();

        $defaultPlan = [
            'project_manager_label' => trim(($project['first_name'] ?? '') . ' ' . ($project['last_name'] ?? '')),
            'start_date' => $project['start_date'] ?? null,
            'end_date' => $project['deadline'] ?? null,
            'priority' => $project['priority'] ?? 'medium',
            'plan_status' => 'draft',
            'rows' => $this->buildDefaultResourcePlanRows($resources),
        ];

        try {
            $this->db->query("SELECT * FROM project_resource_plans WHERE project_id = :project_id LIMIT 1");
            $this->db->bind(':project_id', $project_id);
            $plan = $this->db->fetch();

            if (empty($plan)) {
                return $defaultPlan;
            }

            $this->db->query("
                SELECT department, required_employees, assigned_employees, experience_preferred, notes, display_order
                FROM project_resource_plan_rows
                WHERE plan_id = :plan_id
                ORDER BY display_order ASC, id ASC
            ");
            $this->db->bind(':plan_id', $plan['id']);
            $rows = $this->db->fetchAll();

            return [
                'project_manager_label' => $plan['project_manager_label'] ?: $defaultPlan['project_manager_label'],
                'start_date' => $plan['start_date'] ?: $defaultPlan['start_date'],
                'end_date' => $plan['end_date'] ?: $defaultPlan['end_date'],
                'priority' => $plan['priority'] ?: $defaultPlan['priority'],
                'plan_status' => $plan['plan_status'] ?: 'draft',
                'rows' => !empty($rows) ? $rows : $defaultPlan['rows'],
            ];
        } catch (Exception $e) {
            return $defaultPlan;
        }
    }

    public function saveResourcePlan($project_id, $header, $rows, $updated_by) {
        $this->ensureResourcePlanningTables();

        $this->db->beginTransaction();

        try {
            $this->db->query("SELECT id FROM project_resource_plans WHERE project_id = :project_id LIMIT 1");
            $this->db->bind(':project_id', $project_id);
            $existing = $this->db->fetch();

            $submittedAt = ($header['plan_status'] ?? 'draft') === 'submitted' ? date('Y-m-d H:i:s') : null;

            if (!empty($existing['id'])) {
                $planId = (int) $existing['id'];
                $this->db->query("
                    UPDATE project_resource_plans
                    SET project_manager_label = :project_manager_label,
                        start_date = :start_date,
                        end_date = :end_date,
                        priority = :priority,
                        plan_status = :plan_status,
                        updated_by = :updated_by,
                        submitted_at = :submitted_at
                    WHERE id = :id
                ");
                $this->db->bind(':project_manager_label', $header['project_manager_label']);
                $this->db->bind(':start_date', $header['start_date']);
                $this->db->bind(':end_date', $header['end_date']);
                $this->db->bind(':priority', $header['priority']);
                $this->db->bind(':plan_status', $header['plan_status']);
                $this->db->bind(':updated_by', $updated_by);
                $this->db->bind(':submitted_at', $submittedAt);
                $this->db->bind(':id', $planId);
                $this->db->execute();

                $this->db->query("DELETE FROM project_resource_plan_rows WHERE plan_id = :plan_id");
                $this->db->bind(':plan_id', $planId);
                $this->db->execute();
            } else {
                $this->db->query("
                    INSERT INTO project_resource_plans
                    (project_id, project_manager_label, start_date, end_date, priority, plan_status, updated_by, submitted_at)
                    VALUES
                    (:project_id, :project_manager_label, :start_date, :end_date, :priority, :plan_status, :updated_by, :submitted_at)
                ");
                $this->db->bind(':project_id', $project_id);
                $this->db->bind(':project_manager_label', $header['project_manager_label']);
                $this->db->bind(':start_date', $header['start_date']);
                $this->db->bind(':end_date', $header['end_date']);
                $this->db->bind(':priority', $header['priority']);
                $this->db->bind(':plan_status', $header['plan_status']);
                $this->db->bind(':updated_by', $updated_by);
                $this->db->bind(':submitted_at', $submittedAt);
                $this->db->execute();
                $planId = (int) $this->db->lastInsertId();
            }

            foreach ($rows as $index => $row) {
                $this->db->query("
                    INSERT INTO project_resource_plan_rows
                    (plan_id, department, required_employees, assigned_employees, experience_preferred, notes, display_order)
                    VALUES
                    (:plan_id, :department, :required_employees, :assigned_employees, :experience_preferred, :notes, :display_order)
                ");
                $this->db->bind(':plan_id', $planId);
                $this->db->bind(':department', $row['department']);
                $this->db->bind(':required_employees', $row['required_employees']);
                $this->db->bind(':assigned_employees', $row['assigned_employees']);
                $this->db->bind(':experience_preferred', $row['experience_preferred']);
                $this->db->bind(':notes', $row['notes']);
                $this->db->bind(':display_order', $index);
                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    private function buildDefaultResourcePlanRows($resources) {
        $assignedCount = count($resources);
        $defaultRows = [
            [
                'department' => 'Mechanical',
                'required_employees' => max(3, $assignedCount > 0 ? $assignedCount : 6),
                'assigned_employees' => min(3, $assignedCount),
                'experience_preferred' => '3-5 yrs',
                'notes' => 'HVAC, Piping, Structural',
            ],
            [
                'department' => 'Electrical',
                'required_employees' => max(2, $assignedCount > 0 ? max(2, $assignedCount - 1) : 4),
                'assigned_employees' => min(2, max(0, $assignedCount - 1)),
                'experience_preferred' => '2-4 yrs',
                'notes' => 'Power, Wiring, Control Panel',
            ],
            [
                'department' => 'Architecture',
                'required_employees' => max(1, $assignedCount > 0 ? max(1, $assignedCount - 2) : 3),
                'assigned_employees' => min(2, max(0, $assignedCount - 2)),
                'experience_preferred' => '4+ yrs',
                'notes' => 'AutoCAD, 3D Design, BOQ',
            ],
        ];

        return array_values(array_filter($defaultRows, fn($row) => $row['required_employees'] > 0));
    }

    /**
     * ==========================================
     * EMPLOYEE WORKSPACE MODULE
     * Fetch projects available for the employee to log time against.
     * ==========================================
     */
    public function getAssignedProjects($user_id) {
        try {
            $this->db->query("SELECT id, name FROM projects ORDER BY name ASC");
            return $this->db->fetchAll();
        } catch (Exception $e) {
            return []; // Graceful fallback
        }
    }

    /**
     * ==========================================
     * PM ISOLATED TEAM HUB ENGINE
     * Fetches only the employees assigned to the logged-in PM's projects.
     * ==========================================
     */
   /**
     * ==========================================
     * PM ISOLATED TEAM HUB ENGINE
     * Fetches only the employees assigned to the logged-in PM's projects.
     * ==========================================
     */
    public function getPmTeam($manager_id) {
        $this->ensureResourceColumns(); // Trigger the Auto-Healer BEFORE running the query!

        $this->db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.phone, 
                   SUM(pm.allocated_hours) as total_allocated_hours,
                   COUNT(DISTINCT p.id) as project_count,
                   GROUP_CONCAT(DISTINCT p.name SEPARATOR ', ') as assigned_projects
            FROM users u
            JOIN project_members pm ON u.id = pm.user_id
            JOIN projects p ON pm.project_id = p.id
            WHERE p.project_manager_id = :pm_id1 AND u.id != :pm_id2
            GROUP BY u.id
            ORDER BY u.first_name ASC
        ");
        
        // 🚀 ELITE FIX: Strict PDO binding requires unique identifiers for every placeholder!
        $this->db->bind(':pm_id1', $manager_id);
        $this->db->bind(':pm_id2', $manager_id);
        
        return $this->db->fetchAll();
    }
    /**
     * ==========================================
     * ENTERPRISE ANALYTICS ENGINE
     * Calculates KPI summaries for the PM Dashboard
     * ==========================================
     */
    public function getPmDashboardStats($manager_id) {
        // 1. Get total active projects
        $this->db->query("SELECT COUNT(id) as total FROM projects WHERE project_manager_id = :pm_id AND status != 'completed'");
        $this->db->bind(':pm_id', $manager_id);
        $projects = $this->db->fetch()['total'] ?? 0;

        // 2. Get unique team members deployed
        $this->db->query("SELECT COUNT(DISTINCT user_id) as total FROM project_members WHERE project_id IN (SELECT id FROM projects WHERE project_manager_id = :pm_id)");
        $this->db->bind(':pm_id', $manager_id);
        $team_size = $this->db->fetch()['total'] ?? 0;

        // 3. Get projects currently on hold
        $this->db->query("SELECT COUNT(id) as total FROM projects WHERE project_manager_id = :pm_id AND status = 'on_hold'");
        $this->db->bind(':pm_id', $manager_id);
        $hold_projects = $this->db->fetch()['total'] ?? 0;

        // 4. Get total number of projects
        $this->db->query("SELECT COUNT(id) as total FROM projects WHERE project_manager_id = :pm_id");
        $this->db->bind(':pm_id', $manager_id);
        $total_projects = $this->db->fetch()['total'] ?? 0;

        return [
            'active_projects' => $projects,
            'team_size' => $team_size,
            'hold_projects' => $hold_projects,
            'total_projects' => $total_projects,
            'pending_tasks' => 0 // Placeholder until we build the Task Engine
        ];
    }

    /**
     * Calculates Project Health by comparing Progress % vs Time Elapsed %
     */
    public function getProjectHealthMetrics($manager_id) {
        $this->db->query("
            SELECT id, name, priority, status, progress_pct, start_date, deadline,
                   DATEDIFF(deadline, start_date) as total_days,
                   DATEDIFF(CURDATE(), start_date) as days_elapsed
            FROM projects 
            WHERE project_manager_id = :pm_id AND status != 'completed'
            ORDER BY priority DESC, deadline ASC
        ");
        $this->db->bind(':pm_id', $manager_id);
        $projects = $this->db->fetchAll();

        $analyzed_projects = [];
        foreach ($projects as $p) {
            $total_days = max(1, (int)$p['total_days']); // Prevent division by zero
            $days_elapsed = max(0, (int)$p['days_elapsed']);
            
            // Calculate how much time has passed vs how much work is done
            $time_elapsed_pct = min(100, round(($days_elapsed / $total_days) * 100));
            $variance = $p['progress_pct'] - $time_elapsed_pct;

            // ELITE ALGORITHM: Risk Assessment
            if ($p['progress_pct'] == 100) {
                $health = 'Completed'; $color = 'green';
            } elseif ($time_elapsed_pct >= 100 && $p['progress_pct'] < 100) {
                $health = 'Overdue'; $color = 'red';
            } elseif ($variance < -20) {
                $health = 'Critical Risk'; $color = 'red'; // Way behind schedule
            } elseif ($variance < -5) {
                $health = 'Falling Behind'; $color = 'yellow'; // Slightly delayed
            } else {
                $health = 'On Track'; $color = 'blue'; // Progress matches or beats time
            }

            $p['time_elapsed_pct'] = $time_elapsed_pct;
            $p['variance'] = $variance;
            $p['health'] = $health;
            $p['health_color'] = $color;
            
            $analyzed_projects[] = $p;
        }

        return $analyzed_projects;
    }
}
