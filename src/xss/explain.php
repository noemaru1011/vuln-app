<?php
$title = "XSS（クロスサイト・スクリプティング）解説";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card mb-4 border-danger">
    <h5 class="card-header bg-danger text-white">XSSとは何か？</h5>
    <div class="card-body p-4">
        <p class="lead">
            ウェブサイトの表示内容に<strong>「悪意のあるスクリプト」を混入させる</strong>攻撃です。
        </p>
        <p>
            ユーザーのブラウザ上でスクリプトが実行されることで、Cookie（セッション情報）の盗み出しや、ページ内容の改ざん、偽の入力フォームへの誘導などが行われます。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ脆弱性が生まれるのか？</h5>
    <div class="card-body p-4">
        <p>最大の原因は、<strong>「ユーザーが入力したデータ」をそのままHTMLとして出力してしまう</strong>ことにあります。</p>
        <div class="alert alert-secondary">
            <p class="mb-1 fw-bold">ブラウザの性質：</p>
            ブラウザは <code>&lt;</code> や <code>&gt;</code> を見つけると、それを「文字」ではなく「HTMLタグ（命令）」として解釈します。対策をしないと、入力データがそのままプログラムとして実行されてしまいます。
        </div>
    </div>
</div>



<div class="card mb-4">
    <h5 class="card-header">エスケープ（無害化）の効果</h5>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger fw-bold">⚠️ そのまま出力（脆弱）</h6>
                        <p class="small text-muted">入力されたタグをそのまま表示</p>
                        <pre class="bg-light p-3 rounded small"><code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></pre>
                        <hr>
                        <p class="small fw-bold text-danger">ブラウザの挙動：</p>
                        <div class="p-2 border rounded bg-white text-center">
                            <span class="badge bg-danger">JavaScriptとして実行される</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h6 class="text-success fw-bold">✅ エスケープ後（安全）</h6>
                        <p class="small text-muted">記号を特殊な文字列に変換</p>
                        <pre class="bg-light p-3 rounded small"><code>&amp;lt;script&amp;gt;alert('XSS')&amp;lt;/script&amp;gt;</code></pre>
                        <hr>
                        <p class="small fw-bold text-success">ブラウザの挙動：</p>
                        <div class="p-2 border rounded bg-white text-center text-muted">
                            &lt;script&gt;alert('XSS')&lt;/script&gt;<br>
                            <span class="small">(単なる文字列として表示される)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：多層防御</h5>
    <div class="card-body p-4">

        <h6>① 出力時のサニタイズ（エスケープ） <span class="badge bg-primary">最重要</span></h6>
        <p>HTMLとして出力する直前に、必ず特別な意味を持つ記号を無害化します。</p>
        <pre class="bg-light p-3 rounded"><code>// PHPでの対策例
echo htmlspecialchars($input, ENT_QUOTES, 'UTF-8');</code></pre>

        <hr>

        <h6>② Cookieの保護（HttpOnly属性）</h6>
        <p>JavaScriptからCookieへのアクセスを禁止し、もしXSSが成功してもセッションIDを盗ませないようにします。</p>
        <pre class="bg-light p-3 rounded"><code>setcookie("session_id", "abc...", ["httponly" => true]);</code></pre>

        <hr>

        <h6>③ フレームワークの特性を正しく使う</h6>
        <p>ReactやVueなどは標準で自動エスケープを行いますが、一部の「危険な機能」を使う際は手動対策が必要です。</p>
        <div class="row mt-2">
            <div class="col-sm-6">
                <div class="p-2 bg-light border rounded">
                    <p class="small fw-bold mb-1 text-success">安全（自動防御）</p>
                    <code>&lt;div&gt;{userInput}&lt;/div&gt;</code>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-2 bg-light border rounded">
                    <p class="small fw-bold mb-1 text-danger">危険（例外機能）</p>
                    <code>dangerouslySetInnerHTML</code>
                </div>
            </div>
        </div>

        <hr>

        <h6>④ Content Security Policy (CSP) の導入</h6>
        <p>ブラウザに対して「このサイト以外のスクリプトは実行しないで」という指示を出し、万が一の被害を防ぎます。</p>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>