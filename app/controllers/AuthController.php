<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class AuthController
{
    public function login(): void
    {
        View::render('auth/login', 'Khu vực quản trị');
    }
}
