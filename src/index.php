<?php define('BASE_PATH', __DIR__); ?>
<?php include "./includes/header.php" ?>


<p>下のボタンでデータベースを初期化できます。</p>
<p>最初に行ってください、またXSSなどでページが見れない時などにも行ってください。</p>

<div id="db-status">
    <button class="btn btn-danger"
        hx-post="./db/seed.php"
        hx-vals='{"action": "reset"}'
        hx-target="#db-status"
        hx-confirm="データベースを初期化しますか？">
        DBリセット+初期化
    </button>
</div>


<?php include "./includes/footer.php" ?>