<?php
class AuthController extends Controller {
    private $userModel;
    private $logModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->logModel = $this->model('ActivityLog');
    }

    public function login() {
        // Redirect if already logged in
        if (Auth::check()) {
            $this->routeUserByRole(Session::get('role_id'));
            return; // Stops further execution safely
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');
            
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->findByEmail($email);

            if ($user) {
                // Check if locked out
                if ($user['lockout_until'] !== null && strtotime($user['lockout_until']) > time()) {
                    $this->view('auth/login', ['error' => 'Account locked. Please try again later.']);
                    return;
                }

                if ($user['status'] !== 'active') {
                    $this->view('auth/login', ['error' => 'Account is suspended or inactive. Contact HR.']);
                    return;
                }

                if (password_verify($password, $user['password_hash'])) {
                    // Success: Authenticate and Log
                    $this->userModel->recordLoginAttempt($user['id'], false);
                    
                    // Core Login Helper
                    Auth::login($user);
                    
                    // GOD-LEVEL FIX: Manually force role_id into the session!
                    // This permanently fixes the ROLE_ID: 0 issue.
                    Session::set('role_id', (int)$user['role_id']);
                    Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
                    
                    $this->logModel->log($user['id'], 'Logged In', 'Authentication');
                    
                    // Dynamic Routing Engine
                    $this->routeUserByRole($user['role_id']);
                    return;
                } else {
                    // Failed password
                    $this->userModel->recordLoginAttempt($user['id'], true);
                    $this->logModel->log($user['id'], 'Failed Login Attempt', 'Authentication');
                    $this->view('auth/login', ['error' => 'Invalid credentials.']);
                    return;
                }
            } else {
                // To prevent email enumeration, return generic error
                $this->view('auth/login', ['error' => 'Invalid credentials.']);
                return;
            }
        } else {
            // Render Login Form for GET requests
            $this->view('auth/login');
            return;
        }
    } // End of login() method

    public function logout() {
        if (Auth::check()) {
            $this->logModel->log(Session::get('user_id'), 'Logged Out', 'Authentication');
        }
        
        // Execute core logout (This natively handles the session destruction safely!)
        Auth::logout();
        
        $this->redirect('/auth/login');
    }
    /**
     * ==========================================
     * ENTERPRISE ROUTING ENGINE
     * ==========================================
     */
    private function routeUserByRole($role_id) {
        $role_id = (int)$role_id;

        switch ($role_id) {
            case 1:
                $this->redirect('/dashboard');
                break;
            case 3:
                $this->redirect('/hr/dashboard');
                break;
            case 4:
                $this->redirect('/pm/dashboard');
                break;
            case 5:
                $this->redirect('/it/dashboard');
                break;
            case 6:
                $this->redirect('/finance/dashboard');
                break;
            case 7:
                $this->redirect('/bd/dashboard');
                break;
            case 2:
            default:
                $this->redirect('/dashboard/employee');
                break;
        }
        exit;
    }
}