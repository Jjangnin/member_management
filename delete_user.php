<?php
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
$stmt->execute([$id]);

header("Location: user_list.php");
exit();
?>
