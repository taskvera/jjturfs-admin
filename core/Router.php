<?php
// core/Router.php

class Router {
    protected $routes = [];

    /**
     * Add a new route to the router.
     *
     * @param string   $method   The HTTP method (GET, POST, etc.).
     * @param string   $route    A regex pattern for matching the URI.
     * @param callable $callback The callback function to execute when the route matches.
     */
    public function add($method, $route, $callback) {
        $this->routes[] = compact('method', 'route', 'callback');
    }

    /**
     * Dispatch the request to the first matching route.
     *
     * @param string $method The HTTP method of the request.
     * @param string $uri    The URI of the request.
     * @return bool Returns true if a matching route was found and executed; otherwise, false.
     */
    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                // Execute the callback associated with the matched route.
                call_user_func_array($route['callback'], $matches);
                return true; // Indicate that the route was found and executed.
            }
        }
        // No route matched.
        return false;
    }
}
