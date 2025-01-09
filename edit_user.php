<?php
include 'db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT username, email, role FROM members WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "사용자를 찾을 수 없습니다.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 수정</title>
</head>
<body>
    <h1>회원 수정</h1>
    <form action="update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <label>아이디: <input type="text" name="username" value="<?= $user['username'] ?>" readonly></label><br>
        <label>이메일: <input type="email" name="email" value="<?= $user['email'] ?>"></label><br>
        <label>권한: 
            <select name="role">
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>사용자</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>관리자</option>
            </select>
        </label><br>
        <button type="submit">저장</button>
    </form>
</body>
</html>
