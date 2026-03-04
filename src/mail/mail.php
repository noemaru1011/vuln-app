<?php
$title = "メールヘッダインジェクション環境";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card">
    <h5 class="card-header">⚠️ 脆弱なお問い合わせフォーム</h5>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">送信元（ここを攻撃！）</label>
                <textarea name="from" class="form-control" rows="4" placeholder="user@example.com"></textarea>
                <div class="form-text text-muted">※メールアドレスの後に改行を入れて、Bcc: などを追加してください。</div>
            </div>
            <button type="submit" class="btn btn-danger w-100">メール送信（脆弱）</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 入力をそのまま受け取る（サニタイズなし）
            $from = $_POST['from'];
            $to = "admin@example.com";
            $subject = "Contact Form";
            $body = "お問い合わせがありました。";

            // 文字化け対策の最小ヘッダー
            $headers = "From: " . $from . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8";

            // 💡 第4引数に $headers をそのまま渡す
            if (mail($to, $subject, $body, $headers)) {
                echo '<div class="alert alert-warning mt-3">送信完了。Mailpitをチェック！</div>';
            } else {
                echo '<div class="alert alert-danger mt-3">送信失敗。改行コードが厳格に弾かれている可能性があります。</div>';
            }
        }
        ?>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>