<?php
// login.php
session_start();

// POSTリクエストが来た場合のみ、認証ロジックを走らせる
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $db = new PDO('sqlite:../database.sqlite');
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // 成功時はリダイレクト先の案内を出すか、JavaScriptで飛ばす
        echo "<p style='color:green;'>ログイン成功！ <a href='change_password.php'>パスワード変更へ</a></p>";
    } else {
        // エラー時はメッセージだけ返す
        echo "<p style='color:red;'>ユーザー名かパスワードが違います</p>";
    }
    exit;
}

?>

<?php
// change_password.php
session_start();

// 1. ログインチェック（セッションにユーザーIDがあるか）
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = "";

// 2. パスワード更新処理（POSTが来たら実行）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';

    if ($new_password !== '') {
        try {
            // 現在ログインしているユーザー(SESSIONのID)のパスワードを更新
            $stmt = $db->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            $stmt->execute([$new_password, $_SESSION['user_id']]);

            $message = "✅ パスワードを更新しました！ 新しいパスワード: " . htmlspecialchars($new_password);
        } catch (Exception $e) {
            $message = "❌ エラー: " . $e->getMessage();
        }
    }
}
?>