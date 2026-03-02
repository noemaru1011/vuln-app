<?php
// db/message.php

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $db->query("SELECT * FROM messages ORDER BY id DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 脆弱性のポイント：そのまま出力
        echo "<tr><td>{$row['id']}</td><td>{$row['content']}</td></tr>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $_POST['message'] !== '') {
    // 書き込み処理
    $message = $_POST['message'];
    $stmt = $db->prepare("INSERT INTO messages (content) VALUES (?)");
    $stmt->execute([$message]);
}
