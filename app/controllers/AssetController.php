<?php
class AssetController extends Controller {
    private $assetModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->assetModel = $this->model('Asset');
    }

    /* ========================================================
       ADMIN / OWNER MODULE (EXISTING CODE)
       ======================================================== */

    public function index() {
        PermissionMiddleware::handle('view', 'assets');
        
        $this->assetModel->db->query("SELECT a.*, e.first_name, e.last_name, c.name as category 
                                      FROM assets a 
                                      LEFT JOIN employees emp ON a.assigned_to = emp.id
                                      LEFT JOIN users e ON emp.user_id = e.id
                                      LEFT JOIN asset_categories c ON a.category_id = c.id
                                      ORDER BY a.created_at DESC");
        $assets = $this->assetModel->db->fetchAll();

        $this->view('layouts/main', [
            'view_content' => 'assets/index',
            'assets' => $assets,
            'title' => 'Enterprise Assets'
        ]);
    }

    public function assign() {
        PermissionMiddleware::handle('manage', 'assets');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token']);
            
            $asset_id = $_POST['asset_id'];
            $employee_id = $_POST['employee_id'];
            $condition = sanitize($_POST['condition_status']);
            $notes = sanitize($_POST['notes']);
            
            if ($this->assetModel->assignToEmployee($asset_id, $employee_id, $condition, $notes, Session::get('user_id'))) {
                Session::set('flash_success', 'Asset assigned successfully.');
            } else {
                Session::set('flash_error', 'Failed to assign asset.');
            }
            $this->redirect('/assets');
        }
    }

    /* ========================================================
       EMPLOYEE WORKSPACE MODULE (NEW CODE)
       ======================================================== */

    /**
     * Renders the Employee's personal assigned assets board
     */
    public function my() {
        $user_id = Session::get('user_id');
        
        $assets = $this->assetModel->getMyAssets($user_id);

        $this->view('layouts/main', [
            'view_content' => 'asset/index', // Points to the UI we built earlier
            'assets' => $assets,
            'title' => 'My Company Assets'
        ]);
    }
}