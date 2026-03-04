<?php
$title = "オープンリダイレクト・HTTPヘッダインジェクション解説";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="card mb-4 border-danger">
    <h5 class="card-header bg-danger text-white">リダイレクト関連の脆弱性とは何か？</h5>
    <div class="card-body p-4">
        <p class="lead">
            Webアプリケーションのリダイレクト機能を悪用して、<strong>「ユーザーを攻撃者の用意した悪意のあるサイトへ誘導」</strong>したり、<strong>「HTTPヘッダーに不正な値を注入」</strong>する攻撃です。
        </p>
        <p>
            フィッシング詐欺の踏み台、セッション乗っ取り、XSS攻撃など、様々な二次被害を引き起こす危険な脆弱性です。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ脆弱性が生まれるのか？</h5>
    <div class="card-body p-4">
        <p>最大の原因は、<strong>「リダイレクト先のURL」や「HTTPヘッダーの値」をユーザー入力から取得し、検証せずにそのまま使用してしまう</strong>ことにあります。</p>
        
        <div class="alert alert-secondary">
            <h6>危険なコード例</h6>
            <pre class="mb-0"><code>// リダイレクト先を検証なしで使用
$redirect_url = $_GET['url'] ?? 'default.php';
header("HX-Redirect: " . $redirect_url);  // 危険！</code></pre>
        </div>
        
        <p class="small text-muted mt-2">
            ※ ユーザーが入力した外部URL（<code>https://evil.com</code>）や改行文字（<code>%0d%0a</code>）が、そのままヘッダーに反映されてしまいます。
        </p>
    </div>
</div>

<div class="card mb-4 border-warning">
    <h5 class="card-header bg-warning">脆弱性① オープンリダイレクト</h5>
    <div class="card-body p-4">
        <h6 class="text-warning">⚠️ どんな攻撃か？</h6>
        <p>
            正規サイトのリダイレクト機能を悪用して、ユーザーを攻撃者が用意したフィッシングサイトなどへ誘導する攻撃です。
        </p>
        
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">✅ 正常な使用例</h6>
                        <p class="small">リダイレクト先は同一サイト内のページ。</p>
                        <pre class="bg-light p-2 rounded small"><code>https://example.com/login.php
?url=<span class="text-success">change_password.php</span></code></pre>
                        <p class="small text-success fw-bold mb-2">結果：パスワード変更画面へ遷移</p>
                        <div class="alert alert-success small mb-0">
                            <strong>HTTPヘッダー：</strong><br>
                            <code>HX-Redirect: change_password.php</code>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">⚠️ 攻撃例</h6>
                        <p class="small">リダイレクト先を外部の悪意あるサイトに変更。</p>
                        <pre class="bg-light p-2 rounded small"><code>https://example.com/login.php
?url=<span class="text-danger">https://evil.com/phishing</span></code></pre>
                        <p class="small text-danger fw-bold mb-2">結果：攻撃者のサイトへ誘導される</p>
                        <div class="alert alert-danger small mb-0">
                            <strong>HTTPヘッダー：</strong><br>
                            <code>HX-Redirect: https://evil.com/phishing</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-danger mt-4">
            <h6>🎯 攻撃シナリオ例</h6>
            <ol class="mb-0 small">
                <li>攻撃者が罠URLを作成：<code>https://正規サイト.com/login?url=https://偽サイト.com</code></li>
                <li>メールやSNSでユーザーに送信（URLは正規サイトなので信頼される）</li>
                <li>ユーザーがログインすると、自動的に偽サイトへリダイレクト</li>
                <li>偽サイトで「セッション切れ」などと表示し、再度ログイン情報を入力させる</li>
                <li>認証情報が盗まれる</li>
            </ol>
        </div>
    </div>
</div>

<div class="card mb-4 border-warning">
    <h5 class="card-header bg-warning">脆弱性② HTTPヘッダインジェクション</h5>
    <div class="card-body p-4">
        <h6 class="text-warning">⚠️ どんな攻撃か？</h6>
        <p>
            HTTPヘッダーに改行文字（<code>\r\n</code>、URLエンコードで<code>%0d%0a</code>）を注入することで、任意のHTTPヘッダーやHTMLコンテンツを挿入する攻撃です。
        </p>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">✅ 正常なHTTPレスポンス</h6>
                        <pre class="bg-light p-2 rounded small"><code>HTTP/1.1 200 OK
Content-Type: text/html
HX-Redirect: <span class="text-success">change_password.php</span>

&lt;p&gt;ログイン成功！&lt;/p&gt;</code></pre>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">⚠️ 攻撃後のHTTPレスポンス</h6>
                        <pre class="bg-light p-2 rounded small"><code>HTTP/1.1 200 OK
Content-Type: text/html
HX-Redirect: test.php<span class="text-danger">
Set-Cookie: admin=true
X-Evil-Header: injected</span>

&lt;p&gt;ログイン成功！&lt;/p&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h6>📝 攻撃用URL例</h6>
            <pre class="mb-2"><code>https://example.com/login?url=test.php<span class="text-danger">%0d%0a</span>Set-Cookie:%20admin=true</code></pre>
            <p class="small mb-0">
                <code>%0d%0a</code> = 改行文字（CRLF）<br>
                これにより、新しいHTTPヘッダー行を追加できてしまう
            </p>
        </div>

        <div class="alert alert-danger mt-3">
            <h6>🎯 攻撃の種類</h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>① Cookie注入</strong>
                    <pre class="small"><code>?url=test.php%0d%0a
Set-Cookie:%20admin=true</code></pre>
                    <p class="small">→ 権限昇格の可能性</p>
                </div>
                <div class="col-md-6">
                    <strong>② XSS攻撃</strong>
                    <pre class="small"><code>?url=test.php%0d%0a%0d%0a
&lt;script&gt;alert(1)&lt;/script&gt;</code></pre>
                    <p class="small">→ JavaScriptコードの実行</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">攻撃成立の比較：検証の有無による違い</h5>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-danger">
                    <div class="card-body">
                        <h6 class="text-danger">❌ 脆弱なコード</h6>
                        <pre class="bg-light p-2 rounded small"><code class="text-danger">// 検証なし！
url = request.getParameter("url")
response.setHeader("Location", url)</code></pre>
                        <ul class="small mt-3 mb-0">
                            <li>外部URLへのリダイレクトが可能</li>
                            <li>改行文字の注入が可能</li>
                            <li>任意のヘッダー追加が可能</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <h6 class="text-success">✅ 安全なコード</h6>
                        <pre class="bg-light p-2 rounded small"><code class="text-success">// ホワイトリスト検証
allowed = ["change_password", "dashboard"]
url = request.getParameter("url")

if url not in allowed:
    url = "dashboard"
    
response.setHeader("Location", url)</code></pre>
                        <ul class="small mt-3 mb-0">
                            <li>許可されたURLのみ使用</li>
                            <li>外部サイトへの遷移を防止</li>
                            <li>ヘッダインジェクションも防止</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：安全なリダイレクト実装の考え方</h5>
    <div class="card-body p-4">
        
        <h6>① ホワイトリスト方式の採用 <span class="badge bg-primary">最推奨</span></h6>
        <p>
            許可するリダイレクト先を事前に定義し、それ以外は受け付けない方式です。最も確実で安全な方法です。
        </p>
        <div class="alert alert-light border">
            <strong>考え方：</strong>
            <ul class="mb-0">
                <li>許可するURL（またはパス）のリストを作成</li>
                <li>ユーザー入力がリストに含まれているかチェック</li>
                <li>含まれていない場合はデフォルトのURLを使用</li>
            </ul>
        </div>
        <pre class="bg-light p-3 rounded"><code>// 疑似コード
allowed_urls = ["change_password", "dashboard", "profile"]
redirect_url = get_user_input("url") or "dashboard"

if redirect_url not in allowed_urls:
    redirect_url = "dashboard"  // デフォルトにフォールバック

redirect(redirect_url)</code></pre>

        <hr>

        <h6>② 相対パスのみ許可 <span class="badge bg-info">推奨</span></h6>
        <p>
            外部URLを完全に排除し、同一サイト内のページへのリダイレクトのみを許可します。
        </p>
        <div class="alert alert-light border">
            <strong>考え方：</strong>
            <ul class="mb-0">
                <li>プロトコル（<code>http://</code>、<code>https://</code>）を含むURLを拒否</li>
                <li>スラッシュ（<code>/</code>）で始まる相対パスのみ許可</li>
                <li>改行文字（<code>\r</code>、<code>\n</code>）を除去</li>
            </ul>
        </div>
        <pre class="bg-light p-3 rounded"><code>// 疑似コード
redirect_url = get_user_input("url") or "dashboard"

// プロトコルを含むURLを拒否
if redirect_url contains "://" :
    redirect_url = "dashboard"

// 改行文字を除去（ヘッダインジェクション対策）
redirect_url = remove_characters(redirect_url, ["\r", "\n"])

redirect(redirect_url)</code></pre>

        <hr>

        <h6>③ URLパース＋ドメイン検証</h6>
        <p>
            URLを構成要素に分解し、ドメインが自サイトと一致するかを検証します。
        </p>
        <div class="alert alert-light border">
            <strong>考え方：</strong>
            <ul class="mb-0">
                <li>URLをパース（解析）してドメイン部分を取得</li>
                <li>ドメインが自サイトのドメインと一致するかチェック</li>
                <li>一致しない場合はデフォルトURLを使用</li>
            </ul>
        </div>
        <pre class="bg-light p-3 rounded"><code>// 疑似コード
redirect_url = get_user_input("url") or "dashboard"
parsed = parse_url(redirect_url)

// ドメインが指定されている場合は自サイトかチェック
if parsed.host exists and parsed.host != current_site_host:
    redirect_url = "dashboard"

// 改行文字を除去
redirect_url = remove_characters(redirect_url, ["\r", "\n"])

redirect(redirect_url)</code></pre>

        <hr>

        <h6>④ 間接参照の利用</h6>
        <p>
            URLを直接受け取らず、IDやキーを受け取り、サーバー側でマッピングする方法です。
        </p>
        <div class="alert alert-light border">
            <strong>考え方：</strong>
            <ul class="mb-0">
                <li>URLの代わりにID（例：<code>?dest=1</code>）を使用</li>
                <li>サーバー側でIDとURLの対応表を管理</li>
                <li>ユーザーは直接URLを指定できない</li>
            </ul>
        </div>
        <pre class="bg-light p-3 rounded"><code>// 疑似コード
url_mapping = {
    "1": "change_password",
    "2": "dashboard",
    "3": "profile"
}

dest_id = get_user_input("dest") or "2"
redirect_url = url_mapping.get(dest_id, "dashboard")

redirect(redirect_url)</code></pre>

        <div class="alert alert-warning mt-4">
            <h6>⚠️ やってはいけないこと</h6>
            <ul class="mb-0">
                <li><strong>ブラックリスト方式：</strong> 特定のドメイン（<code>evil.com</code>など）だけを弾く → 回避可能</li>
                <li><strong>不完全な検証：</strong> <code>javascript:</code>プロトコルのチェック漏れ</li>
                <li><strong>改行文字の見逃し：</strong> <code>\r\n</code>のサニタイズ忘れ</li>
                <li><strong>正規表現の不備：</strong> <code>https://evil.com@example.com</code>のような回避手法</li>
            </ul>
        </div>

        <div class="alert alert-info mt-4">
            <h6>💡 防御の基本原則</h6>
            <ol class="mb-0">
                <li><strong>信頼できる入力はない：</strong> すべてのユーザー入力を検証する</li>
                <li><strong>ホワイトリストが基本：</strong> 許可するものを明示的に定義</li>
                <li><strong>多層防御：</strong> 複数の検証を組み合わせる</li>
                <li><strong>デフォルト値を用意：</strong> 不正な入力時の安全な遷移先を確保</li>
            </ol>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>