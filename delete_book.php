<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php?msg=deleted");
exit();
?>