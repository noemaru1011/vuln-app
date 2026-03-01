<?php
// db/message.php

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $_POST['message'] !== '') {
    // 書き込み処理
    $message = $_POST['message'];
    $stmt = $db->prepare("INSERT INTO messages (content) VALUES (?)");
    $stmt->execute([$message]);
}

header('Location: ../xss/xss.php');
exit;
