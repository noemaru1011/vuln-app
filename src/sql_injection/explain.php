<?php
$title = "SQLインジェクション解説";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card mb-4">
    <h5 class="card-header">なぜSQLインジェクションは起きるのか？</h5>
    <div class="card-body p-4">
        <p>
            最大の原因は、
            「開発者が書いた命令（SQL）」と
            「ユーザーが入力したデータ」を
            同じ文字列として連結してしまうことにあります。
        </p>
    </div>
</div>


<div class="card mb-4">
    <h5 class="card-header">文字列連結の罠</h5>
    <div class="card-body p-4">
        <p>
            ユーザー入力をそのままSQLに埋め込むと、
            命令とデータの境界が壊れます。
        </p>

        <pre class="bg-light p-3 rounded"><code>
$sql = "SELECT * FROM USER WHERE USERNAME LIKE '%" . $name . "%'";
        </code></pre>
    </div>
</div>


<div class="card mb-4">
    <h5 class="card-header">攻撃が成立する瞬間の比較</h5>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>正常な入力（田中）</h6>
                        <p>単なる値として扱われる</p>
                        <pre class="bg-light p-2 rounded"><code>
... WHERE USERNAME LIKE '%田中%'
                        </code></pre>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>攻撃入力 (' OR 1=1 --)</h6>
                        <p>命令として解釈される</p>
                        <pre class="bg-light p-2 rounded"><code>
... WHERE USERNAME LIKE '%' OR 1=1 --%'
                        </code></pre>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="card mb-5">
    <h5 class="card-header">正しい対策</h5>
    <div class="card-body p-4">

        <h6>① プレースホルダの利用</h6>
        <p>
            SQL文と入力値を分離して実行します。
            データベースは命令と値を別物として扱います。
        </p>

        <pre class="bg-light p-3 rounded"><code>
$stmt = $pdo->prepare(
  "SELECT * FROM users WHERE username LIKE :name"
);
$stmt->bindValue(':name', "%$name%");
$stmt->execute();
        </code></pre>

        <hr>

        <h6>② 入力値の制限・チェック</h6>
        <p>
            文字数制限や許可文字のみ受付などを行います。
            これ単体では防御にはなりません。
        </p>

        <hr>

        <h6>③ ORMの利用</h6>
        <p>
            ORMは内部でプレースホルダを使用する設計が多く、
            SQLを直接組み立てる機会を減らします。
        </p>

    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>