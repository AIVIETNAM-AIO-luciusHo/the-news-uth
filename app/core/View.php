<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $template, string $title): void
    {
        $viewPath = dirname(__DIR__, 2) . '/views/' . $template . '.php';
        require dirname(__DIR__, 2) . '/views/layouts/public.php';
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
