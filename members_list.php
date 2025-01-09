<?php
$mysqli = new mysqli("localhost", "root", "", "member_db");

if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT username, name, gender, email, video_path FROM members");

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 목록</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        video {
            width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>회원 목록</h2>
    <table>
        <tr>
            <th>아이디</th>
            <th>이름</th>
            <th>성별</th>
            <th>이메일</th>
            <th>소개 영상</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <?php if ($row['video_path']): ?>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($row['video_path']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <span>영상 없음</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$mysqli->close();
?>
