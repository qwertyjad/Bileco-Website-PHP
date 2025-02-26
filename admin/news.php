<?php
session_start();
session_regenerate_id(true); // Regenerate session ID for security

include '../conn.php'; // Include the database connection class
// Instantiate the database connection class
$database = new conn();
$conn = $database->conn; // Get the PDO connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect guests (not logged in) away
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user role from the database using PDO
$query = "SELECT role FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_role = $stmt->fetchColumn(); // Fetch only the role column

// Redirect non-admin users away
if ($user_role !== 'admin') {
    header("Location: ../index.php"); // Redirect to user homepage
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
    <?php include 'navbar-a.php'; ?>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <main class="p-6 overflow-auto">
                <div class="grid gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">Card 1</div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <canvas id="myChart"></canvas>
                </div>
            </main>

            <?php include '../components/footer.php'; ?>
        </div>
    </div>

</body>
</html>