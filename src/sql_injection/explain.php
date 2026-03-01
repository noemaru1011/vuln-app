<?php include '../includes/header.php'; ?>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-5">
                <h1 class="display-6 fw-bold text-danger border-bottom pb-3 mb-4">なぜSQLインジェクションは起きるのか？</h1>
                
                <p class="lead text-secondary">
                    最大の原因は、プログラムが<strong>「開発者が書いた命令（SQL）」</strong>と<strong>「ユーザーが入力したデータ」</strong>を混ぜこぜにして処理してしまうことにあります。
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <h5 class="fw-bold"><span class="badge bg-primary me-2">1</span> 文字列連結の罠</h5>
                        <p>今回のプログラムでは、以下のようにユーザーの入力をそのままSQL文の中に「合体」させています。</p>
                        <div class="bg-dark text-light p-3 rounded font-monospace my-3 small">
                            $sql = "SELECT * FROM USER WHERE USERNAME LIKE '%" . <span class="text-warning">$name</span> . "%'";
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12 text-center">
                        <h5 class="fw-bold mb-3">攻撃が成立する瞬間のイメージ</h5>
                        
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-success h-100 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold text-success">正常な入力 (田中)</h6>
                                <p class="small text-muted">入力が単なる「名前」として扱われる</p>
                                <div class="bg-light p-2 border rounded font-monospace small">
                                    ... WHERE USERNAME LIKE '%<span class="fw-bold">田中</span>%'
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger h-100 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold text-danger">攻撃的な入力 (' OR 1=1 --)</h6>
                                <p class="small text-muted">入力が「SQL命令」として解釈される</p>
                                <div class="bg-light p-2 border rounded font-monospace small">
                                    ... WHERE USERNAME LIKE '%<span class="text-danger fw-bold">' OR 1=1 --</span>%'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mt-4">
                    <h5 class="h6 fw-bold">正しい対策：プリペアドステートメント</h5>
                    <p class="small mb-0">
                        ユーザーの入力を「命令」としてではなく、ただの「値」として扱うようにデータベースエンジンに伝えます。これにより、どんな記号が入ってきても命令が書き換わることはなくなります。
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>