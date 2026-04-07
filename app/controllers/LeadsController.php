<?php
class LeadsController extends Controller {
    private $leadModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->leadModel = $this->model('LeadModel');
    }

    public function create() {
        $this->view('layouts/main', [
            'view_content' => 'bd/leads/create',
            'title' => 'Create New Lead'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/leads/create');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $data = [
            'company_name' => sanitize($_POST['company_name'] ?? ''),
            'contact_person' => sanitize($_POST['contact_person'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'status' => sanitize($_POST['status'] ?? 'New'),
        ];

        if ($data['company_name'] === '') {
            Session::set('flash_error', 'Company name is required.');
            $this->redirect('/leads/create');
        }

        if ($data['contact_person'] === '' || strlen($data['contact_person']) < 3) {
            Session::set('flash_error', 'Contact person must be at least 3 characters.');
            $this->redirect('/leads/create');
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Session::set('flash_error', 'Please enter a valid email address.');
            $this->redirect('/leads/create');
        }

        $allowedStatuses = ['New', 'Active', 'Closed'];
        if (!in_array($data['status'], $allowedStatuses, true)) {
            Session::set('flash_error', 'Please choose a valid lead status.');
            $this->redirect('/leads/create');
        }

        $payload = [
            'company_name' => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => $data['status'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->leadModel->createLead($payload)) {
                Session::set('flash_success', 'Lead created successfully.');
                $this->redirect('/bd/dashboard');
            }
        } catch (\Throwable $e) {
            error_log('[LeadsController::store] ' . $e->getMessage());
            Session::set('flash_error', 'Lead could not be created right now. Please try again or contact support.');
            $this->redirect('/leads/create');
        }

        Session::set('flash_error', 'Failed to save lead. Please try again.');
        $this->redirect('/leads/create');
    }
}
