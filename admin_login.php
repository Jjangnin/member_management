<?php
session_start();

// DB 연결 설정
$mysqli = new mysqli("localhost", "root", "", "member_db");

if ($mysqli->connect_error) {
    die("DB 연결 실패: " . $mysqli->connect_error);
}

// 로그인 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 관리자 계정 확인 쿼리 (role = 'admin'인 계정만 확인)
    $sql = "SELECT * FROM members WHERE username = ? AND role = 'admin'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 비밀번호 확인 (password_verify()를 사용하여 해시된 비밀번호 확인)
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;  // 세션에 로그인 정보 저장
            header("Location: view_members.php");  // 로그인 성공 시 관리자 페이지로 리디렉션
            exit();
        } else {
            $error = "아이디 또는 비밀번호가 틀립니다.";
        }
    } else {
        $error = "관리자 계정이 아닙니다.";
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 로그인</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>관리자 로그인</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="admin_login.php" method="POST">
        <input type="text" name="username" placeholder="아이디" required>
        <input type="password" name="password" placeholder="비밀번호" required>
        <button type="submit">로그인</button>
    </form>
</body>
</html>
