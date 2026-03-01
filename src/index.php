<?php include "./includes/header.php" ?>

<div class="container mt-5">
    <div class="p-4 mb-4 bg-light border rounded-3">
        <h1>Vulnerability Lab</h1>
        <p>下のボタンでデータベースを初期化できます。</p>
        <p>最初に行ってください、またデータが壊れた時にも行ってください。</p>

        <div id="db-status">
            <button class="btn btn-danger"
                hx-post="./db/seed.php"
                hx-vals='{"action": "reset"}'
                hx-target="#db-status"
                hx-confirm="データベースを初期化しますか？">
                DBリセット+初期化
            </button>
        </div>
    </div>
</div>

<?php include "./includes/footer.php" ?>