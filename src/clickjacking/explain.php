<?php include '../includes/header.php'; ?>


<div class="card shadow-sm border-0 p-4">
    <h1 class="h5 fw-bold mb-4 text-primary">クリックジャッキング解説</h1>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-danger h-100 shadow-sm">
                <div class="card-header bg-danger text-white fw-bold small">攻撃のネタばらし (Attack Mechanism)</div>
                <div class="card-body bg-light">
                    <h6 class="fw-bold small text-danger">① 透明な iframe の重畳</h6>
                    <p class="small text-muted">
                        <code>&lt;iframe&gt;</code> タグを使って、標的となるサイト（今回は掲示板）を読み込み、CSSの <code>opacity: 0</code> で完全に透明にして重ねています。
                    </p>

                    <h6 class="fw-bold small text-danger">② pointer-events の悪用</h6>
                    <p class="small text-muted">
                        前面にある「100万円ゲット」の偽UIに <code>pointer-events: none;</code> を設定することで、ユーザーのクリックを背後の透明なサイトへ「貫通」させています。
                    </p>

                    <div class="p-2 bg-dark text-warning rounded font-monospace small">
                        /* 貫通の魔法 */<br>
                        .fake-ui { pointer-events: none; }<br>
                        /* ステルスの魔法 */<br>
                        .target-iframe { opacity: 0; }
                    </div>

                    <hr>
                    <p class="small fw-bold mb-1">【この攻撃で何が起きる？】</p>
                    <ul class="small text-muted ps-3">
                        <li>意図しない SNS の「いいね」や「フォロー」</li>
                        <li>意図しない商品の購入や退会処理</li>
                        <li>今回のような「掲示板への勝手な投稿」</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-success h-100 shadow-sm">
                <div class="card-header bg-success text-white fw-bold small">防御策 (Security Defense)</div>
                <div class="card-body">
                    <h6 class="fw-bold">1. X-Frame-Options ヘッダーの利用</h6>
                    <p class="small text-muted">
                        最も一般的で強力な対策です。ブラウザに対し、「このサイトを他サイトの iframe 内に表示して良いか」を指示します。
                    </p>
                    <div class="p-3 bg-light border rounded mb-3">
                        <code class="text-success fw-bold small">header("X-Frame-Options: SAMEORIGIN");</code>
                        <p class="small text-muted mt-2 mb-0">
                            <strong>SAMEORIGIN:</strong> 同じドメイン内なら埋め込みOK。<br>
                            <strong>DENY:</strong> どこにも埋め込ませない（最強の拒否）。
                        </p>
                    </div>

                    <h6 class="fw-bold">2. Content-Security-Policy (CSP)</h6>
                    <p class="small text-muted">
                        モダンなブラウザ向けに、より細かく制御できるヘッダーです。
                    </p>
                    <div class="p-3 bg-light border rounded mb-3">
                        <code class="text-success fw-bold small">header("Content-Security-Policy: frame-ancestors 'self'");</code>
                    </div>

                    <div class="alert alert-info py-2 shadow-none border-0 mb-0">
                        <p class="small mb-0 fw-bold">💡 実際にやってみよう！</p>
                        <p class="small mb-0">
                            <code>includes/header.php</code> の先頭に上記 <code>header()</code> 関数を記述すると、先ほどの体験ページの iframe が真っ白になり、攻撃が防げることが確認できます。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="p-4 border rounded bg-light">
                <h6 class="fw-bold mb-3 small">クリックジャッキングの構造イメージ</h6>

            </div>
        </div>
    </div>
</div>


<?php include '../includes/footer.php'; ?>