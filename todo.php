<?php

// データベース接続
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !isset($_POST['add'])) {
    $id = $_POST['id'];
    
    // 現在の状態を取得
    $stmt = $pdo->prepare("SELECT is_completed FROM todos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 状態を反転（0→1、1→0）
    $new_status = $todo['is_completed'] ? 0 : 1;
    
    // データベースを更新
    $stmt = $pdo->prepare("UPDATE todos SET is_completed = :status WHERE id = :id");
    $stmt->bindParam(':status', $new_status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // リダイレクト
    header('Location: todo.php');
    exit;
}

// タスク追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $task = trim($_POST['task']);
    $priority = $_POST['priority']; 
    
    if (!empty($task)) {
        // XSS対策
        $task = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
        
        // データベースに挿入（INSERT）← priorityを追加
        $stmt = $pdo->prepare("INSERT INTO todos (task, priority) VALUES (:task, :priority)");
        $stmt->bindParam(':task', $task);
        $stmt->bindParam(':priority', $priority);  // ← 追加
        $stmt->execute();
        
        // リダイレクト（PRGパターン）
        header('Location: todo.php');
        exit;
    }
}

// 検索キーワードを取得
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// タスク一覧を取得（SELECT）
$show_completed = isset($_GET['show']) && $_GET['show'] === 'completed';

if ($search !== '') {
    // 検索あり
    if ($show_completed) {
        $stmt = $pdo->prepare("SELECT * FROM todos WHERE task LIKE :search ORDER BY created_at DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM todos WHERE task LIKE :search AND is_completed = 0 ORDER BY created_at DESC");
    }
    $searchParam = '%' . $search . '%';
    $stmt->bindParam(':search', $searchParam);
    $stmt->execute();
} else {
    // 検索なし
    if ($show_completed) {
        $stmt = $pdo->query("SELECT * FROM todos ORDER BY created_at DESC");
    } else {
        $stmt = $pdo->query("SELECT * FROM todos WHERE is_completed = 0 ORDER BY created_at DESC");
    }
}

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
            width: 55%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
        }
        .add-form select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            margin-left: 5px;
            cursor: pointer;
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
        .todo-item.completed {
            background-color: #f0f0f0;
            border-left-color: #999;
        }
        .todo-item.completed .task {
            text-decoration: line-through;
            color: #999;
        }
        .checkbox {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
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
        .filter-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .filter-btn:hover {
            background-color: #0b7dda;
        }
        .priority-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .priority-high {
            background-color: #ffebee;
            color: #c62828;
            border-left-color: #c62828 !important;
        }
        .priority-medium {
            background-color: #fff3e0;
            color: #ef6c00;
            border-left-color: #ef6c00 !important;
        }
        .priority-low {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left-color: #2e7d32 !important;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
        }
        .search-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #607D8B;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 5px;
        }
        .search-btn:hover {
            background-color: #455A64;
        }
        .clear-btn {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #999;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-left: 5px;
        }
        .clear-btn:hover {
            background-color: #777;
        }
        .search-keyword {
            font-size: 14px;
            color: #607D8B;
            font-weight: normal;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>📝 Todoアプリ</h1>
    
    <!-- タスク追加フォーム -->
    <div class="add-form">
        <form method="POST">
            <input type="text" name="task" placeholder="新しいタスクを入力..." required>
            <select name="priority">
                <option value="high">🔴 高</option>
                <option value="medium" selected>🟡 中</option>
                <option value="low">🟢 低</option>
            </select>
            <button type="submit" name="add">追加</button>
        </form>
    </div>
    <!-- 検索フォーム -->
    <div class="search-form">
        <form method="GET">
            <input type="text" 
                name="search" 
                placeholder="タスクを検索..." 
                value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="search-btn">🔍 検索</button>
            <?php if ($search !== ''): ?>
                <a href="todo.php" class="clear-btn">✕ クリア</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- タスク一覧 -->
    <h2>
        タスク一覧（<?php echo count($todos); ?>件）
        <?php if ($search !== ''): ?>
            <span class="search-keyword">「<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>」の検索結果</span>
        <?php endif; ?>
    </h2>
    
    <div style="margin-bottom: 15px;">
        <?php if ($show_completed): ?>
            <a href="todo.php" class="filter-btn">✓ 未完了のみ表示</a>
        <?php else: ?>
            <a href="todo.php?show=completed" class="filter-btn">✓ 完了済みも表示</a>
        <?php endif; ?>
    </div>

    <?php if (empty($todos)): ?>
        <div class="empty-message">
            <p>タスクがありません。<br>新しいタスクを追加してください。</p>
        </div>
    <?php else: ?>
        <ul class="todo-list">
            <?php foreach ($todos as $todo): ?>
                <li class="todo-item priority-<?php echo $todo['priority']; ?> <?php echo $todo['is_completed'] ? 'completed' : ''; ?>">
                    
                    <!-- チェックボックス -->
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                        <input type="checkbox" 
                            name="toggle" 
                            class="checkbox"
                            <?php echo $todo['is_completed'] ? 'checked' : ''; ?>
                            onchange="this.form.submit()">
                    </form>
                    <div class="task">
                        <?php echo $todo['task']; ?>
                        <span class="priority-badge">
                            <?php 
                            if ($todo['priority'] === 'high') echo '🔴 高';
                            elseif ($todo['priority'] === 'medium') echo '🟡 中';
                            else echo '🟢 低';
                            ?>
                        </span>
                    </div>
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