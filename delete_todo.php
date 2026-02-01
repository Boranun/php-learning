<?php
// データベース接続
require_once 'db.php';

// POSTで送られてきたか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    
    $id = $_POST['id'];
    
    // データベースから削除（DELETE）
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // todo.phpにリダイレクト
    header('Location: todo.php');
    exit;
    
} else {
    // 不正なアクセス
    die("不正なアクセスです。");
}
?>