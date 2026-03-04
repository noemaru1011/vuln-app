<?php
$title = "クリックジャッキング解説";
require dirname(__DIR__) . "/includes/header.php";
?>
<div class="card mb-4 border-danger">
    <h5 class="card-header bg-danger text-white">クリックジャッキングとは何か？</h5>
    <div class="card-body p-4">
        <p class="lead">
            透明にした「正規サイト」を「罠サイト」の上に重ね、<strong>ユーザーを騙してクリックさせる</strong>攻撃です。
        </p>
        <p>
            ユーザーは罠サイトのボタン（例：「閉じる」「再生」）を押しているつもりでも、実際にはその上に重なった透明な正規サイトのボタン（例：「退会する」「購入する」）をクリックさせられています。
        </p>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">なぜ攻撃が成功してしまうのか？</h5>
    <div class="card-body p-4">
        <p>ブラウザの「重ね合わせ」と「透明度」の機能が、攻撃者に悪用されることが原因です。</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>1. iframeによる埋め込み</h6>
                        <p class="small mb-0"><code>&lt;iframe&gt;</code> タグで他サイトを自分のページ内に埋め込める</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>2. 透明化</h6>
                        <p class="small mb-0">CSSの <code>opacity: 0</code> で完全に透明にしても、クリック可能領域は残る</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>3. 重なりの制御</h6>
                        <p class="small mb-0"><code>z-index</code> で透明な正規サイトを手前に配置し、クリックを横取り</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">攻撃成立のステップ</h5>
    <div class="card-body p-4">
        <ol class="mb-3">
            <li class="mb-2">攻撃者が、ターゲットサイトを <code>iframe</code> で読み込んだ罠サイトを作成</li>
            <li class="mb-2">CSSで <code>iframe</code> を完全に透明にし、罠ボタンの真上に配置</li>
            <li class="mb-2">SNSやメールでユーザーを罠サイトへ誘導</li>
            <li class="mb-2">ユーザーが罠サイトのボタンをクリック</li>
            <li class="mb-2">実際には透明な <code>iframe</code> 内のボタンが押され、意図しない操作が実行</li>
        </ol>

        <div class="alert alert-danger mb-0">
            <strong>⚠️ 重要：</strong> ユーザーは正規サイトにログイン済みのため、クリックした操作がそのまま実行されてしまいます。
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
                        <pre class="bg-light p-2 rounded small"><code>&lt;style&gt;
  iframe {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    z-index: 2;
  }
  .fake-button {
    position: absolute;
    top: 100px;
    left: 100px;
    z-index: 1;
  }
&lt;/style&gt;

&lt;button class="fake-button"&gt;
  動画を再生
&lt;/button&gt;
&lt;iframe src="https://bank.com/delete-account"&gt;
&lt;/iframe&gt;</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <h6 class="text-info">何が起きるか</h6>
                        <ul class="small mb-0">
                            <li>ユーザーには「動画を再生」ボタンが見える</li>
                            <li>実際にはその上に透明なiframeが重なっている</li>
                            <li>クリックすると、iframe内の「退会ボタン」が押される</li>
                            <li>ユーザーの意図しない退会処理が実行される</li>
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
        
        <h6>① X-Frame-Options ヘッダーの設定 <span class="badge bg-primary">推奨</span></h6>
        <p>
            サーバー側から「このサイトを <code>iframe</code> に埋め込むことを許可するか」をブラウザに指示します。
        </p>
        
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small text-muted">DENY（完全拒否）</h6>
                        <pre class="small mb-2"><code>header('X-Frame-Options: DENY');</code></pre>
                        <p class="small mb-0">すべての埋め込みを拒否（最も安全）</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small text-muted">SAMEORIGIN（同一オリジンのみ）</h6>
                        <pre class="small mb-2"><code>header('X-Frame-Options: SAMEORIGIN');</code></pre>
                        <p class="small mb-0">自サイト内のみ埋め込み許可</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-success mt-3">
            <h6 class="text-success">✅ どう防げるのか？</h6>
            <p class="small mb-0">
                ブラウザがこのヘッダーを検出すると、iframe内での表示を拒否します。攻撃者は透明な正規サイトを重ねることができなくなります。
            </p>
        </div>

        <hr>

        <h6>② Content Security Policy (CSP) <span class="badge bg-info">最推奨</span></h6>
        <p>
            より柔軟で強力な対策として、<code>frame-ancestors</code> ディレクティブを使用します。
        </p>
        <pre class="bg-light p-3 rounded"><code>// 自分のサイト以外への埋め込みを禁止
header("Content-Security-Policy: frame-ancestors 'self'");

// 完全に禁止
header("Content-Security-Policy: frame-ancestors 'none'");

// 特定のドメインのみ許可
header("Content-Security-Policy: frame-ancestors 'self' https://trusted.com");</code></pre>

        <div class="alert alert-info mt-3">
            <h6>💡 CSPの利点</h6>
            <ul class="small mb-0">
                <li>X-Frame-Optionsより柔軟（複数ドメイン指定可能）</li>
                <li>モダンブラウザで広くサポート</li>
                <li>他のセキュリティ設定も一緒に管理可能</li>
            </ul>
        </div>

        <hr>

        <h6>③ フレームバスター（JavaScript） <span class="badge bg-secondary">非推奨</span></h6>
        <p>
            自分がフレーム内に読み込まれたことを検知して、強制的にトップ画面へ遷移させる方法です。
        </p>
        <pre class="bg-light p-3 rounded"><code>&lt;script&gt;
if (top !== self) {
    top.location = self.location;
}
&lt;/script&gt;</code></pre>

        <div class="alert alert-warning mt-3">
            <h6>⚠️ JavaScriptの限界</h6>
            <ul class="small mb-0">
                <li>JavaScriptが無効化されていると機能しない</li>
                <li><code>sandbox</code> 属性で無効化可能</li>
                <li>サーバー側のヘッダー設定が確実</li>
            </ul>
        </div>

        <hr>

        <h6>④ 補助的な対策</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small">重要操作の確認</h6>
                        <p class="small mb-0">退会や購入など重要な操作には確認画面を挟む</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="small">CAPTCHA</h6>
                        <p class="small mb-0">自動化された攻撃を防ぐ（補助的）</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>