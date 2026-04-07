<?php
class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Determine Controller Name (Capitalize first letter + Controller)
        $controllerName = 'DashboardController'; 
        if (isset($url[0]) && $url[0] != '') {
            $controllerName = ucwords(strtolower($url[0])) . 'Controller';
        }

        // 2. Safely Check if Controller File Exists
        $controllerFile = '../app/controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            $this->controller = $controllerName;
            unset($url[0]);
        } else {
            // ENTERPRISE FIX: Force 404 instead of 500 Fatal Error
            $this->force404("Module or Controller '{$controllerName}' not found.");
            return;
        }

        // 3. Instantiate Controller (Autoloader handles the require)
        $this->controller = new $this->controller;

        // 4. Safely Determine Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->force404("Method '{$url[1]}' not found in {$controllerName}.");
                return;
            }
        }

        // 5. Execute Controller Method
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function force404($message) {
        http_response_code(404);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px; background: #f9fafb; height: 100vh;'>";
        echo "<h1 style='font-size: 3rem; color: #dc2626; margin-bottom: 10px;'>404 - Not Found</h1>";
        echo "<p style='color: #4b5563; margin-bottom: 30px;'>{$message}</p>";
        echo "<a href='javascript:history.back()' style='background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;'>&larr; Go Back</a>";
        echo "</div>";
        exit;
    }
}