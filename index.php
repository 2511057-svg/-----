<?php
// データベースに接続
$conn = new mysqli('localhost', 'webapp', 'karapass', 'karaoke');
if ($conn->connect_error) {
    die("データベース接続失敗: " . $conn->connect_error);
}

// フォームが送信されたときの処理（データベースに追加）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $song_title = $_POST['song_title'];
    
    if (!empty($name) && !empty($song_title)) {
        $stmt = $conn->prepare("INSERT INTO participants (name, song_title) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $song_title);
        $stmt->execute();
        $stmt->close();
        echo "<h3 style='color:green;'>★申し込みが完了しました！★</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カラオケ大会 申し込みシステム</title>
</head>
<body>
    <h1>🎤 カラオケ大会 参加申し込み</h1>
    
    <!-- 申し込みフォーム -->
    <form method="POST" action="">
        <label>お名前: <input type="text" name="name" required></label><br><br>
        <label>曲名: <input type="text" name="song_title" required></label><br><br>
        <input type="submit" value="申し込む">
    </form>

    <hr>

    <!-- 参加者一覧表示 -->
    <h2>【主催者用】現在の参加者一覧</h2>
    <table border="1" cellpadding="5">
        <tr style="background-color:#eee;">
            <th>ID</th>
            <th>名前</th>
            <th>曲名</th>
            <th>申込日時</th>
        </tr>
        <?php
        // データベースからデータを取得して表示
        $result = $conn->query("SELECT * FROM participants ORDER BY id ASC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['song_title'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
