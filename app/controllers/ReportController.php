<?php
class ReportController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    public function index() {
        PermissionMiddleware::handle('generate', 'reports');
        $this->view('layouts/main', [
            'view_content' => 'reports/index',
            'title' => 'Master Report Builder'
        ]);
    }

    public function generate() {
        PermissionMiddleware::handle('generate', 'reports');
        // Logic to dynamically build SQL based on $_POST filters
        // Fetch data, and return as structured array for the view
        // Or stream directly to output buffer as CSV
        if(isset($_POST['export_type']) && $_POST['export_type'] == 'csv') {
            $this->exportCSV($data, 'enterprise_report_' . date('Ymd') . '.csv');
        }
    }

    private function exportCSV($data, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        if(!empty($data)) {
            fputcsv($output, array_keys($data[0])); // Headers
            foreach($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }
}