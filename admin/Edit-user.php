<?php
session_start();
include '../conn.php';

$database = new conn();
$conn = $database->conn;

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: consumer.php");
    exit();
}

$id = $_GET['id'];

// Fetch user data
$query = "SELECT * FROM tbl_users WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: consumer.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE tbl_users SET firstname = :firstname, middlename = :middlename, lastname = :lastname, status = :status WHERE id = :id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':middlename', $middlename, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: consumer.php");
        exit();
    } else {
        echo "Error updating user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form method="POST" class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Edit User</h2>

        <label class="block text-gray-700">First Name</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" 
               class="w-full px-4 py-2 border rounded-lg mb-4" required>

        <label class="block text-gray-700">Middle Name</label>
        <input type="text" name="middlename" value="<?php echo htmlspecialchars($user['middlename']); ?>" 
               class="w-full px-4 py-2 border rounded-lg mb-4">

        <label class="block text-gray-700">Last Name</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" 
               class="w-full px-4 py-2 border rounded-lg mb-4" required>

        <button type="submit" class="w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600">
            Update User
        </button>
    </form>
</body>
</html>
