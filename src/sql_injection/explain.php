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
        
        <div class="alert alert-info mt-4">
            <h6>💡 何が起きているのか？</h6>
            <p class="small mb-2">攻撃入力により、SQL文の意味が完全に変わってしまいます：</p>
            <ul class="small mb-0">
                <li><code>username = ''</code> → 常に偽（該当なし）</li>
                <li><code>OR '1'='1'</code> → 常に真（すべてに該当）</li>
                <li>結果：WHERE条件が実質的に無効化され、全データが取得される</li>
            </ul>
        </div>
    </div>
</div>

<div class="card mb-4 border-warning">
    <h5 class="card-header bg-warning">様々な攻撃パターン</h5>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-danger">① データ窃取</h6>
                        <pre class="small bg-light p-2 rounded"><code>' UNION SELECT password FROM users--</code></pre>
                        <p class="small mb-0">他のテーブルのデータを結合して取得</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-danger">② 認証回避</h6>
                        <pre class="small bg-light p-2 rounded"><code>admin'--</code></pre>
                        <p class="small mb-0">パスワードチェックをコメントアウト</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-danger">③ データ改ざん</h6>
                        <pre class="small bg-light p-2 rounded"><code>'; UPDATE users SET role='admin'--</code></pre>
                        <p class="small mb-0">不正にデータを書き換え</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-danger">④ データ削除</h6>
                        <pre class="small bg-light p-2 rounded"><code>'; DROP TABLE users--</code></pre>
                        <p class="small mb-0">テーブルごと削除（最悪のケース）</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：安全なデータベース操作</h5>
    <div class="card-body p-4">
        
        <h6>① プレースホルダ（パラメータ化クエリ）の利用 <span class="badge bg-primary">最推奨</span></h6>
        <p>
            SQLの「構造」を先に確定させ、値を後から安全に流し込む方法です。データベース側で「ここから先はただのデータだ」と厳格に区別されるため、<strong>SQLインジェクションに対して極めて効果的</strong>です。
        </p>
        <pre class="bg-light p-3 rounded"><code>// 1. 命令の形を準備（プレースホルダを置く）
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :name");
// 2. 値を安全に紐付け（バインド）
$stmt->bindValue(':name', $user_input);
// 3. 実行
$stmt->execute();</code></pre>
        
        <div class="alert alert-success mt-3">
            <h6 class="text-success">✅ なぜ安全なのか？</h6>
            <ul class="small mb-0">
                <li>SQL文の構造が事前に確定し、後から変更できない</li>
                <li>ユーザー入力は「値」としてのみ扱われ、「命令」にはならない</li>
                <li><code>'</code> などの特殊文字も自動的に適切に処理される</li>
            </ul>
        </div>

        <div class="alert alert-warning mt-3">
            <h6>⚠️ プレースホルダの限界</h6>
            <p class="small mb-2">以下の場合はプレースホルダが使用できません：</p>
            <ul class="small mb-0">
                <li><strong>テーブル名・カラム名：</strong> <code>SELECT * FROM :table</code> は不可</li>
                <li><strong>ORDER BY句：</strong> 一部のデータベースでは制限あり</li>
                <li><strong>IN句の可変長リスト：</strong> 工夫が必要</li>
            </ul>
            <p class="small mb-0 mt-2">→ これらの場合はホワイトリスト検証と組み合わせる</p>
        </div>

        <hr>

        <h6>② ホワイトリスト検証</h6>
        <p>
            プレースホルダが使えない箇所（テーブル名、カラム名など）では、<strong>許可する値のリスト</strong>を事前に定義し、それ以外を拒否します。
        </p>
        <pre class="bg-light p-3 rounded"><code>// 許可するカラム名のリスト
$allowed_columns = ['name', 'email', 'created_at'];
$sort_column = $user_input;

// リストに含まれているかチェック
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'created_at'; // デフォルト値
}

// 安全に使用
$sql = "SELECT * FROM users ORDER BY " . $sort_column;</code></pre>

        <div class="alert alert-info mt-3">
            <h6>💡 ホワイトリストの原則</h6>
            <ul class="small mb-0">
                <li>「許可するもの」を明示的に定義（ブラックリストは回避可能）</li>
                <li>想定外の値は全て拒否</li>
                <li>デフォルト値を必ず用意</li>
            </ul>
        </div>

        <hr>

        <h6>③ エスケープ処理（補助的手段）</h6>
        <p>
            特殊文字をエスケープしてSQLの記号としての意味を打ち消します。ただし、<strong>エスケープ漏れや文字エンコーディングの問題</strong>が生じやすいため、プレースホルダが使えない場合の補助的な手段です。
        </p>
        <pre class="bg-light p-3 rounded"><code>// データベース固有のエスケープ関数を使用
$escaped = $pdo->quote($user_input);
$sql = "SELECT * FROM users WHERE username = " . $escaped;</code></pre>

        <div class="alert alert-danger mt-3">
            <h6>⚠️ エスケープの問題点</h6>
            <ul class="small mb-0">
                <li>エスケープすべき文字はデータベースにより異なる</li>
                <li>文字エンコーディング（UTF-8、Shift_JISなど）により挙動が変わる</li>
                <li>実装ミスが起きやすい</li>
                <li><strong>可能な限りプレースホルダを使用すべき</strong></li>
            </ul>
        </div>

        <hr>

        <h6>④ ORM（Object-Relational Mapper）の活用</h6>
        <p>
            LaravelのEloquent、DoctrineなどのORMライブラリを使うと、内部で自動的に安全な処理（プレースホルダ）が行われます。
        </p>
        <pre class="bg-light p-3 rounded"><code>// Laravel Eloquent の例
$user = User::where('username', $user_input)->first();

// 内部的にプレースホルダが使用される</code></pre>

        <div class="alert alert-warning mt-3">
            <h6>⚠️ ORMでも注意が必要</h6>
            <p class="small mb-2">ORMでも生のSQLを書く機能を使うと脆弱性が生まれます：</p>
            <pre class="small bg-light p-2 rounded mb-2"><code>// 危険な例
DB::raw("SELECT * FROM users WHERE name = '" . $input . "'");</code></pre>
            <p class="small mb-0">→ ORMの安全な機能を正しく使うことが重要</p>
        </div>

        <hr>

        <h6>⑤ 入力値の検証（バリデーション）</h6>
        <p>
            想定外の値を早期に弾くことで、攻撃の成功率を下げます。ただし、これ単体では不十分で、<strong>プレースホルダと併用</strong>することが重要です。
        </p>
        <pre class="bg-light p-3 rounded"><code>// 例：ユーザーIDは数値のみ
if (!is_numeric($user_id)) {
    throw new Exception("Invalid user ID");
}

// 例：メールアドレスの形式チェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email format");
}</code></pre>

        <div class="alert alert-info mt-3">
            <h6>💡 バリデーションの位置づけ</h6>
            <ul class="small mb-0">
                <li><strong>第一の防御線：</strong> 明らかに不正な入力を早期に排除</li>
                <li><strong>ユーザビリティ向上：</strong> 適切なエラーメッセージを表示</li>
                <li><strong>根本対策ではない：</strong> プレースホルダなどと必ず併用</li>
            </ul>
        </div>

        <hr>

        <h6>⚠️ 対策にならないもの</h6>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li><strong>ブラックリスト方式：</strong> 危険な文字列（<code>SELECT</code>、<code>OR</code>など）を除外 → 大文字小文字変換、エンコーディング、コメントなどで回避可能</li>
                <li><strong>入力値の検証のみ：</strong> 複雑な攻撃パターンをすべて防ぐのは困難</li>
                <li><strong>WAF（Web Application Firewall）のみ：</strong> 補助的な防御であり、根本対策ではない</li>
                <li><strong>文字列置換：</strong> <code>str_replace("'", "", $input)</code> → 不完全で回避可能</li>
            </ul>
        </div>

        <hr>

        <div class="alert alert-success">
            <h6 class="text-success">✅ 対策の優先順位まとめ</h6>
            <ol class="mb-0">
                <li><strong>プレースホルダを必ず使用</strong>（最優先）</li>
                <li>プレースホルダが使えない箇所は<strong>ホワイトリスト検証</strong></li>
                <li><strong>入力値の検証</strong>を併用（補助的）</li>
                <li>可能であれば<strong>ORMを活用</strong>（正しく使う）</li>
                <li>エスケープは最後の手段（非推奨）</li>
            </ol>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>