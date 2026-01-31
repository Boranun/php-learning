<?php
// データベース接続ファイルを読み込む
require_once 'db.php';

echo "<h1>データベース接続テスト</h1>";
echo "<p>接続成功しました！</p>";

// テーブルの確認
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "<h2>作成済みテーブル:</h2>";
echo "<ul>";
foreach ($tables as $table) {
    echo "<li>" . $table . "</li>";
}
echo "</ul>";
?>