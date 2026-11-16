<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class SystemStatus
{
    public static function isAvailable(PDO $database): bool
    {
        $statement = $database->prepare('SELECT ? AS connection_ok');
        $statement->execute([1]);

        return (int) $statement->fetchColumn() === 1;
    }
}
