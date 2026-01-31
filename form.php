<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム入力</title>
</head>
<body>
    <h1>名前を入力してください</h1>
    
    <form action="result.php" method="POST">
        <label for="name">お名前:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">送信</button>
    </form>
    
</body>
</html>