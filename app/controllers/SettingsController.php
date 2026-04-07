<?php
class SettingsController extends Controller {
    private $settingModel;

    public function __construct() {
        AuthMiddleware::handle(); 
        // Note: Ensure your permission system checks for admin rights here if needed
        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        $this->view('layouts/main', [
            'view_content' => 'settings/index',
            'title' => 'General Settings',
            'settings' => $this->settingModel->getAllSettings()
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            csrf_verify($_POST['csrf_token'] ?? '');

            $data = [];
            foreach ($_POST as $key => $value) {
                if ($key === 'csrf_token') continue; 
                $data[$key] = sanitize($value);      
            }

            if ($this->settingModel->updateSettings($data)) {
                Session::set('flash_success', 'System configuration updated successfully.');
            } else {
                Session::set('flash_error', 'Database Error: Could not update settings.');
            }
            
            $redirect_url = $_SERVER['HTTP_REFERER'] ?? base_url('/settings');
            header("Location: " . $redirect_url);
            exit;
        }
    }

    public function localization() {
        $this->view('layouts/main', [
            'view_content' => 'settings/localization', 
            'title' => 'Localization',
            // 🚀 THE FIX: Passing the DB settings back to the View!
            'settings' => $this->settingModel->getAllSettings() 
        ]);
    }

    public function smtp() {
        $this->view('layouts/main', [
            'view_content' => 'settings/smtp', 
            'title' => 'SMTP Settings',
            // 🚀 THE FIX: Passing the DB settings back to the View!
            'settings' => $this->settingModel->getAllSettings()
        ]);
    }

    public function security() {
        $this->view('layouts/main', [
            'view_content' => 'settings/security', 
            'title' => 'Security & API',
            // 🚀 THE FIX: Passing the DB settings back to the View!
            'settings' => $this->settingModel->getAllSettings()
        ]);
    }
}