<?php

class Application {

    private $controller = null;
    private $controller_name = null;
    private $params = array();

    public function __construct() {
        $this->parse_url();
        $this->get_controller();
    }

    private function get_controller() {
        if(!$this->controller_name)
            return null;
        if(!ctype_alpha($this->controller_name))
            return null;

        $controller_name = ucfirst($this->controller_name) . 'Controller';
        $controller_path = ROOT . 'app/Controller/' . $controller_name . '.php';
        if(!file_exists($controller_path))
            return null;
        require $controller_path;

        $this->controller = new $controller_name;
    }

    private function parse_url() {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->controller_name = isset($url[0]) ? $url[0] : null;
            unset($url[0]);
            $this->params = array_values($url);

        }
    }

    public function dispatch() {
        if($this->controller) {
            return $this->controller->dispatch($this->params);
        }
        http_response_code(404);
    }


}

