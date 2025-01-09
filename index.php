<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 관리 시스템</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select,
        input[type="file"] {
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        #username_check {
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message, .logout-message {
            color: green;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .login-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            width: 100%;
            max-width: 900px;
        }

        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .login-container {
                flex-direction: column;
                align-items: center;
            }

            .login-form {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
    <script>
        // ID 중복 검사 기능
        function checkUsername() {
            var username = document.getElementById("username").value;
            if (username) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "check_username.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    var response = document.getElementById("username_check");
                    if (xhr.responseText === "available") {
                        response.innerHTML = "사용 가능한 아이디입니다.";
                        response.style.color = "green";
                    } else {
                        response.innerHTML = "이미 사용 중인 아이디입니다.";
                        response.style.color = "red";
                    }
                };
                xhr.send("username=" + encodeURIComponent(username));
            } else {
                document.getElementById("username_check").innerHTML = "";
            }
        }

        // 비밀번호 확인 기능
        function checkPasswordMatch() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var message = document.getElementById("password_check");

            if (password !== confirmPassword) {
                message.innerHTML = "비밀번호가 일치하지 않습니다.";
                message.style.color = "red";
            } else {
                message.innerHTML = "";
            }
        }
    </script>
</head>
<body>
    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
        <div class="logout-message">로그아웃이 성공적으로 완료되었습니다!</div>
    <?php endif; ?>

    <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
        <div class="success-message">회원가입이 성공적으로 완료되었습니다!</div>
    <?php endif; ?>

    <h2>회원 가입</h2>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <input type="text" id="username" name="username" placeholder="아이디" required onblur="checkUsername()">
        <span id="username_check"></span><br>

        <input type="text" name="name" placeholder="이름" required><br>

        <input type="password" id="password" name="password" placeholder="비밀번호" required onkeyup="checkPasswordMatch()"><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="비밀번호 확인" required onkeyup="checkPasswordMatch()">
        <span id="password_check"></span><br>

        <label>성별:</label>
        <select name="gender" required>
            <option value="">선택하세요</option>
            <option value="male">남성</option>
            <option value="female">여성</option>
        </select><br>

        <input type="email" name="email" placeholder="이메일" required><br>

        <label>소개 영상 업로드:</label>
        <input type="file" name="profile_video" accept="video/*"><br>

        <button type="submit">가입</button>
    </form>

    <div class="login-container">
        <!-- 일반 로그인 폼 -->
        <div class="login-form">
            <h2>로그인</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="아이디" required>
                <input type="password" name="password" placeholder="비밀번호" required>
                <label>
                    <input type="checkbox" name="remember"> 자동 로그인
                </label><br>
                <button type="submit">로그인</button>
            </form>
        </div>

        <!-- 관리자 로그인 폼 -->
        <div class="login-form">
            <h2>관리자 로그인</h2>
            <form action="admin_login.php" method="POST">
                <input type="text" name="username" placeholder="아이디" required>
                <input type="password" name="password" placeholder="비밀번호" required>
                <label>
                    <input type="checkbox" name="remember"> 자동 로그인
                </label><br>
                <button type="submit">로그인</button>
            </form>
        </div>
    </div>
</body>
</html>
