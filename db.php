<?php
$mysqli = new mysqli("localhost", "root", "", "member_db");

// 연결 확인
if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}
?>
