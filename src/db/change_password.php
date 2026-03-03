<?php
// change_password.php
session_start();

// 1. ログインチェック（セッションにユーザーIDがあるか）
if (!isset($_SESSION['user_id'])) {
    echo '<p class="text-danger">ログインしてください</p>';
    exit;
}

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = "";

// 2. パスワード更新処理（POSTが来たら実行）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password']) && $_POST['new_password'] !== '') {
    $new_password = $_POST['new_password'] ?? '';

    // 現在ログインしているユーザー(SESSIONのID)のパスワードを更新
    $stmt = $db->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $_SESSION['user_id']]);
    echo "<p>✅ パスワードを更新しました！ 新しいパスワード: $new_password</p>";
}
