<?php
class SearchController extends Controller {
    public function __construct() {
        AuthMiddleware::handle();
    }
    
    /**
     * ==========================================
     * 🚀 UNIVERSAL SEARCH ROUTE
     * Catches the ?q=... parameter from the top navbar
     * ==========================================
     */
    public function index() {
        // Grab the search string securely
        $query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
        $role_id = (int) Session::get('role_id');
        $user_id = (int) Session::get('user_id');
        
        $searchModel = $this->model('Search');
        $results = $searchModel->globalSearch($query, $role_id, $user_id);
        
        $this->view('layouts/main', [
            'view_content' => 'search/results',
            'title' => 'Search Results: ' . ($query ?: 'Empty'),
            'query' => $query,
            'results' => $results
        ]);
    }
}
