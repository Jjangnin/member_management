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

// 계정 삭제 처리
if (isset($_POST['delete'])) {
    // 계정 삭제를 위한 SQL 쿼리 (MySQLi를 사용)
    $delete_query = "DELETE FROM members WHERE username = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    $delete_stmt->bind_param("s", $username);
    $delete_stmt->execute();

    // 계정 삭제 후 세션 종료 및 쿠키 삭제
    session_destroy(); // 세션 종료
    setcookie("username", "", time() - 3600, "/"); // 쿠키 삭제

    // 로그아웃 후 로그인 페이지로 리디렉션
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>계정 삭제</title>
</head>
<body>
    <h1>계정 삭제</h1>

    <p>정말로 계정을 삭제하시겠습니까?</p>

    <!-- 계정 삭제 폼 -->
    <form action="delete_account.php" method="POST">
        <input type="submit" name="delete" value="계정 삭제">
    </form>

    <a href="dashboard.php">대시보드로 돌아가기</a>
</body>
</html>
