<?php
session_start();

// Include the database connection file
include '../conn.php';  // Ensure this path is correct

// Create a new database connection instance
$db = new conn();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Clear session token and update user status to 'offline'
    $update_status_sql = "UPDATE tbl_users SET status = 'offline', session_token = NULL WHERE id = :id";
    $update_status_stmt = $db->conn->prepare($update_status_sql);
    $update_status_stmt->execute([':id' => $user_id]);

    // Unset all session variables
    $_SESSION = [];

    // Destroy session completely
    session_destroy();
}

// Redirect to the login page
header('Location: ../index.php');  // Adjust the path as needed
exit();
?>
