<?php
session_start();
include '../../conn.php';
include 'fetch-user.php'; // Reuse fetch function

$database = new conn();
$conn = $database->conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id == 0) {
    header("Location: mng-user.php");
    exit();
}

// Fetch user details
$query = "SELECT id, accountnum, firstname, middlename, lastname, status FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: mng-user.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountnum = trim($_POST['accountnum']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $status = trim($_POST['status']);

    $query = "UPDATE tbl_users SET accountnum = :accountnum, firstname = :firstname, 
              middlename = :middlename, lastname = :lastname, status = :status WHERE id = :user_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':accountnum', $accountnum, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':middlename', $middlename, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $flag = $stmt->execute();

}
?>

<title>Edit User</title>
<div class="flex h-screen bg-gray-100">
    <?php include '../navbar-s.php'; ?>
    <div class="flex-1 overflow-y-auto p-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-bold mb-4">Edit User</h2>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="mb-4"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="text" name="accountnum" value="<?= htmlspecialchars($user['accountnum']) ?>" required 
                    class="w-full px-4 py-2 border rounded">

                <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required 
                    class="w-full px-4 py-2 border rounded">

                <input type="text" name="middlename" value="<?= htmlspecialchars($user['middlename']) ?>" 
                    class="w-full px-4 py-2 border rounded">

                <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required 
                    class="w-full px-4 py-2 border rounded">

                <select name="status" required class="w-full px-4 py-2 border rounded">
                    <option value="online" <?= $user['status'] == 'online' ? 'selected' : '' ?>>Online</option>
                    <option value="offline" <?= $user['status'] == 'offline' ? 'selected' : '' ?>>Offline</option>
                </select>

                <div class="flex justify-between">
                    <a href="mng-user.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>