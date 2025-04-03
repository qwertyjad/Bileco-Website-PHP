<?php
session_start();
include '../../conn.php';
$database = new conn();
$conn = $database->conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_role = $stmt->fetchColumn();

if ($user_role !== 's_admin') {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tbl_accreditation WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

// Redirect with success parameter for toast notification
header("Location: accreditation.php?delete_success=1");
exit();