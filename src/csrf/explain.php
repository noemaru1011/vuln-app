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
                        <p class="small">ブラウザには、ログイン情報を証明する <code>PHPSESSID</code>（Cookie）が保存されています。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>2. Cookieの自動送信</h6>
                        <p class="small">ブラウザは、特定のサイトへリクエストを送る際、保存されているCookieを<strong>自動的に添付</strong>して送ります。</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-light bg-light">
                    <div class="card-body">
                        <h6>3. 本人確認の不足</h6>
                        <p class="small">サーバー側が「Cookieさえあれば本人からのリクエストだ」と過信し、送信元のページを確認していません。</p>
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
                <li class="list-group-item">あなたが正規サイトにログインする。</li>
                <li class="list-group-item">ログインしたまま、別のタブで「罠サイト」を開く。</li>
                <li class="list-group-item">罠サイトのJavaScriptが、勝手に正規サイトへリクエストを飛ばす。</li>
                <li class="list-group-item">ブラウザがあなたのCookieを勝手に添えて送る。</li>
                <li class="list-group-item">サーバーは「本人からの依頼だ」と信じて処理を実行する。</li>
            </ol>
        </div>
    </div>
</div>

<div class="card mb-5 border-success">
    <h5 class="card-header bg-success text-white">正しい対策：CSRFトークン</h5>
    <div class="card-body p-4">
        <h6>① トークンの発行と埋め込み</h6>
        <p>
            フォームを表示する際、推測不可能な「合言葉（トークン）」を発行し、セッションとフォームの両方にセットします。
        </p>
        <pre class="bg-light p-3 rounded"><code>
// フォーム生成時
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// HTMLに隠しフィールドとして埋め込む
&lt;input type="hidden" name="csrf_token" value="&lt;?php echo $_SESSION['csrf_token']; ?&gt;"&gt;
        </code></pre>

        <hr>

        <h6>② 送信時のチェック</h6>
        <p>
            リクエストを受けた際に、送られてきたトークンがセッションのものと一致するか検証します。
        </p>
        <pre class="bg-light p-3 rounded"><code>
// 受信時
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("不正なリクエストです。");
}
        </code></pre>
        <p class="text-muted small">
            ※ 罠サイトはあなたのセッション内のトークンを知ることができないため、正しいリクエストを送ることができなくなります。
        </p>

        <hr>

        <h6>③ その他の対策（SameSite属性）</h6>
        <p>
            Cookieに <code>SameSite=Lax</code>（または <code>Strict</code>）を設定することで、外部サイトからのリクエスト時にCookieを送信しないようブラウザに指示できます。現在のモダンブラウザではこれが標準になりつつあります。
        </p>
    </div>
</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>