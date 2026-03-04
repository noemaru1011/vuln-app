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
    
    // リダイレクト先を取得（脆弱：検証なし）
    $redirect_url = $_GET['url'] ?? 'change_password.php';
    
    $db = new PDO('sqlite:../database.sqlite');
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // 脆弱：Locationヘッダーに直接リダイレクト先を設定
        // HTTPヘッダインジェクションが可能
        header("HX-Redirect: " . $redirect_url);
        exit;
    } else {
        echo "<p style='color:red;'>ユーザー名かパスワードが違います</p>";
    }
    exit;
}