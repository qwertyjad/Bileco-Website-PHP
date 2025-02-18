<?php
session_start();

// Include the database connection class
include '../../conn.php';
include '../../components/header.php';

// Create a new database connection instance
$db = new conn();  // Instantiate the connection class

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If no session exists, redirect to login page
    header('Location: ../../auth/login.php');
    exit();
}

// Fetch user data to check status
$user_id = $_SESSION['user_id'];
$sql = "SELECT status FROM tbl_users WHERE id = :id LIMIT 1";
$stmt = $db->conn->prepare($sql);  // Using $db->conn to access the PDO object
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user's status is 'offline'
if ($user && $user['status'] === 'offline') {
    // If the user's status is 'offline', redirect them to login page
    header('Location: ../../auth/login.php');
    exit();
}

// Optionally, update the user's status to 'online' when accessing the dashboard
$update_status_sql = "UPDATE tbl_users SET status = 'online' WHERE id = :id";
$update_status_stmt = $db->conn->prepare($update_status_sql);
$update_status_stmt->execute([':id' => $user_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to the User Dashboard, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>!</h1>
    <p>You are successfully logged in.</p>

    <!-- Dashboard content here -->

    <!-- Logout button -->
    <a href="logout.php">Logout</a>  <!-- Path to logout -->
</body>
</html>
