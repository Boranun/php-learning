<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP学習</title>
</head>
<body>
    <hr>
    <h2>配列と繰り返し</h2>
    <?php
    $skills=["React","Next.js","TypeScript","PHP"];
    echo "<h3>習得済みスキル:</h3>";
    echo "<ul?>";

    foreach ($skills as $skill){
        echo "<li>".$skill."</li>";
    }

    echo "</ul>";
    ?>

    <hr>
    <h2>条件分岐</h2>
    <?php
    $score = 85;
    if ($score >=80){
        echo "<p>優秀です!</p>";
    }
    elseif($score>=60){
        echo "<p>合格です。</p>";
    }
    else {
        echo "<p>もう少し頑張りましょう。</p>";
    }
        ?>
</body>
</html>