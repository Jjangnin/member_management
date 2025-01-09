<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 목록</title>
    <style>
        /* 스타일을 추가합니다 */
    </style>
</head>
<body>
    <h2>회원 목록</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>아이디</th>
            <th>이름</th>
            <th>이메일</th>
            <th>성별</th>
            <th>프로필 영상</th>
            <th>로그인 정보 삭제</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['gender']) ?></td>
            <td>
                <?php if ($user['profile_video']): ?>
                    <video width="100" controls>
                        <source src="<?= htmlspecialchars($user['profile_video']) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    없음
                <?php endif; ?>
            </td>
            <td><a href="delete_login_info.php?id=<?= $user['id'] ?>">삭제</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
