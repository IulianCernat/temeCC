<?php

class Controller
{
    public function dispatch($params = array())
    {
        $request_method = strtolower($_SERVER["REQUEST_METHOD"]);
        $method_name = "handle_$request_method";
        if (method_exists($this, $method_name)) {
            if (!empty($params)) {
                return call_user_func_array(array($this, $method_name), $params);
            } else {
                return $this->$method_name();
            }
        } else {
            http_response_code(405);
        }
    }
}
