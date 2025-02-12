<?php
session_start();
include '../conn.php';

$db = new conn();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Update the user's password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE tbl_users SET password = :password WHERE email = :email";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':password' => $hashed_password, ':email' => $email]);

    // Delete the OTP record after use
    $sql = "DELETE FROM tbl_otp WHERE email = :email";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':email' => $email]);

    $success_message = "Your password has been reset successfully. You can now log in.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Reset Password</h2>

        <?php if (!empty($success_message)) : ?>
            <p class="text-green-500 text-center mb-4"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" hidden>
                <input type="password" name="new_password" placeholder="Enter new password" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Reset Password
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Remembered your password? <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
