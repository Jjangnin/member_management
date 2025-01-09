<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "member_db");

if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];
$remember = isset($_POST['remember']); // 자동 로그인 체크 여부 확인

$stmt = $mysqli->prepare("SELECT password FROM members WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    $_SESSION['username'] = $username;

    // 자동 로그인 선택 시 쿠키 설정 (1주일 유효)
    if ($remember) {
        setcookie("username", $username, time() + (7 * 24 * 60 * 60), "/");
    } else {
        // 자동 로그인 해제 시 쿠키 삭제
        setcookie("username", "", time() - 3600, "/");
    }

    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('로그인 실패'); window.location.href = 'index.php';</script>";
}

$stmt->close();
$mysqli->close();
?>
