<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;
use App\Core\View;

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
// Only fixed event codes are logged below; raw exceptions can contain credentials.
ini_set('log_errors', '0');
error_reporting(E_ALL);
date_default_timezone_set('UTC');

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\Core\\' => 'core/',
        'App\\Controllers\\' => 'controllers/',
        'App\\Models\\' => 'models/',
        'App\\Services\\' => 'services/',
    ];
    foreach ($prefixes as $prefix => $directory) {
        if (str_starts_with($class, $prefix)) {
            $name = substr($class, strlen($prefix));
            if (preg_match('/\A[A-Za-z][A-Za-z0-9]*\z/', $name)) {
                $file = dirname(__DIR__) . '/app/' . $directory . $name . '.php';
                if (is_file($file)) {
                    require $file;
                }
            }
            return;
        }
    }
});

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException('Application error.', 0, $severity, $file, $line);
});

header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'");
header('Cache-Control: no-store');

// Explicit asset allowlist also works when this file is the development router.
$assetTypes = [
    '/assets/css/app.css' => 'text/css; charset=UTF-8',
    '/assets/images/demo-campus.svg' => 'image/svg+xml',
    '/assets/images/demo-library.svg' => 'image/svg+xml',
];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (is_string($path) && isset($assetTypes[$path])) {
    header('Content-Type: ' . $assetTypes[$path]);
    if (in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD'], true)) {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
            readfile(__DIR__ . $path);
        }
    } else {
        http_response_code(405);
        header('Allow: GET, HEAD');
    }
    return;
}

$bufferLevel = ob_get_level();
ob_start();
try {
    $router = new Router();
    $router->get('/', [new HomeController(), 'index']);
    $router->get('/admin/login', [new AuthController(), 'login']);
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'HEAD') {
        ob_end_clean();
    } else {
        ob_end_flush();
    }
} catch (Throwable) {
    while (ob_get_level() > $bufferLevel) {
        ob_end_clean();
    }
    http_response_code(500);
    error_log('[foundation] request_failed');
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
        View::render('errors/500', 'Tạm thời gián đoạn');
    }
}
