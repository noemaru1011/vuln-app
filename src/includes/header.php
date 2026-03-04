<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webアプリケーションの脆弱性体験</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@1.9.3"></script>
</head>

<body class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar bg-primary-subtle shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="../">ホーム</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav">
                        <a class="nav-link" href="../sql_injection/sql_injection.php">SQLインジェクション体験</a>
                        <a class="nav-link" href="../sql_injection/explain.php">SQLインジェクション解説</a>
                        <a class="nav-link" href="../xss/xss.php">XSS体験</a>
                        <a class="nav-link" href="../xss/explain.php">XSS解説</a>
                        <a class="nav-link" href="../csrf/login.php?url=change_password.php">CSRF体験</a>
                        <a class="nav-link" href="../csrf/explain.php">CSRF解説</a>
                        <a class="nav-link" href="../clickjacking/clickjacking.php">クリックジャギング体験</a>
                        <a class="nav-link" href="../clickjacking/explain.php">クリックジャギング解説</a>
                        <a class="nav-link" href="../csrf/login.php?url=https://www.google.com/">リダイレクト系攻撃体験</a>
                        <a class="nav-link" href="../redirect/explain.php">リダイレクト系攻撃解説</a>
                        <a class="nav-link" href="../mail/mail.php">メールヘッダインジェクション体験</a>
                        <a class="nav-link" href="../mail/explain.php">メールヘッダインジェクション解説</a>

                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-fill">
        <!-- 最初からコンテナ -->
        <div class="container">
            <h1><?= $title ?? 'ホーム' ?></h1>