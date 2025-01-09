<?php
session_start();

// 로그인 확인
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 데이터베이스 연결
include 'db.php'; // db.php에서 MySQL 연결을 포함합니다.

$username = $_SESSION['username'];

// 사용자의 정보를 가져오기 위한 SQL 쿼리
$query = "SELECT username, email, name, gender FROM members WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);  // 's'는 문자열을 나타냅니다.
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($db_username, $db_email, $db_name, $db_gender);
$stmt->fetch();

// POST 요청 시 계정 정보 업데이트 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $new_name = $_POST['name'];
    $new_gender = $_POST['gender'];

    // 사용자 정보를 업데이트하는 SQL 쿼리
    $update_query = "UPDATE members SET email = ?, name = ?, gender = ? WHERE username = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param("ssss", $new_email, $new_name, $new_gender, $username);
    $update_stmt->execute();

    // 업데이트 완료 후 대시보드로 리디렉션
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>계정 수정</title>
</head>
<body>
    <h1>계정 수정</h1>

    <form action="edit_account.php" method="POST">
        <label for="email">이메일:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($db_email); ?>" required><br><br>

        <label for="name">이름:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($db_name); ?>" required><br><br>

        <label for="gender">성별:</label><br>
        <select name="gender">
            <option value="male" <?php echo ($db_gender == 'male') ? 'selected' : ''; ?>>남성</option>
            <option value="female" <?php echo ($db_gender == 'female') ? 'selected' : ''; ?>>여성</option>
        </select><br><br>

        <input type="submit" value="수정하기">
    </form>

    <a href="dashboard.php">대시보드로 돌아가기</a>
</body>
</html>
