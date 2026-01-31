<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果</title>
</head>
<body>
    <h1>送信結果</h1>
    
    <?php
    // POSTで送られてきたか確認
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // データを受け取る
        $name = $_POST['name'];
        
        // 1. 空白チェック（前後の空白を削除してからチェック）
        $name = trim($name);  // 前後の空白を削除
        
        if (empty($name)) {
            // 空白の場合はエラー表示
            echo "<p style='color: red;'>名前を入力してください。</p>";
        } else {
            // 2. XSS対策（HTMLタグをエスケープ）
            $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            
            // 安全に表示
            echo "<p>こんにちは、" . $name . "さん！</p>";
        }
        
    } else {
        // POSTで送られていない場合（直接アクセスされた場合）
        echo "<p style='color: red;'>不正なアクセスです。</p>";
    }
    ?>
    
    <a href="form.php">戻る</a>
    
</body>
</html>