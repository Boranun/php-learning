<?php 

$host='localhost';
$dbname='php_learning';
$username = 'root'; 
$password = '';  

try{
    $pdo=new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // エラーモードを設定（エラーが出たら例外を投げる）
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 接続成功
    // echo "データベース接続成功！"; // デバッグ用（後で削除）
}
catch (PDOException $e) {
    // 接続失敗
    die("データベース接続エラー: " . $e->getMessage());
}
?>