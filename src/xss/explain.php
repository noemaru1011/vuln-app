<?php include '../includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-5">
                <h1 class="display-6 fw-bold text-primary mb-4">なぜXSSは起きるのか？</h1>

                <section class="mb-5">
                    <h2 class="h4 fw-bold border-bottom pb-2">1. 根本的な原因：エスケープ漏れ</h2>
                    <p>Webブラウザにとって、HTMLタグ（<code>&lt;</code> や <code>&gt;</code>）は特別な意味を持ちます。安全なサイトは、これらを無害な文字に変換（エスケープ）します。</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>入力した文字</th>
                                    <th>そのまま出力（脆弱）</th>
                                    <th>エスケープ後（安全）</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><code>&lt;</code></td>
                                    <td><strong>タグの開始</strong>とみなされる</td>
                                    <td><code>&amp;lt;</code> (単なる文字)</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><code>&gt;</code></td>
                                    <td><strong>タグの終了</strong>とみなされる</td>
                                    <td><code>&amp;gt;</code> (単なる文字)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="h4 fw-bold border-bottom pb-2">🛡️ 多層防御：XSSを防ぐ3つの壁</h2>
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="card h-100 border-success shadow-sm">
                                <div class="card-header bg-success text-white fw-bold">1. 入力値の検証 (Validation)</div>
                                <div class="card-body">
                                    <p class="small">DBに保存する前にサーバー側でチェック。不正な形式を拒否します。</p>
                                    <pre class="bg-dark text-white p-2 rounded small"><code>// 不正な文字を検知
if (strpos($input, '&lt;script&gt;') !== false) {
    die("Invalid input");
}</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-primary shadow-sm">
                                <div class="card-header bg-primary text-white fw-bold">2. HttpOnly 属性</div>
                                <div class="card-body">
                                    <p class="small">CookieをJSから隔離します。<strong>document.cookie</strong> で盗まれるのを防ぎます。</p>
                                    <pre class="bg-dark text-white p-2 rounded small"><code>setcookie("sid", "xyz", [
    'httponly' => true,
    'secure' => true
]);</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5 bg-light p-4 rounded border-start border-4 border-success">
                    <h2 class="h5 fw-bold text-success">エンジニアがやるべきこと</h2>
                    <p class="small">出力時は必ず <code>htmlspecialchars()</code> を通しましょう。</p>
                    <pre class="bg-dark text-white p-3 rounded"><code>echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');</code></pre>
                </section>

                <div class="text-center mt-4">
                    <a href="xss.php" class="btn btn-primary px-5 shadow-sm">演習に戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>