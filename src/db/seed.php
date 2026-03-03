<?php
// 1. データベース接続
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset') {
    $db->beginTransaction();
    try {
        // --- テーブルの削除と再作成 ---
        $db->exec("DROP TABLE IF EXISTS user");
        $db->exec("DROP TABLE IF EXISTS admin_users");
        $db->exec("DROP TABLE IF EXISTS messages");

        // 一般ユーザー用（SQLインジェクション検索用）
        $db->exec("CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT
        )");

        // ログイン用（ログイン突破・CSRF演習用）
        $db->exec("CREATE TABLE admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT,
            password TEXT,
            role TEXT
        )");

        // メッセージ用（XSS・CSRF演習用）
        $db->exec("CREATE TABLE messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            content TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // --- シードデータの挿入 ---

        // 一般ユーザー（10名）
        $stmtUser = $db->prepare("INSERT INTO user (username) VALUES (?)");
        $seedUsers = ['佐藤太郎', '鈴木花子', '高橋一郎', '田中美咲', '伊藤健', '渡辺奈々', '山本翔', '中村優子', '小林直樹', '加藤真由美'];
        foreach ($seedUsers as $name) {
            $stmtUser->execute([$name]);
        }

        // 管理者・特定ユーザー（ログイン突破用）
        $stmtAdmin = $db->prepare("INSERT INTO admin_users (username, password, role) VALUES (?, ?, ?)");
        $stmtAdmin->execute(['admin', 'admin123', 'administrator']); // 管理者
        $stmtAdmin->execute(['alice', 'password555', 'user']);        // 一般ユーザー

        // 初期メッセージ（XSS用）
        $stmtMsg = $db->prepare("INSERT INTO messages (content) VALUES (?)");
        $seeds = [
            "ようこそ！この掲示板は自由に書き込めます。",
            "<b>太字</b>や<u>下線</u>などのHTMLタグもそのまま画面に出ます",
            "左のボタンから攻撃ペイロードを試してみてください。",
        ];
        foreach ($seeds as $s) {
            $stmtMsg->execute([$s]);
        }

        $db->commit();
        echo '<div class="alert alert-success">データベースの初期化と初期データの挿入が完了しました！</div>';
    } catch (Exception $e) {
        $db->rollBack();
        echo "初期化エラー: " . $e->getMessage();
    }
    exit;
}
