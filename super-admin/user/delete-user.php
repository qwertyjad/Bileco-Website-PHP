<?php
session_start();
include '../../conn.php';

$database = new conn();
$conn = $database->conn;

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "DELETE FROM tbl_users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

}

?>
