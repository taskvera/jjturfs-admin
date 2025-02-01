<?php
// core/Router.php
namespace Core;

class Router {
    protected $routes = [];

    /**
     * Add a new route.
     *
     * @param string   $method   HTTP method (GET, POST, etc.).
     * @param string   $route    A regex pattern for the URI.
     * @param callable $callback Callback to execute when the route matches.
     */
    public function add(string $method, string $route, callable $callback) {
        $this->routes[] = compact('method', 'route', 'callback');
    }

    /**
     * Dispatch the request to the matching route.
     *
     * @param string $method HTTP method of the request.
     * @param string $uri    Request URI.
     * @return bool True if a route was matched and executed; otherwise, false.
     */
    public function dispatch(string $method, string $uri): bool {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                call_user_func_array($route['callback'], $matches);
                return true;
            }
        }
        return false;
    }
}
