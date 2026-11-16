<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/core/Database.php';

use App\Core\Database;

function check(bool $condition, string $label): void
{
    if (!$condition) {
        throw new RuntimeException('FAIL: ' . $label);
    }
    echo 'PASS: ' . $label . PHP_EOL;
}

$db = Database::connect(dirname(__DIR__, 2) . '/config/config.local.php');
check($db->getAttribute(PDO::ATTR_EMULATE_PREPARES) === false, 'native prepares');
check($db->query('SELECT @@character_set_connection')->fetchColumn() === 'utf8mb4', 'utf8mb4');
foreach (['users' => 2, 'categories' => 2, 'articles' => 4, 'article_images' => 2] as $table => $count) {
    check((int) $db->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn() === $count, $table . ' seed count');
}
foreach ($db->query('SELECT password_hash FROM users') as $user) {
    check(password_verify('FoundationDemo!2026', $user['password_hash']), 'demo password hash');
    check(!password_verify('wrong-password', $user['password_hash']), 'wrong password rejected');
}
check((int) $db->query('SELECT COUNT(*) FROM articles a LEFT JOIN users u ON a.author_id = u.id LEFT JOIN categories c ON a.category_id = c.id WHERE u.id IS NULL OR c.id IS NULL')->fetchColumn() === 0, 'article references');
foreach ($db->query('SELECT path FROM article_images') as $image) {
    check(is_file(dirname(__DIR__, 2) . '/public/' . $image['path']), 'seed image asset exists');
}

// Every mutation is rolled back. Run only against the disposable seeded DB.
$rejections = [
    'author delete restricted' => ['DELETE FROM users WHERE id = 1', 1451],
    'category delete restricted' => ['DELETE FROM categories WHERE id = 1', 1451],
    'orphan article rejected' => ['UPDATE articles SET author_id = 999999 WHERE id = 1', 1452],
    'duplicate email rejected' => ["UPDATE users SET email = 'admin@example.test' WHERE id = 2", 1062],
    'duplicate slug rejected' => ["UPDATE articles SET slug = 'ban-tin-demo' WHERE id = 2", 1062],
    'invalid role rejected' => ["UPDATE users SET role = 'reader' WHERE id = 1", 1265],
    'invalid status rejected' => ["UPDATE articles SET status = 'invalid' WHERE id = 1", 1265],
    'published date required' => ['UPDATE articles SET published_at = NULL WHERE id = 1', 3819],
    'duplicate cover rejected' => ['UPDATE article_images SET is_cover = 1 WHERE id = 2', 1062],
    'relative image path required' => ["UPDATE article_images SET path = '../private' WHERE id = 2", 3819],
];
foreach ($rejections as $label => [$sql, $expectedCode]) {
    $db->beginTransaction();
    try {
        $db->exec($sql);
        throw new RuntimeException('Constraint missing: ' . $label);
    } catch (PDOException $exception) {
        check((int) $exception->errorInfo[1] === $expectedCode, $label);
    } finally {
        $db->rollBack();
    }
}
$db->beginTransaction();
try {
    $db->exec('DELETE FROM articles WHERE id = 1');
    check((int) $db->query('SELECT COUNT(*) FROM article_images WHERE article_id = 1')->fetchColumn() === 0, 'article image cascade');
} finally {
    $db->rollBack();
}
check((int) $db->query('SELECT COUNT(*) FROM article_images')->fetchColumn() === 2, 'test mutations rolled back');
