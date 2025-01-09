<?php
$mysqli = new mysqli("localhost", "root", "", "member_db"); // 사용자 이름과 비밀번호 수정

if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM members WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    echo $count > 0 ? "unavailable" : "available"; // 사용 중이면 unavailable 반환
    $stmt->close();
}

$mysqli->close();
?>
