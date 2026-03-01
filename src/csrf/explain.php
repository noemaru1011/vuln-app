<?php include '../includes/header.php'; ?>


<h2 class="text-danger">CSRF（クロスサイト・リクエスト・フォージェリ）とは？</h2>
<p class="lead">ログイン中のユーザーに、<strong>「本人の意図しない操作（パスワード変更、投稿など）」</strong>を勝手に実行させる攻撃です。</p>

<hr>

<h3>1. なぜ攻撃が成功したのか？</h3>
<p>今回の実験では、以下の3つの条件が揃ったため攻撃が成立しました。</p>
<ul>
    <li><strong>ログイン状態:</strong> ターゲット（あなた）が <code>auth/login.php</code> でログインし、ブラウザに <code>PHPSESSID</code>（セッション情報）が残っていた。</li>
    <li><strong>Cookieの自動送信:</strong> ブラウザは、<code>trap.html</code> から <code>change_password.php</code> にリクエストを送る際、ドメインが同じであれば<strong>自動的にCookieを付与して送る</strong>という性質がある。</li>
    <li><strong>チェックの欠如:</strong> <code>change_password.php</code> 側で、「このリクエストは本当に自分のサイトのフォームから送られたものか？」を確認していなかった。</li>
</ul>



<h3>2. 攻撃のステップ（おさらい）</h3>
<ol>
    <li>ユーザーが正規のサイト（localhost:8080）にログインする。</li>
    <li>ブラウザに <code>PHPSESSID</code> が保存される。</li>
    <li>ユーザーが罠サイト（trap.html）を別タブで開く。</li>
    <li>罠サイトが JavaScript で勝手に <code>POST /auth/change_password.php</code> を送信する。</li>
    <li>ブラウザが自動で <code>PHPSESSID</code> を添えるため、サーバーは正規の依頼だと勘違いしてパスワードを書き換える。</li>
</ol>

<div class="alert alert-success mt-4">
    <h4>🛡️ どうやって防ぐ？</h4>
    <p>最も一般的な対策は <strong>CSRFトークン</strong> の導入です。</p>
    <ul>
        <li>フォームを表示する際に、推測不可能な「使い捨ての合言葉（トークン）」を発行し、セッションに保存する。</li>
        <li>送信されたトークンとセッション内のトークンが一致しない場合は、処理を拒否する。</li>
        <li>罠サイト（trap.html）はこのトークンを知ることができないため、攻撃に失敗します。</li>
    </ul>
</div>

<div class="mt-4">
    <a href="login.php" class="btn btn-outline-primary">ログイン画面に戻る</a>
    <a href="change_password.php" class="btn btn-outline-secondary">マイページへ</a>
</div>


<?php include '../includes/footer.php'; ?>