<?php
$title = "CSRF実行環境(体験画面)";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card">
    <h5 class="card-header">ログイン(SQLインジェクションでユーザー名とパスワードを特定してみてください)</h5>
    <div class="card-body">

        <form hx-post="../db/change_password.php" hx-target="#message">
            <div class="form-floating flex-grow-1 mb-3">
                <input type="text" id="new-password" name="new_password" class="form-control">
                <label for="new-password">新しいパスワードを入力してください</label>
            </div>
            <button type="submit" class="btn btn-primary">パスワードを変更する</button>
        </form>
        <div id="message" class="mb-3"></div>
    </div>
</div>


<div class="card mt-4 gap-4">
    <h5 class="card-header">⚠️ CSRF攻撃を体験する</h5>
    <p>ログインした状態（この画面が開いている状態）で、以下のボタンを別タブで開いてみてください。</p>
    <p>別サイト（罠サイト）にアクセスしただけで、パスワードが勝手に書き換わります。</p>
    <p>アクセスした後、DBを確認したり、再度ログインを試してみてください。</p>
    <a href="./trap.html" class="w-25 btn btn-danger">別タブで「罠サイト」へアクセス</a>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>