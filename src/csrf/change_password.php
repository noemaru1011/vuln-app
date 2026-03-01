<?php include '../includes/header.php'; ?>


<h2>マイページ（パスワード変更）</h2>
<p>ようこそ、<strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> さん</p>

<?php if ($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card p-4 shadow-sm mb-4">
    <form action="../db/auth.php" method="POST">
        <div class="mb-3">
            <label class="form-label">新しいパスワードを入力してください</label>
            <input type="text" name="new_password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">パスワードを変更する</button>
    </form>
</div>

<div class="alert alert-warning">
    <h5>⚠️ CSRF攻撃を体験する</h5>
    <p>ログインした状態（この画面が開いている状態）で、以下のボタンを<strong>別タブ</strong>で開いてみてください。</p>
    <p>別サイト（罠サイト）にアクセスしただけで、ここのパスワードが勝手に書き換わる様子が確認できます。</p>
    <a href="./trap.html" target="_blank" class="btn btn-danger">別タブで「罠サイト」へアクセス</a>
</div>

<hr>



<?php include '../includes/footer.php'; ?>