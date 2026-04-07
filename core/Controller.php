<?php
class Controller {
    
    /**
     * Enterprise Model Loader
     */
    public function model($model) {
        $file = ROOT_PATH . '/app/models/' . $model . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            if (class_exists($model)) {
                return new $model();
            } else {
                throw new Exception("Enterprise MVC Error: 'class {$model}' missing in {$model}.php");
            }
        } else {
            throw new Exception("Enterprise MVC Error: Model '{$model}' not found.");
        }
    }

    /**
     * Enterprise View Loader
     * STRICTLY PROTECTED: Cannot be overridden or called as a route by child controllers.
     */
    protected function view($view, $data = []) {
        $file = ROOT_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($file)) {
            extract($data);
            require_once $file;
        } else {
            throw new Exception("Enterprise MVC Error: View '{$view}' not found.");
        }
    }

    public function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function redirect($url) {
        $config = require ROOT_PATH . '/config/app.php';
        $baseUrl = rtrim($config['base_url'], '/');
        $targetUrl = ltrim($url, '/');
        header('Location: ' . $baseUrl . '/' . $targetUrl);
        exit;
    }
}