<?php

namespace MSS;

class Router implements \ArrayAccess
{
    private $routes = [];

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function offsetExists($offset)
    {
        return isset($this->routes[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->routes[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->routes[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->routes[$offset]);
    }

    public function handler()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = explode('?', $path);
        $path = $path[0];
        if (strlen($path) > 1) {
            $path = rtrim($path, '/');
        }

        if ($this->offsetExists($path)) {
            return $this->offsetGet($path);
        }

        http_response_code(404);
        echo '<h1>Página não encontrada</h1>';
        exit();
    }
}
