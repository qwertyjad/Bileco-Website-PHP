<?php
session_start();
include '../conn.php';

$database = new conn();
$conn = $database->conn;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: consumer.php");
    exit();
}

$id = $_GET['id'];

$query = "DELETE FROM tbl_users WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: consumer.php?deleted=success");
    exit();
} else {
    echo "Error deleting user.";
}
?>
