<?php
session_start();
include '../conn.php';

$db = new conn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];  // Get email from session after OTP verification
    $new_password = $_POST['new_password'];

    // Validate password (ensure it's at least 6 characters)
    if (strlen($new_password) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password
        $sql = "UPDATE tbl_users SET password = :password WHERE email = :email";
        $stmt = $db->conn->prepare($sql);
        $stmt->execute([':password' => $hashed_password, ':email' => $email]);

        $_SESSION['success_message'] = "Your password has been reset successfully!";
        unset($_SESSION['email']);  // Remove email from session
    
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script>
        <?php if (!empty($_SESSION['success_message'])): ?>
            window.onload = function() {
                // Show toast for successful password reset
                Toastify({
                    text: "<?php echo $_SESSION['success_message']; ?>",
                    duration: 3000, // Toast duration
                    close: true,
                    gravity: "top", // Position at the top
                    position: "right", // Right side
                    backgroundColor: "#4caf50", // Success color
                }).showToast();

                // Clear success message after showing
                <?php unset($_SESSION['success_message']); ?>

                // Redirect to login page after 3 seconds (toast duration)
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 3000); // 3000 milliseconds = 3 seconds
            }
        <?php endif; ?>
    </script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Reset Password</h2>

        <form method="POST" class="space-y-4">
            <input type="password" name="new_password" placeholder="Enter new password" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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