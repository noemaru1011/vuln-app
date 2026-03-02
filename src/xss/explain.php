<?php
$title = "XSS解説";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card mb-4">
    <h5 class="card-header">なぜXSSは起きるのか？</h5>
    <div class="card-body p-4">
        <p>
            最大の原因は、
            「ユーザーが入力したデータ」を
            HTMLとしてそのまま出力してしまうことにあります。
        </p>
        <p>
            ブラウザは <code>&lt;</code> や <code>&gt;</code> を
            タグとして解釈します。
            対策をしなければ、入力は「命令」になります。
        </p>
    </div>
</div>


<div class="card mb-4">
    <h5 class="card-header">エスケープの重要性</h5>
    <div class="card-body p-4">

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>そのまま出力（脆弱）</h6>
                        <pre class="bg-light p-3 rounded"><code>
&lt;script&gt;alert('XSS')&lt;/script&gt;
                        </code></pre>
                        <p>→ JavaScriptとして実行される</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>エスケープ後（安全）</h6>
                        <pre class="bg-light p-3 rounded"><code>
&amp;lt;script&amp;gt;alert('XSS')&amp;lt;/script&amp;gt;
                        </code></pre>
                        <p>→ 単なる文字列として表示される</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


<div class="card mb-4">
    <h5 class="card-header">正しい対策</h5>
    <div class="card-body p-4">

        <h6>① 出力時エスケープ</h6>
        <p>
            HTMLとして出力する前に必ずエスケープします。
            これが最も重要です。
        </p>

        <pre class="bg-light p-3 rounded"><code>
echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');
        </code></pre>

        <hr>

        <h6>② 入力値検証</h6>
        <p>
            不正な形式の入力をサーバー側で拒否します。
            ただし、これ単体では防御になりません。
        </p>

        <pre class="bg-light p-3 rounded"><code>
if (strpos($input, '&lt;script&gt;') !== false) {
    die("Invalid input");
}
        </code></pre>

        <hr>

        <h6>③ HttpOnly属性</h6>
        <p>
            CookieをJavaScriptから隔離します。
            セッション窃取のリスクを下げます。
        </p>

        <pre class="bg-light p-3 rounded"><code>
setcookie("sid", "xyz", [
  'httponly' => true,
  'secure' => true
]);
        </code></pre>

        <hr>

        <h6>④ フレームワークの自動エスケープを理解する</h6>
        <p>
            Reactなどのフレームワークは、
            通常の記述であれば自動でエスケープ処理を行います。
            そのため、基本的な使い方ではXSSは発生しにくくなっています。
        </p>

        <p class="mb-2">通常（安全な例）</p>
        <pre class="bg-light p-3 rounded"><code>
<div>{userInput}</div>
</code></pre>
        <p>
            → 出力時に自動でエスケープされる
        </p>

        <p class="mt-3 mb-2">例外機能の使用（注意が必要）</p>
        <pre class="bg-light p-3 rounded"><code>
&lt;div dangerouslySetInnerHTML={{ __html: userInput }} /&gt;
</code></pre>
        <p>
            → エスケープされない
            → ユーザー入力をそのまま渡すとXSSが成立する可能性がある
        </p>

        <p class="mt-3">
            フレームワークを使っていても、
            仕組みを理解せずに例外機能を使うと脆弱になります。
            「自動で守られている」前提で設計しないことが重要です。
        </p>

    </div>
</div>


<?php require dirname(__DIR__) . "/includes/footer.php"; ?>