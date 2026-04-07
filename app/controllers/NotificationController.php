<?php
class NotificationController extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle();
    }

    /**
     * Silently fetches unread alerts for the AJAX polling engine
     */
    public function fetch() {
        $model = $this->model('Notification');
        $data = $model->getUnread(Session::get('user_id'));
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success', 
            'data' => $data, 
            'count' => count($data)
        ]);
        exit;
    }

    /**
     * Marks a single alert as read
     */
    public function read($id) {
        $model = $this->model('Notification');
        $model->markAsRead((int)$id, Session::get('user_id'));
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }

    /**
     * Clears all alerts
     */
    public function read_all() {
        $model = $this->model('Notification');
        $model->markAllAsRead(Session::get('user_id'));
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }
}