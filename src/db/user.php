<?php
// 1. データベース接続
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 2. 検索処理（SQLインジェクション脆弱性あり）
if (isset($_GET['name']) && $_GET['name'] !== '') {
    $name = $_GET['name'];

    $sql = "SELECT * FROM user WHERE username LIKE '%$name%'";

    try {

        $result = $db->query($sql);
        $users = $result->fetchAll(PDO::FETCH_ASSOC);

        if (count($users) > 0) {
            foreach ($users as $user) {
                echo "<p class='mb-1 border-bottom pb-1'>" . htmlspecialchars($user['username']) . "</p>";
            }
        } else {
            echo "<p class='text-muted'>該当するユーザーはいません</p>";
        }
    } catch (Exception $e) {
        // エラーメッセージを表示（攻撃のヒントになる）
        echo "<div class='alert alert-danger small'>" . $e->getMessage() . "</div>";
    }
    exit;
}
