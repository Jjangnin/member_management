<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "member_db");

if ($mysqli->connect_error) {
    die("연결 실패: " . $mysqli->connect_error);
}

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    
    // 비밀번호 확인
    if ($password !== $confirm_password) {
        echo "비밀번호가 일치하지 않습니다.";
        exit();
    }

    // 아이디 중복 체크
    $checkStmt = $mysqli->prepare("SELECT id FROM members WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows > 0) {
        echo "이미 사용 중인 아이디입니다.";
        $checkStmt->close();
        exit();
    }
    $checkStmt->close();

    // 비밀번호 해시화
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 업로드 폴더 확인
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);  // uploads 폴더가 없으면 생성
    }

    // 프로필 영상 업로드
    $profile_video = null;
    if (isset($_FILES['profile_video']) && $_FILES['profile_video']['error'] === UPLOAD_ERR_OK) {
        $videoName = basename($_FILES['profile_video']['name']);
        $videoPath = 'uploads/' . $videoName;
        
        // 업로드 폴더로 이동
        if (move_uploaded_file($_FILES['profile_video']['tmp_name'], $videoPath)) {
            $profile_video = $videoPath;
        } else {
            echo "프로필 영상 업로드에 실패했습니다.";
            exit();
        }
    }

    // 회원가입 쿼리
    $stmt = $mysqli->prepare("INSERT INTO members (username, password, name, gender, email, profile_video) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        echo "SQL 준비 실패: " . $mysqli->error;
        exit();
    }

    $stmt->bind_param("ssssss", $username, $hashed_password, $name, $gender, $email, $profile_video);

    if ($stmt->execute()) {
        // 성공적으로 가입되었으면 성공 페이지로 리디렉션
        header("Location: success.php");
        exit();
    } else {
        echo "회원가입 실패: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
