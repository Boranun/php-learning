<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム入力</title>
</head>
<body>
    <h1>名前を入力してください</h1>
    
    <form action="result.php" method="POST" onsubmit="return validateForm()">
        <label for="name">お名前:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">送信</button>
    </form>
    
    <script>
    // JavaScriptでスペースのみをチェック
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        
        if (name === '') {
            alert('名前を入力してください');
            return false;  // 送信を中止
        }
        
        return true;  // 送信を許可
    }
    </script>
    
</body>
</html>