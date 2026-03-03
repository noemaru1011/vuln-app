<?php
$title = "SQLインジェクション解説";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card mb-4 border-danger">
    <h5 class="card-header bg-danger text-white">SQLインジェクションとは何か？</h5>
    <div class="card-body p-4">
        <p class="lead">
            データベースへの命令（SQL文）に、<strong>「悪意のある命令」を注入（インジェクション）</strong>する攻撃です。
        </p>
        <p>
            本来見ることができないデータの盗み出し、データの改ざん、さらにはアカウントの乗っ取りなどが引き起こされる非常に危険な脆弱性です。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ脆弱性が生まれるのか？</h5>
    <div class="card-body p-4">
        <p>最大の原因は、プログラム内で<strong>「命令」と「ユーザー入力（データ）」を区別せずに連結してしまう</strong>ことにあります。</p>

        <div class="alert alert-secondary">
            <h6>文字列連結の罠（危険なコード例）</h6>
            <pre class="mb-0"><code>$sql = "SELECT * FROM users WHERE username = '" . $user_input . "'";</code></pre>
        </div>
        <p class="small text-muted mt-2">
            ※ ユーザーが入力した <code>'</code>（シングルクォート）などの記号が、SQLの構造そのものを書き換えてしまいます。
        </p>
    </div>
</div>



<div class="card mb-4">
    <h5 class="card-header">攻撃成立の比較：命令とデータの境界崩壊</h5>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">✅ 正常な入力：<code>taro</code></h6>
                        <p class="small">入力値は単なる「名前」として扱われる。</p>
                        <pre class="bg-light p-2 rounded small"><code>SELECT * FROM users 
WHERE username = '<span class="text-success">taro</span>'</code></pre>
                        <p class="small text-success fw-bold">結果：taroさんの情報を取得</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">⚠️ 攻撃入力：<code>' OR '1'='1</code></h6>
                        <p class="small">入力値が「SQLの命令」の一部に化ける。</p>
                        <pre class="bg-light p-2 rounded small"><code>SELECT * FROM users 
WHERE username = '' <span class="text-danger">OR '1'='1'</span></code></pre>
                        <p class="small text-danger fw-bold">結果：全ユーザーの情報が流出</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：安全なデータベース操作</h5>
    <div class="card-body p-4">

        <h6>① プレースホルダ（静的プレースホルダ）の利用 <span class="badge bg-primary">推奨</span></h6>
        <p>
            SQLの「型」を先に作り、値を後から流し込む方法です。データベース側で「ここから先はただのデータだ」と厳格に区別されるため、100%安全です。
        </p>
        <pre class="bg-light p-3 rounded"><code>// 1. 命令の形を準備（名前解決用ラベルを置く）
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :name");

// 2. 値を安全に紐付け（バインド）
$stmt->bindValue(':name', $user_input);

// 3. 実行
$stmt->execute();</code></pre>

        <hr>

        <h6>② 特殊文字のエスケープ処理</h6>
        <p>
            <code>'</code> を <code>\'</code> に変換するなどして、SQLの記号としての意味を打ち消します。ただし、漏れが生じやすいため、①のプレースホルダが使えない場合の補助的な手段です。
        </p>

        <hr>

        <h6>③ ORM（Object-Relational Mapper）の活用</h6>
        <p>
            LaravelのEloquentやDoctrineなどのライブラリを使うと、内部で自動的に安全な処理（プレースホルダ）が行われるため、意識せずとも高い安全性を確保できます。
        </p>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>