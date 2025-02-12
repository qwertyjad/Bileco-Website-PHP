<?php
session_start();
include '../conn.php';

$db = new conn();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data
    $sql = "SELECT * FROM tbl_users WHERE email = :email";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['verified'] == 1) {
            // Store session data securely
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['firstname'] . " " . $user['lastname'];

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'user':
                    header("Location: user_dashboard.php");
                    break;
                default:
                    session_destroy();
                    echo "Unauthorized access.";
                    exit();
            }
            exit();
        } else {
            $error_message = "Your email is not verified. Please check your email.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>
        
        <?php if (!empty($error_message)) : ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" placeholder="Email" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="login"
                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Don't have an account? <a href="registerform.php" class="text-blue-500 hover:underline">Sign up</a>
        </p>

          <!-- Forgot Password link -->
          <p class="text-center text-gray-600 mt-2">
            <a href="forgotpassword.php" class="text-blue-500 hover:underline">Forgot Password?</a>
        </p>
    </div>
</body>
</html>
