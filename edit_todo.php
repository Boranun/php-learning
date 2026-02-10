<?php
// データベース接続
require_once 'db.php';

// GETパラメータでidを受け取る
if (!isset($_GET['id'])) {
    die("不正なアクセスです。");
}

$id = $_GET['id'];

// 編集対象のタスクを取得
$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

// タスクが存在しない場合
if (!$todo) {
    die("タスクが見つかりません。");
}

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = trim($_POST['task']);
    
    if (!empty($task)) {
        // XSS対策
        $task = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
        
        // 優先度を受け取る
        $priority = $_POST['priority'];
        // データベースを更新（UPDATE）
        $stmt = $pdo->prepare("UPDATE todos SET task = :task, priority = :priority WHERE id = :id");
        $stmt->bindParam(':task', $task);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // todo.phpにリダイレクト
        header('Location: todo.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク編集</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .edit-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .edit-form input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        .edit-form button {
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
            cursor: pointer;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
        .cancel-btn {
            background-color: #999;
            color: white;
            border: none;
            text-decoration: none;
            display: inline-block;
            padding: 10px 20px;
        }
        .cancel-btn:hover {
            background-color: #777;
        }
        .edit-form label {
            display: block;
            margin-bottom: 5px;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
        .edit-form select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>📝 タスク編集</h1>
    
    <div class="edit-form">
        <form method="POST">
            <label for="task">タスク内容:</label>
            <input type="text" id="task" name="task" value="<?php echo htmlspecialchars($todo['task'], ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="priority">優先度:</label>
            <select id="priority" name="priority">
                <option value="high" <?php echo $todo['priority'] === 'high' ? 'selected' : ''; ?>>🔴 高</option>
                <option value="medium" <?php echo $todo['priority'] === 'medium' ? 'selected' : ''; ?>>🟡 中</option>
                <option value="low" <?php echo $todo['priority'] === 'low' ? 'selected' : ''; ?>>🟢 低</option>
            </select>
            
            <button type="submit" class="update-btn">更新</button>
            <a href="todo.php" class="cancel-btn">キャンセル</a>
        </form>
    </div>
    
</body>
</html>