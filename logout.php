<?php
session_start();
session_destroy();  // 세션을 종료하고
header("Location: admin_login.php");  // 로그인 페이지로 리디렉션
exit();
?>
