<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$gender = $_POST['gender'];
$profile_video = null;

if (!empty($_FILES['profile_video']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['profile_video']['name']);
    move_uploaded_file($_FILES['profile_video']['tmp_name'], $target_file);
    $profile_video = $target_file;
}

$hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

// 업데이트 쿼리 생성
$query = "UPDATE members SET email = ?, name = ?, gender = ?";
$params = [$email, $name, $gender];

if ($hashed_password) {
    $query .= ", password = ?";
    $params[] = $hashed_password;
}

if ($profile_video) {
    $query .= ", profile_video = ?";
    $params[] = $profile_video;
}

$query .= " WHERE id = ?";
$params[] = $id;

$stmt = $pdo->prepare($query);
$stmt->execute($params);

echo "<script>alert('계정이 수정되었습니다.'); window.location.href = 'account.php';</script>";
?>
