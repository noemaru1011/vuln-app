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
                        <p class="small"><code>&lt;iframe&gt;</code> タグを使うと、自分のサイトの中に他人のサイトを自由に埋め込むことができます。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>2. 透明化（CSS opacity）</h6>
                        <p class="small">CSSの <code>opacity: 0;</code> を使うと、要素を完全に透明にできます。透明でも「当たり判定」は残ります。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>3. 重なりの制御（z-index）</h6>
                        <p class="small"><code>z-index</code> を使うと、透明な正規サイトを罠サイトの「手前」に配置し、クリックを横取りできます。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card mb-4 shadow-sm">
    <h5 class="card-header">攻撃成立のステップ（おさらい）</h5>
    <div class="card-body p-4">
        <div class="ms-3">
            <ol class="list-group list-group-numbered list-group-flush">
                <li class="list-group-item">攻撃者が、ターゲットサイトを <code>iframe</code> で読み込んだ罠サイトを作る。</li>
                <li class="list-group-item">CSSで <code>iframe</code> を完全に透明にし、罠ボタンの真上に重ねる。</li>
                <li class="list-group-item">SNSなどでユーザーを騙して、罠サイトへ誘導する。</li>
                <li class="list-group-item">ユーザーが罠サイトのボタンをクリックする。</li>
                <li class="list-group-item">実際には透明な <code>iframe</code> 内のボタンが押され、意図しない操作が実行される。</li>
            </ol>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：クリックジャッキング防御</h5>
    <div class="card-body p-4">
        <h6>① X-Frame-Options ヘッダーの設定</h6>
        <p>
            サーバー側から「このサイトを <code>iframe</code> に埋め込むことを許可するか」をブラウザに指示します。
        </p>
        <pre class="bg-light p-3 rounded"><code>
// PHPでの設定例（すべての埋め込みを拒否）
header('X-Frame-Options: DENY');

// 同じドメイン内のみ許可する場合
header('X-Frame-Options: SAMEORIGIN');
        </code></pre>

        <hr>

        <h6>② Content Security Policy (CSP) の利用</h6>
        <p>
            より柔軟で強力な対策として、<code>frame-ancestors</code> を使用します。
        </p>
        <pre class="bg-light p-3 rounded"><code>
// 自分のサイト以外への埋め込みを禁止する
header("Content-Security-Policy: frame-ancestors 'self'");
        </code></pre>

        <hr>

        <h6>③ フレームバスター（JavaScriptによる対策）</h6>
        <p>
            古いブラウザ向けに、自分がフレーム内に読み込まれたことを検知して、強制的にトップ画面へ遷移させるスクリプトです（現在は上記①②が推奨されます）。
        </p>
        <pre class="bg-light p-3 rounded"><code>
&lt;script&gt;
if (top !== self) {
    top.location = self.location;
}
&lt;/script&gt;
        </code></pre>
        <p class="text-muted small">
            ※ 根本的な解決には、サーバー側で <strong>X-Frame-Options</strong> または <strong>CSP</strong> を設定することが最も安全です。
        </p>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>