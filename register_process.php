<?php
include 'db.php';  // 데이터베이스 연결

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    // 프로필 영상 업로드
    if (isset($_FILES['profile_video']) && $_FILES['profile_video']['error'] === UPLOAD_ERR_OK) {
        $videoName = $_FILES['profile_video']['name'];
        $videoPath = 'uploads/' . $videoName;
        move_uploaded_file($_FILES['profile_video']['tmp_name'], $videoPath);
    } else {
        $videoPath = null;
    }

    // 데이터베이스에 회원 정보 저장
    $stmt = $pdo->prepare("INSERT INTO users (username, name, password, email, gender, profile_video) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $name, $password, $email, $gender, $videoPath]);

    header("Location: index.php?success=true");
    exit();
}
?>
