<?php
class BdController extends Controller {
    private $bdModel;
    private $proposalModel;
    private $adminDocumentModel;
    private $notificationModel;
    private $activityLogModel;

    public function __construct() {
        AuthMiddleware::handle();
        $this->bdModel = $this->model('BDDashboard');
        $this->proposalModel = $this->model('Proposal');
        $this->adminDocumentModel = $this->model('AdminDocument');
        $this->notificationModel = $this->model('Notification');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    private function ensureBdAccess() {
        $roleId = (int) Session::get('role_id');
        $allowedRoleIds = [1, 2, 7];

        if (!in_array($roleId, $allowedRoleIds, true)) {
            Session::set('flash_error', 'Access denied.');
            $this->redirect('/dashboard');
        }
    }

    public function dashboard() {
        $this->ensureBdAccess();

        $totalLeads = $this->bdModel->getTotalLeads();
        $conversionRate = $this->bdModel->getConversionRate();
        $proposalSummary = $this->proposalModel->getSummary();
        $recentProposals = $this->proposalModel->getLatest(8);

        $data = [
            'view_content' => 'dashboard/bd',
            'title' => 'BD Command Center',
            'stats' => [
                'total' => $totalLeads,
                'active' => $proposalSummary['active'],
                'closed' => $proposalSummary['approved'],
                'conversion' => $conversionRate,
            ],
            'recent_leads' => $this->bdModel->getRecentLeads(),
            'proposal_summary' => $proposalSummary,
            'proposals' => $recentProposals,
            'upcoming_meetings' => [],
        ];

        $this->view('layouts/main', $data);
    }

    public function proposals() {
        $this->ensureBdAccess();

        $data = [
            'view_content' => 'bd/proposals',
            'title' => 'Proposal Pipeline',
            'proposals' => $this->proposalModel->getAll(),
            'proposal_summary' => $this->proposalModel->getSummary(),
        ];

        $this->view('layouts/main', $data);
    }

    public function createProposal() {
        $this->ensureBdAccess();

        $data = [
            'view_content' => 'bd/create_proposal',
            'title' => 'Create Proposal',
            'proposal_summary' => $this->proposalModel->getSummary(),
            'proposal' => null,
            'form_action' => base_url('/bd/storeProposal'),
            'submit_label' => 'Save Proposal',
            'page_label' => 'New Proposal',
            'page_heading' => 'Create a client-ready proposal',
        ];

        $this->view('layouts/main', $data);
    }

    public function editProposal($id = 0) {
        $this->ensureBdAccess();

        $proposal = $this->proposalModel->findById((int) $id);
        if (!$proposal) {
            Session::set('flash_error', 'Proposal not found.');
            $this->redirect('/bd/proposals');
        }

        $data = [
            'view_content' => 'bd/create_proposal',
            'title' => 'Edit Proposal',
            'proposal_summary' => $this->proposalModel->getSummary(),
            'proposal' => $proposal,
            'form_action' => base_url('/bd/updateProposal/' . (int) $proposal['id']),
            'submit_label' => 'Update Proposal',
            'page_label' => 'Edit Proposal',
            'page_heading' => 'Update proposal details',
        ];

        $this->view('layouts/main', $data);
    }

    public function storeProposal() {
        $this->ensureBdAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bd/createProposal');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        [$payload, $errors] = $this->validateProposalInput($_POST);

        if ($errors) {
            $message = implode(' ', $errors);
            if ($this->isJsonRequest()) {
                $this->jsonResponse(['status' => 'error', 'message' => $message], 422);
            }

            Session::set('flash_error', $message);
            $this->redirect('/bd/createProposal');
        }

        try {
            $proposalId = $this->proposalModel->create($payload);

            $this->activityLogModel->log(
                (int) Session::get('user_id'),
                "Created proposal #{$proposalId} for {$payload['client_name']}",
                'business_development'
            );

            $successMessage = 'Proposal created successfully.';

            if ($this->isJsonRequest()) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => $successMessage,
                    'proposal_id' => $proposalId,
                    'redirect' => base_url('/bd/proposals'),
                ]);
            }

            Session::set('flash_success', $successMessage);
            $this->redirect('/bd/proposals');
        } catch (\Throwable $e) {
            error_log('[BdController::storeProposal] ' . $e->getMessage());

            if ($this->isJsonRequest()) {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Unable to save the proposal right now. Please try again.',
                ], 500);
            }

            Session::set('flash_error', 'Unable to save the proposal right now. Please try again.');
            $this->redirect('/bd/createProposal');
        }
    }

    public function updateProposal($id = 0) {
        $this->ensureBdAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bd/proposals');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $proposalId = (int) $id;
        $proposal = $this->proposalModel->findById($proposalId);
        if (!$proposal) {
            Session::set('flash_error', 'Proposal not found.');
            $this->redirect('/bd/proposals');
        }

        [$payload, $errors] = $this->validateProposalInput($_POST);

        if ($errors) {
            Session::set('flash_error', implode(' ', $errors));
            $this->redirect('/bd/editProposal/' . $proposalId);
        }

        try {
            $this->proposalModel->updateById($proposalId, $payload);

            $this->activityLogModel->log(
                (int) Session::get('user_id'),
                "Updated proposal #{$proposalId} for {$payload['client_name']}",
                'business_development'
            );

            Session::set('flash_success', 'Proposal updated successfully.');
        } catch (\Throwable $e) {
            error_log('[BdController::updateProposal] ' . $e->getMessage());
            Session::set('flash_error', 'Unable to update the proposal right now. Please try again.');
        }

        $this->redirect('/bd/proposals');
    }

    public function getProposals() {
        $this->ensureBdAccess();

        try {
            $proposals = $this->proposalModel->getAll();

            foreach ($proposals as &$proposal) {
                $proposal['amount_formatted'] = number_format((float) ($proposal['amount'] ?? 0), 2);
                $proposal['created_at_formatted'] = !empty($proposal['created_at'])
                    ? date('M d, Y', strtotime($proposal['created_at']))
                    : '';
            }

            $this->jsonResponse([
                'status' => 'success',
                'data' => $proposals,
                'summary' => $this->proposalModel->getSummary(),
            ]);
        } catch (\Throwable $e) {
            error_log('[BdController::getProposals] ' . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Unable to load proposals right now.',
                'data' => [],
            ], 500);
        }
    }

    public function sendToAdmin() {
        $this->ensureBdAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bd/dashboard');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $proposalId = (int) ($_POST['proposal_id'] ?? 0);
        if ($proposalId <= 0) {
            Session::set('flash_error', 'Invalid proposal selected.');
            $this->redirect('/bd/dashboard');
        }

        $proposal = $this->proposalModel->findById($proposalId);
        if (!$proposal) {
            Session::set('flash_error', 'Proposal not found.');
            $this->redirect('/bd/dashboard');
        }

        try {
            $recipients = $this->proposalModel->getAdminRecipients();
            $title = 'Proposal ready for admin review';
            $message = sprintf(
                '%s for %s is ready for admin processing.',
                $proposal['project_name'],
                $proposal['client_name']
            );
            $link = base_url('/bd/proposals');

            foreach ($recipients as $recipient) {
                $this->notificationModel->send((int) $recipient['id'], $title, $message, $link);
            }

            $this->activityLogModel->log(
                (int) Session::get('user_id'),
                "Sent proposal #{$proposalId} to admin",
                'business_development'
            );

            Session::set('flash_success', 'Proposal shared with the admin team successfully.');
        } catch (\Throwable $e) {
            error_log('[BdController::sendToAdmin] ' . $e->getMessage());
            Session::set('flash_error', 'Unable to notify the admin team right now.');
        }

        $this->redirect('/bd/dashboard');
    }

    public function uploadDocument() {
        $this->ensureBdAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bd/dashboard');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        if (empty($_FILES['project_doc']) || ($_FILES['project_doc']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            Session::set('flash_error', 'Please select a file to upload.');
            $this->redirect('/bd/dashboard');
        }

        try {
            $uploadDir = ROOT_PATH . '/uploads/admin-documents/';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Unable to prepare secure document storage.');
            }

            $allowedMimes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

            $savedFileName = Security::secureUpload($_FILES['project_doc'], $uploadDir, $allowedMimes, 10485760);

            $this->adminDocumentModel->createDocument([
                'original_name' => sanitize($_FILES['project_doc']['name'] ?? 'document'),
                'stored_name' => $savedFileName,
                'file_path' => $uploadDir . $savedFileName,
                'mime_type' => mime_content_type($uploadDir . $savedFileName) ?: ($_FILES['project_doc']['type'] ?? null),
                'file_size' => (int) ($_FILES['project_doc']['size'] ?? 0),
                'uploaded_by' => Session::get('user_id'),
                'source_module' => 'bd',
            ]);

            Session::set('flash_success', 'Document uploaded successfully. It is now available in the admin panel.');
        } catch (\Throwable $e) {
            error_log('[BdController::uploadDocument] ' . $e->getMessage());
            Session::set('flash_error', 'Document upload failed. Please try again.');
        }

        $this->redirect('/bd/dashboard');
    }

    public function uploadProposalDocument($id = 0) {
        $this->ensureBdAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bd/proposals');
        }

        csrf_verify($_POST['csrf_token'] ?? '');

        $proposalId = (int) $id;
        $proposal = $this->proposalModel->findById($proposalId);
        if (!$proposal) {
            Session::set('flash_error', 'Proposal not found for document upload.');
            $this->redirect('/bd/proposals');
        }

        if (empty($_FILES['proposal_doc']) || ($_FILES['proposal_doc']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            Session::set('flash_error', 'Please choose a file for this proposal.');
            $this->redirect('/bd/proposals');
        }

        try {
            $uploadDir = ROOT_PATH . '/uploads/admin-documents/';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Unable to prepare secure document storage.');
            }

            $allowedMimes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

            $savedFileName = Security::secureUpload($_FILES['proposal_doc'], $uploadDir, $allowedMimes, 10485760);

            $this->adminDocumentModel->createDocument([
                'original_name' => sanitize($_FILES['proposal_doc']['name'] ?? 'proposal-document'),
                'stored_name' => $savedFileName,
                'file_path' => $uploadDir . $savedFileName,
                'mime_type' => mime_content_type($uploadDir . $savedFileName) ?: ($_FILES['proposal_doc']['type'] ?? null),
                'file_size' => (int) ($_FILES['proposal_doc']['size'] ?? 0),
                'uploaded_by' => Session::get('user_id'),
                'proposal_id' => $proposalId,
                'project_name' => $proposal['project_name'] ?? null,
                'source_module' => 'bd',
            ]);

            $this->activityLogModel->log(
                (int) Session::get('user_id'),
                "Uploaded proposal document for proposal #{$proposalId}",
                'business_development'
            );

            Session::set('flash_success', 'Proposal document uploaded successfully and shared with the admin panel.');
        } catch (\Throwable $e) {
            error_log('[BdController::uploadProposalDocument] ' . $e->getMessage());
            Session::set('flash_error', 'Proposal document upload failed. Please try again.');
        }

        $redirect = $_SERVER['HTTP_REFERER'] ?? base_url('/bd/proposals');
        header('Location: ' . $redirect);
        exit;
    }

    private function isJsonRequest() {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

        return stripos($acceptHeader, 'application/json') !== false
            || strtolower($requestedWith) === 'xmlhttprequest';
    }

    private function validateProposalInput(array $input) {
        $clientName = sanitize($input['client_name'] ?? '');
        $projectName = sanitize($input['project_name'] ?? '');
        $status = strtolower(trim((string) ($input['status'] ?? 'pending')));
        $amountRaw = trim((string) ($input['amount'] ?? ''));

        $errors = [];

        if ($clientName === '') {
            $errors[] = 'Client name is required.';
        }

        if ($projectName === '') {
            $errors[] = 'Project name is required.';
        }

        if ($amountRaw === '' || !is_numeric($amountRaw)) {
            $errors[] = 'Proposal amount must be a valid number.';
        }

        $amount = (float) $amountRaw;
        if ($amountRaw !== '' && $amount < 0) {
            $errors[] = 'Proposal amount cannot be negative.';
        }

        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $errors[] = 'Invalid proposal status selected.';
        }

        return [[
            'client_name' => $clientName,
            'project_name' => $projectName,
            'amount' => number_format($amount, 2, '.', ''),
            'status' => $status,
        ], $errors];
    }
}
