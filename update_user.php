<?php
include 'db.php';

$id = $_POST['id'];
$email = $_POST['email'];
$role = $_POST['role'];

$stmt = $pdo->prepare("UPDATE members SET email = ?, role = ? WHERE id = ?");
$stmt->execute([$email, $role, $id]);

header("Location: user_list.php");
exit();
?>
