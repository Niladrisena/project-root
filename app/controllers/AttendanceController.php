<?php
class AttendanceController extends Controller {
    
    public function clock_in() {
        AuthMiddleware::handle();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $attendanceModel = $this->model('Attendance');
            $user_id = Session::get('user_id'); 
            
            if ($attendanceModel->clock_in($user_id)) {
                Session::set('flash_success', 'Clocked in successfully! Have a great shift.');
            } else {
                Session::set('flash_error', 'Action failed. You may have already clocked in today.');
            }
        }
        // Redirect back to dashboard
        header('Location: ' . base_url('/dashboard/employee'));
        exit;
    }

    public function clock_out() {
        AuthMiddleware::handle();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $attendanceModel = $this->model('Attendance');
            $user_id = Session::get('user_id');
            
            if ($attendanceModel->clock_out($user_id)) {
                Session::set('flash_success', 'Clocked out successfully! Great work today.');
            } else {
                Session::set('flash_error', 'Could not process clock out.');
            }
        }
        header('Location: ' . base_url('/dashboard/employee'));
        exit;
    }
}