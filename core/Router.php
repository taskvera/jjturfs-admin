<?php
// core/Router.php

class Router {
    protected $routes = [];

    public function add($method, $route, $callback) {
        $this->routes[] = compact('method', 'route', 'callback');
    }

    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                return call_user_func_array($route['callback'], $matches);
            }
        }
        // No route found
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
