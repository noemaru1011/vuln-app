<?php
// login.php
session_start();

// POSTリクエストが来た場合のみ、認証ロジックを走らせる
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['username']))
    && (isset($_POST['password']))
) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $db = new PDO('sqlite:../database.sqlite');
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // 成功時はリダイレクト先の案内を出す
        echo "<p style='color:green;'>ログイン成功！ <a href='change_password.php'>パスワード変更へ</a></p>";
    } else {
        // エラー時はメッセージだけ返す
        echo "<p style='color:red;'>ユーザー名かパスワードが違います</p>";
    }
    exit;
}
