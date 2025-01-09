<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE users SET auto_login_token = NULL WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: user_list.php");
exit();
?>
