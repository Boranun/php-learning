<?php
// データベース接続
require_once 'db.php';

// タスク追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $task = trim($_POST['task']);
    
    if (!empty($task)) {
        // XSS対策
        $task = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
        
        // データベースに挿入（INSERT）
        $stmt = $pdo->prepare("INSERT INTO todos (task) VALUES (:task)");
        $stmt->bindParam(':task', $task);
        $stmt->execute();
        
        // リダイレクト（PRGパターン）
        header('Location: todo.php');
        exit;
    }
}

// タスク一覧を取得（SELECT）
$stmt = $pdo->query("SELECT * FROM todos ORDER BY created_at DESC");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todoアプリ</title>
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
        .add-form {
            margin-bottom: 30px;
        }
        .add-form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
        }
        .add-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .add-form button:hover {
            background-color: #45a049;
        }
        .todo-list {
            list-style: none;
            padding: 0;
        }
        .todo-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .todo-item .task {
            flex: 1;
        }
        .todo-item .date {
            font-size: 12px;
            color: #999;
            margin-right: 15px;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 14px;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .edit-btn {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        .edit-btn:hover {
            background-color: #0b7dda;
        }
        .empty-message {
            text-align: center;
            color: #999;
            padding: 40px;
        }
    </style>
</head>
<body>
    <h1>📝 Todoアプリ</h1>
    
    <!-- タスク追加フォーム -->
    <div class="add-form">
        <form method="POST">
            <input type="text" name="task" placeholder="新しいタスクを入力..." required>
            <button type="submit" name="add">追加</button>
        </form>
    </div>
    
    <!-- タスク一覧 -->
    <h2>タスク一覧（<?php echo count($todos); ?>件）</h2>
    
    <?php if (empty($todos)): ?>
        <div class="empty-message">
            <p>タスクがありません。<br>新しいタスクを追加してください。</p>
        </div>
    <?php else: ?>
        <ul class="todo-list">
            <?php foreach ($todos as $todo): ?>
                <li class="todo-item">
                    <div class="task"><?php echo $todo['task']; ?></div>
                    <div class="date"><?php echo $todo['created_at']; ?></div>
                    <!-- 編集ボタン（新規追加） -->
                    <a href="edit_todo.php?id=<?php echo $todo['id']; ?>" class="edit-btn">編集</a>
                    <form method="POST" action="delete_todo.php" style="margin: 0;">
                        <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
</body>
</html>