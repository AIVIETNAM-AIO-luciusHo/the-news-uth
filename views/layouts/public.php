<?php use App\Core\View; ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= View::escape($title) ?> · The News UTH</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="masthead">
        <div class="container">
            <a class="brand" href="/">The News <span>UTH</span></a>
            <nav aria-label="Điều hướng chính">
                <a href="/">Trang chủ</a>
                <a href="/admin/login">Quản trị</a>
            </nav>
        </div>
    </header>
    <main class="container" id="main"><?php require $viewPath; ?></main>
    <footer class="container">The News UTH · Bản demo nền tảng · Dữ liệu tổng hợp</footer>
</body>
</html>
