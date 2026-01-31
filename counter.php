<?php
// セッションを開始
session_start();

// カウンターの初期化
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}

// POSTリクエストの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // リセットボタンが押された場合
    if (isset($_POST['reset'])) {
        $_SESSION['count'] = 0;
    }
    
    // カウントアップボタンが押された場合
    if (isset($_POST['increment'])) {
        $_SESSION['count']++;
    }
    
    // 処理後、同じページにリダイレクト（GETリクエストに変換）
    header('Location: counter.php');
    exit;  // リダイレクト後は必ずexitで処理を終了
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>セッション - カウンター</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
        }
        .counter {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>セッションカウンター</h1>
    
    <div class="counter">
        <?php echo $_SESSION['count']; ?>
    </div>
    
    <form method="POST">
        <button type="submit" name="increment">+1</button>
        <button type="submit" name="reset">リセット</button>
    </form>
    
    <p>ページを更新しても数字が保持されます</p>
    
</body>
</html>