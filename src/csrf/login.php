<?php
$title = "CSRF実行環境(準備画面)";
require dirname(__DIR__) . "/includes/header.php";
?>


<div class="card">
    <h5 class="card-header">ログイン(SQLインジェクションでユーザー名とパスワードを特定してみてください)</h5>
    <div class="card-body">

        <form hx-post="../db/login.php" hx-target="#message">
            <div class="form-floating flex-grow-1 mb-3">
                <input type="text" id="user-name" name="username" class="form-control">
                <label for="user-name">ユーザー名</label>
            </div>

            <div class="form-floating flex-grow-1 mb-3">
                <input type="password" id="user-password" name="password" class="form-control">
                <label for="user-password">パスワード</label>
            </div>

            <button type="submit" class="btn btn-primary">
                ログイン
            </button>
        </form>
        <div id="message" class="mb-3"></div>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>