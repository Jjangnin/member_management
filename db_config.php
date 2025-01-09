<?php
$host = 'localhost';   // 데이터베이스 서버
$user = 'root';        // MySQL 사용자명
$pass = '';            // 비밀번호 (없으면 빈 문자열)
$dbname = 'part';      // 사용할 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($host, $user, $pass, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}
?>
