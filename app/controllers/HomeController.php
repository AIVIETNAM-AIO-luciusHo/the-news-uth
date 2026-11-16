<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Models\SystemStatus;
use RuntimeException;

final class HomeController
{
    public function index(): void
    {
        $database = Database::connect(dirname(__DIR__, 2) . '/config/config.local.php');
        if (!SystemStatus::isAvailable($database)) {
            throw new RuntimeException('Service unavailable.');
        }

        View::render('public/home', 'Trang chủ');
    }
}
