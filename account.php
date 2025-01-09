<?php
session_start();
include 'db.php';

// 세션 확인 (로그인 여부 확인)
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// 사용자 정보 가져오기
$stmt = $pdo->prepare("SELECT id, username, email, role FROM members WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    echo "사용자 정보를 찾을 수 없습니다.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>계정 관리</title>
</head>
<body>
    <h1>계정 관리</h1>
    <p><strong>아이디:</strong> <?= $user['username'] ?></p>
    <p><strong>이메일:</strong> <?= $user['email'] ?></p>
    <p><strong>권한:</strong> <?= $user['role'] ?></p>

    <h2>계정 수정</h2>
    <form action="update_account.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <label>새 이메일: <input type="email" name="email" value="<?= $user['email'] ?>"></label><br>
        <label>새 비밀번호: <input type="password" name="password"></label><br>
        <button type="submit">수정</button>
    </form>

    <h2>계정 삭제</h2>
    <form action="delete_account.php" method="POST" onsubmit="return confirm('정말로 계정을 삭제하시겠습니까?');">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <button type="submit" style="color: red;">계정 삭제</button>
    </form>
</body>
</html>
