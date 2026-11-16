<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes[$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        if (!is_string($path) || !isset($this->routes[$path])) {
            http_response_code(404);
            View::render('errors/404', 'Không tìm thấy trang');
            return;
        }

        if (!in_array($method, ['GET', 'HEAD'], true)) {
            http_response_code(405);
            header('Allow: GET, HEAD');
            return;
        }

        ($this->routes[$path])();
    }
}
