<?php
session_start();

// 1. セッション変数をすべて解除
$_SESSION = array();

// 2. ブラウザ側のCookie（PHPSESSID）を過去の日付にして無効化
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// 3. サーバー側のセッションデータ破棄
session_destroy();

// 4. フロントエンドへのレスポンス
echo '<div class="alert alert-success">Cookieを削除しました。パスワード変更には再度ログインが必要です。</div>';
