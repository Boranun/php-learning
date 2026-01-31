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
    // POSTで送られてきたデータを受け取る
    $name = $_POST['name'];
    
    // 受け取ったデータを表示
    echo "<p>こんにちは、" . $name . "さん！</p>";
    ?>
    
    <a href="form.php">戻る</a>
    
</body>
</html>