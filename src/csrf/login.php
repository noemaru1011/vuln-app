<?php include '../includes/header.php'; ?>

<div class="py-4">
    <h2 class="mb-4">ログイン(SQLインジェクションでユーザー名とパスワードを特定してみてください)</h2>

    <div id="message" class="mb-3"></div>

    <form hx-post="../db/auth.php" hx-target="#message">
        <div class="mb-3">
            <label class="form-label">ユーザー名</label>
            <input type="text" name="username" class="form-control w-50" required>
        </div>

        <div class="mb-3">
            <label class="form-label">パスワード</label>
            <input type="password" name="password" class="form-control w-50" required>
        </div>

        <button type="submit" class="btn btn-primary">
            ログイン
        </button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>