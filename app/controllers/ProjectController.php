<?php
class ProjectController extends Controller {
    private $projectModel;
    private $taskModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->projectModel = $this->model('Project');
        $this->taskModel    = $this->model('Task');
    }

    /**
     * ✅ Polymorphic Database Factory
     * Safely bypasses private Singleton constructor.
     */
    private function getDbInstance() {
        if (method_exists('Database', 'getInstance'))   return Database::getInstance();
        if (method_exists('Database', 'getConnection')) return Database::getConnection();
        if (method_exists('Database', 'getDb'))         return Database::getDb();

        return new class {
            private \PDO $pdo;
            private ?\PDOStatement $stmt = null;
            public function __construct() {
                $this->pdo = new \PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER, DB_PASS,
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
            }
            public function query(string $sql): void { $this->stmt = $this->pdo->prepare($sql); }
            public function bind(string $p, mixed $v): void { $this->stmt->bindValue($p, $v); }
            public function execute(): bool { return $this->stmt->execute(); }
            public function fetch(): array { $this->execute(); return $this->stmt->fetch(\PDO::FETCH_ASSOC) ?: []; }
            public function fetchAll(): array { $this->execute(); return $this->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: []; }
        };
    }

    public function index() {
        PermissionMiddleware::handle('view', 'projects');
        $user     = Auth::user();
        $projects = $this->projectModel->getAllForUser($user['id'], $user['role_slug']);

        $this->view('layouts/main', [
            'view_content' => 'projects/index',
            'projects'     => $projects,
            'title'        => 'Projects Overview',
        ]);
    }

    public function create() {
        PermissionMiddleware::handle('create', 'projects');
        $userModel = $this->model('User');

        // ✅ FIX: Read exclusively from the global macros set by SystemConfig.
        // These are the single source of truth. Never re-derive currency here.
        $base_currency   = defined('SYS_CURRENCY_CODE') ? SYS_CURRENCY_CODE : 'USD';
        $currency_symbol = defined('SYS_CURRENCY')      ? SYS_CURRENCY      : '$';

        // --- Resolve currency_id from the currencies table ---
        $currency_id = 1; // safe fallback
        try {
            $db = $this->getDbInstance();

            // Try to find existing row
            $db->query("SELECT id FROM currencies WHERE code = :code LIMIT 1");
            $db->bind(':code', $base_currency);
            // ✅ FIX: fetchAll result is already cast to assoc array by our factory.
            $curr = $db->fetch();

            if (!empty($curr['id'])) {
                $currency_id = (int) $curr['id'];
            } else {
                // Row doesn't exist — insert it, then retrieve the new id
                $db->query("INSERT INTO currencies (code, symbol, exchange_rate, is_base) VALUES (:code, :sym, 1.00, 1)");
                $db->bind(':code', $base_currency);
                $db->bind(':sym',  $currency_symbol);
                $db->execute();

                $db->query("SELECT id FROM currencies WHERE code = :code LIMIT 1");
                $db->bind(':code', $base_currency);
                $newCurr = $db->fetch();
                $currency_id = !empty($newCurr['id']) ? (int) $newCurr['id'] : 1;
            }
        } catch (\Throwable $e) {
            error_log('[ProjectController::create currency_id] ' . $e->getMessage());
            // $currency_id stays 1 — acceptable fallback, project can still be created
        }

        // --- Fetch clients & managers for UI dropdowns ---
        $clients  = [];
        $managers = [];
        try {
            $db = $this->getDbInstance();

            // Detect the name column dynamically
            $db->query("SHOW COLUMNS FROM clients");
            $cols     = $db->fetchAll(); // ✅ already arrays from factory
            $colNames = array_column($cols, 'Field');
            $nameCol  = in_array('company_name', $colNames) ? 'company_name'
                      : (in_array('name', $colNames)        ? 'name' : 'id');

            $db->query("SELECT id, `{$nameCol}` AS display_name FROM clients ORDER BY `{$nameCol}` ASC");
            $clients = $db->fetchAll();

        } catch (\Throwable $e) {
            error_log('[ProjectController::create dropdowns] ' . $e->getMessage());
        }

        $managers = $userModel->getActiveProjectManagers();

        // --- Handle form submission ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify($_POST['csrf_token']);

            $client_id = $_POST['client_id'] ?? '';

            // Inline client creation
            if ($client_id === 'new') {
                try {
                    $db = $this->getDbInstance();
                    $db->query("SHOW COLUMNS FROM clients");
                    $cols = $db->fetchAll();

                    $insertCols  = [];
                    $insertVals  = [];
                    $bindParams  = [];
                    $providedName = sanitize(trim($_POST['new_client_name'] ?? ''));
                    $providedContact = sanitize(trim($_POST['new_client_contact_person'] ?? ''));
                    $providedEmail = sanitize(trim($_POST['new_client_email'] ?? ''));

                    if ($providedName === '' || $providedContact === '' || $providedEmail === '') {
                        throw new \InvalidArgumentException('Please enter the new client company name, contact person, and email address.');
                    }

                    if (!filter_var($providedEmail, FILTER_VALIDATE_EMAIL)) {
                        throw new \InvalidArgumentException('Please enter a valid email address for the new client.');
                    }

                    $db->query("SELECT id FROM clients WHERE email = :email LIMIT 1");
                    $db->bind(':email', $providedEmail);
                    $existingClient = $db->fetch();
                    if (!empty($existingClient['id'])) {
                        throw new \InvalidArgumentException('A client with this email address already exists. Please select that client or use a different email.');
                    }

                    foreach ($cols as $col) {
                        $field = $col['Field'];
                        $type  = strtolower($col['Type'] ?? '');
                        $extra = strtolower($col['Extra'] ?? '');
                        $null  = strtoupper($col['Null'] ?? 'YES');

                        if (strtolower($field) === 'id' || $extra === 'auto_increment') continue;

                        if (in_array(strtolower($field), ['name', 'company_name', 'client_name'])) {
                            $insertCols[] = "`$field`";
                            $insertVals[] = ":$field";
                            $bindParams[":$field"] = $providedName;
                        } elseif (strtolower($field) === 'contact_person') {
                            $insertCols[] = "`$field`";
                            $insertVals[] = ":$field";
                            $bindParams[":$field"] = $providedContact;
                        } elseif (strtolower($field) === 'email') {
                            $insertCols[] = "`$field`";
                            $insertVals[] = ":$field";
                            $bindParams[":$field"] = $providedEmail;
                        } elseif ($null === 'NO' && $col['Default'] === null) {
                            $insertCols[] = "`$field`";
                            $insertVals[] = ":$field";
                            if (str_contains($type, 'int') || str_contains($type, 'decimal')) {
                                $bindParams[":$field"] = 0;
                            } elseif (str_contains($type, 'date') || str_contains($type, 'time')) {
                                $bindParams[":$field"] = date('Y-m-d H:i:s');
                            } else {
                                $bindParams[":$field"] = '';
                            }
                        }
                    }

                    if (!empty($insertCols)) {
                        $db->query("INSERT INTO clients (" . implode(', ', $insertCols) . ") VALUES (" . implode(', ', $insertVals) . ")");
                        foreach ($bindParams as $key => $val) $db->bind($key, $val);
                        $db->execute();

                        $db->query("SELECT LAST_INSERT_ID() AS new_id");
                        $res = $db->fetch();
                        if (!empty($res['new_id'])) {
                            $client_id = $res['new_id'];
                        }
                    }
                } catch (\Throwable $e) {
                    Session::set('flash_error', 'Could not auto-create client: ' . $e->getMessage());
                    $this->redirect('/project/create');
                    exit;
                }
            }

            // Build project data array
            $data = [
                'client_id'          => $client_id,
                'currency_id'        => $currency_id,
                'project_manager_id' => $_POST['project_manager_id'] ?? null,
                'name'               => sanitize($_POST['name'] ?? ''),
                'description'        => sanitize($_POST['description'] ?? ''),
                'status'             => sanitize($_POST['status'] ?? 'planning'),
                'priority'           => sanitize($_POST['priority'] ?? 'medium'),
                'estimated_budget'   => $_POST['estimated_budget'] ?? 0,
                'total_hours'        => $_POST['total_hours'] ?? 0,
                'start_date'         => $_POST['start_date'] ?? date('Y-m-d'),
                'deadline'           => $_POST['deadline'] ?? null,
            ];

            try {
                $project_id = $this->projectModel->createProject($data);

                if ($project_id) {
                    $logModel = $this->model('ActivityLog');
                    $logModel->log(Session::get('user_id'), "Created Project: {$data['name']}", 'Projects');
                    Session::set('flash_success', 'Project successfully initialized and assigned!');
                    $this->redirect('/project/show/' . $project_id);
                    exit;
                }
            } catch (\PDOException $e) {
                Session::set('flash_error', 'Database Error: ' . $e->getMessage());
            } catch (\Throwable $e) {
                Session::set('flash_error', 'System Error: ' . $e->getMessage());
            }
        }

        $this->view('layouts/main', [
            'view_content' => 'projects/create',
            'title'        => 'Create New Project',
            'clients'      => $clients,
            'managers'     => $managers,
        ]);
    }

    public function show($id) {
        PermissionMiddleware::handle('view', 'projects');
        $user    = Auth::user();
        $project = $this->projectModel->findById($id);
        if (!$project) $this->redirect('/project');

        $tasks      = $this->taskModel->getTasksByProject($id);
        $progress   = $this->projectModel->calculateProgress($id);
        $files      = $this->projectModel->getProjectFiles($id);
        $resources  = $this->projectModel->getProjectResources($id);
        $availableResources = $this->projectModel->getAvailableResources();
        $resourceSummary = [
            'headcount' => count($resources),
            'allocated_hours' => array_sum(array_map(fn($member) => (float) ($member['allocated_hours'] ?? 0), $resources)),
            'active_tasks' => array_sum(array_map(fn($member) => (int) ($member['active_tasks'] ?? 0), $resources)),
            'completed_tasks' => array_sum(array_map(fn($member) => (int) ($member['completed_tasks'] ?? 0), $resources)),
        ];
        $resourcePlan = $this->projectModel->getResourcePlan($id, $project, $resources);
        $hourPlan = $this->projectModel->getHourPlan($id, $project, $resourcePlan);

        $this->view('layouts/main', [
            'view_content' => 'projects/view',
            'project'      => $project,
            'tasks'        => $tasks,
            'progress'     => $progress,
            'files'        => $files,
            'resources'    => $resources,
            'availableResources' => $availableResources,
            'resourcePlan' => $resourcePlan,
            'hourPlan'     => $hourPlan,
            'resourceSummary' => $resourceSummary,
            'active_tab'   => $_GET['tab'] ?? 'overview',
            'title'        => 'Project Dashboard: ' . sanitize($project['name']),
        ]);
    }

    public function save_resource_plan($id) {
        PermissionMiddleware::handle('view', 'projects');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/project/show/' . $id . '?tab=resources');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $project = $this->projectModel->findById($id);
        if (!$project) {
            Session::set('flash_error', 'Project not found.');
            $this->redirect('/project');
        }

        $departments = $_POST['department'] ?? [];
        $required = $_POST['required_employees'] ?? [];
        $assigned = $_POST['assigned_employees'] ?? [];
        $notes = $_POST['notes'] ?? [];
        $rows = [];

        foreach ($departments as $index => $department) {
            $department = sanitize(trim((string) $department));
            if ($department === '') {
                continue;
            }

            $rows[] = [
                'department' => $department,
                'required_employees' => max(0, (int) ($required[$index] ?? 0)),
                'assigned_employees' => max(0, (int) ($assigned[$index] ?? 0)),
                'notes' => sanitize(trim((string) ($notes[$index] ?? ''))),
            ];
        }

        if (empty($rows)) {
            Session::set('flash_error', 'Add at least one department row before saving the resource plan.');
            $this->redirect('/project/show/' . $id . '?tab=resources');
        }

        $header = [
            'project_manager_label' => sanitize(trim((string) ($_POST['project_manager_label'] ?? 'Project Manager'))),
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'priority' => sanitize(trim((string) ($_POST['priority'] ?? 'medium'))),
            'plan_status' => ($_POST['plan_action'] ?? 'draft') === 'submit' ? 'submitted' : 'draft',
        ];

        $saved = $this->projectModel->saveResourcePlan($id, $header, $rows, Session::get('user_id'));

        if ($saved) {
            Session::set('flash_success', $header['plan_status'] === 'submitted'
                ? 'Resource plan submitted successfully.'
                : 'Resource plan saved as draft.'
            );
        } else {
            Session::set('flash_error', 'Unable to save the resource plan right now.');
        }

        $this->redirect('/project/show/' . $id . '?tab=resources');
    }

    public function save_hour_plan($id) {
        PermissionMiddleware::handle('view', 'projects');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/project/show/' . $id . '?tab=hour');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $project = $this->projectModel->findById($id);
        if (!$project) {
            Session::set('flash_error', 'Project not found.');
            $this->redirect('/project');
        }

        $departments = $_POST['department'] ?? [];
        $estimated = $_POST['estimated_hours'] ?? [];
        $assigned = $_POST['assigned_hours'] ?? [];
        $week1 = $_POST['week_1_hours'] ?? [];
        $week2 = $_POST['week_2_hours'] ?? [];
        $rows = [];

        foreach ($departments as $index => $department) {
            $department = sanitize(trim((string) $department));
            if ($department === '') {
                continue;
            }

            $rows[] = [
                'department' => $department,
                'estimated_hours' => max(0, (float) ($estimated[$index] ?? 0)),
                'assigned_hours' => max(0, (float) ($assigned[$index] ?? 0)),
                'week_1_hours' => max(0, (float) ($week1[$index] ?? 0)),
                'week_2_hours' => max(0, (float) ($week2[$index] ?? 0)),
            ];
        }

        if (empty($rows)) {
            Session::set('flash_error', 'Add at least one department row before saving the hour plan.');
            $this->redirect('/project/show/' . $id . '?tab=hour');
        }

        $planningMonthInput = trim((string) ($_POST['planning_month'] ?? ''));
        $planningMonth = $planningMonthInput !== '' ? date('F Y', strtotime($planningMonthInput . '-01')) : date('F Y');

        $header = [
            'planning_month' => $planningMonth,
            'comparison_month' => $planningMonth,
            'plan_status' => ($_POST['plan_action'] ?? 'draft') === 'submit' ? 'submitted' : 'draft',
        ];

        $saved = $this->projectModel->saveHourPlan($id, $header, $rows, Session::get('user_id'));

        if ($saved) {
            Session::set('flash_success', $header['plan_status'] === 'submitted'
                ? 'Hour plan submitted successfully.'
                : 'Hour plan saved as draft.'
            );
        } else {
            Session::set('flash_error', 'Unable to save the hour plan right now.');
        }

        $this->redirect('/project/show/' . $id . '?tab=hour');
    }

    public function upload() {
        PermissionMiddleware::handle('create', 'projects');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify($_POST['csrf_token']);
            $projectId = $_POST['project_id'];
            try {
                $uploadDir    = ROOT_PATH . '/public/uploads/projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $allowedMimes = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ];
                $savedFileName = Security::secureUpload($_FILES['project_file'], $uploadDir, $allowedMimes, 10485760);
                $this->projectModel->saveFileRecord($projectId, $savedFileName, sanitize($_FILES['project_file']['name']), Session::get('user_id'));
                Session::set('flash_success', 'Secure file uploaded successfully.');
            } catch (\Exception $e) {
                Session::set('flash_error', 'Upload failed: ' . $e->getMessage());
            }
            $this->redirect('/project/show/' . $projectId);
        }
    }
}
