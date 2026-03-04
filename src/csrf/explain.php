<?php
$title = "CSRF（クロスサイト・リクエスト・フォージェリ）解説";
require dirname(__DIR__) . "/includes/header.php";
?>
<div class="card mb-4 border-danger">
    <h5 class="card-header bg-danger text-white">CSRFとは何か？</h5>
    <div class="card-body p-4">
        <p class="lead">
            ログイン中のユーザーに、<strong>「本人の意図しない操作」</strong>を勝手に実行させる攻撃です。
        </p>
        <p>
            ユーザーが「罠サイト」を閲覧しただけで、裏側で勝手にパスワード変更や商品の購入リクエストが送信されてしまいます。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ攻撃が成功してしまうのか？</h5>
    <div class="card-body p-4">
        <p>ブラウザの「便利な機能」が、攻撃者に悪用されることが原因です。</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>1. ログイン状態の維持</h6>
                        <p class="small mb-0">ブラウザには、ログイン情報を証明する <code>Cookie</code>（セッションID）が保存されています。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>2. Cookieの自動送信</h6>
                        <p class="small mb-0">ブラウザは、特定のサイトへリクエストを送る際、保存されているCookieを<strong>自動的に添付</strong>して送ります。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>3. 本人確認の不足</h6>
                        <p class="small mb-0">サーバー側が「Cookieさえあれば本人からのリクエストだ」と判断し、リクエストの送信元を確認していません。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">攻撃成立のステップ</h5>
    <div class="card-body p-4">
        <ol class="mb-0">
            <li class="mb-2">ユーザーが正規サイトにログインする</li>
            <li class="mb-2">ログインしたまま、別のタブで「罠サイト」を開く</li>
            <li class="mb-2">罠サイトのコードが、勝手に正規サイトへリクエストを送信</li>
            <li class="mb-2">ブラウザがユーザーのCookieを自動的に添付</li>
            <li class="mb-2">サーバーは「本人からの正当なリクエスト」と判断して処理を実行</li>
        </ol>
        
        <div class="alert alert-danger mt-3 mb-0">
            <strong>⚠️ 重要：</strong> 攻撃者はユーザーのCookieの中身を盗むわけではなく、ブラウザの「自動送信機能」を悪用しています。
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
                        <h6 class="text-danger">罠サイトのコード例</h6>
                        <pre class="bg-light p-2 rounded small"><code>&lt;form action="https://bank.com/transfer" method="POST"&gt;
  &lt;input type="hidden" name="to" value="attacker"&gt;
  &lt;input type="hidden" name="amount" value="100000"&gt;
&lt;/form&gt;
&lt;script&gt;
  document.forms[0].submit();
&lt;/script&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">何が起きるか</h6>
                        <ul class="small mb-0">
                            <li>ページを開いた瞬間に自動送信</li>
                            <li>ユーザーのCookieが自動添付</li>
                            <li>サーバーは正当なリクエストと判断</li>
                            <li>攻撃者の口座に送金が実行される</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策</h5>
    <div class="card-body p-4">
        
        <h6>① CSRFトークンの利用 <span class="badge bg-primary">最推奨</span></h6>
        <p>
            推測不可能な「ワンタイムトークン」を発行し、正規のフォームからのリクエストであることを検証します。
        </p>
        
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small text-muted">STEP 1: トークン発行</h6>
                        <pre class="small mb-0"><code>// フォーム表示時
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

// HTMLに埋め込み
&lt;input type="hidden" 
       name="csrf_token" 
       value="&lt;?= $token ?&gt;"&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small text-muted">STEP 2: トークン検証</h6>
                        <pre class="small mb-0"><code>// リクエスト受信時
$sent = $_POST['csrf_token'] ?? '';
$saved = $_SESSION['csrf_token'] ?? '';

if ($sent !== $saved) {
    die("不正なリクエスト");
}

// 使用後は削除（推奨）
unset($_SESSION['csrf_token']);</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-success mt-3">
            <h6 class="text-success">✅ なぜ防げるのか？</h6>
            <p class="small mb-0">
                罠サイトはユーザーのセッション内のトークンを知ることができないため、正しいトークンを含むリクエストを送信できません。
            </p>
        </div>

        <hr>

        <h6>② SameSite Cookie属性 <span class="badge bg-info">推奨</span></h6>
        <p>
            Cookieに <code>SameSite</code> 属性を設定し、外部サイトからのリクエスト時にCookieを送信しないようブラウザに指示します。
        </p>
        <pre class="bg-light p-3 rounded"><code>// Cookie設定時
setcookie('session_id', $value, [
    'samesite' => 'Lax',  // または 'Strict'
    'httponly' => true,
    'secure' => true
]);</code></pre>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="alert alert-light border mb-0">
                    <h6 class="small"><code>SameSite=Strict</code></h6>
                    <p class="small mb-0">外部サイトからのリクエストでは一切Cookieを送信しない（最も厳格）</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-light border mb-0">
                    <h6 class="small"><code>SameSite=Lax</code></h6>
                    <p class="small mb-0">通常のリンククリックでは送信、フォームPOSTでは送信しない（バランス型）</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <h6>💡 注意点</h6>
            <ul class="small mb-0">
                <li>モダンブラウザでは <code>Lax</code> がデフォルト</li>
                <li>古いブラウザでは未対応の場合がある</li>
                <li><strong>CSRFトークンとの併用が推奨</strong></li>
            </ul>
        </div>

        <hr>

        <h6>③ その他の補助的対策</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="small">Refererチェック</h6>
                        <p class="small mb-0">リクエスト元が自サイトかを確認（補助的、Refererは偽装可能）</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="small">再認証</h6>
                        <p class="small mb-0">重要な操作時にパスワードを再入力させる</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="small">CAPTCHA</h6>
                        <p class="small mb-0">自動送信を防ぐ（ユーザビリティとのトレードオフ）</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require dirname(__DIR__) . "/includes/footer.php"; ?>