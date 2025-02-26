<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conn.php'; // Include database connection

$db = new conn();
$success_message = "";
$error_message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password.";
    } else {
        try {
            $sql = "SELECT * FROM tbl_users WHERE email = :email LIMIT 1";
            $stmt = $db->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['verified'] == 1) {
                    $session_token = bin2hex(random_bytes(32));

                    // Store user details in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = ucfirst($user['firstname']) . " " . ucfirst($user['lastname']);
                    $_SESSION['session_token'] = $session_token;

                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);

                    // Update user status to "online"
                    $update_status_sql = "UPDATE tbl_users SET status = 'online', session_token = :session_token WHERE id = :id";
                    $update_status_stmt = $db->conn->prepare($update_status_sql);
                    $update_status_stmt->execute([
                        ':session_token' => $session_token,
                        ':id' => $user['id']
                    ]);

                    // Debugging: Print role before redirection
                    error_log("User Role: " . $_SESSION['role']); 

                    // Prevent infinite redirects: Check if already on correct page
                    $currentPage = basename($_SERVER['PHP_SELF']);

                    if ($_SESSION['role'] === 's_admin' && $currentPage !== 'sadmin/index.php') {
                        header("Location: ../super-admin/index.php");
                        exit();
                    } elseif ($_SESSION['role'] === 'admin' && $currentPage !== 'admin/index.php') {
                        header("Location: ../admin/index.php");
                        exit();
                    } elseif ($_SESSION['role'] !== 's_admin' && $_SESSION['role'] !== 'admin' && $currentPage !== 'index.php') {
                        header("Location: ../index.php");
                        exit();
                    }

                } else {
                    $error_message = "Your email is not verified. Please check your inbox.";
                }
            } else {
                $error_message = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}

// Handle logout request
if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_id'])) {
        $update_status_sql = "UPDATE tbl_users SET status = 'offline', session_token = NULL WHERE id = :id";
        $update_status_stmt = $db->conn->prepare($update_status_sql);
        $update_status_stmt->execute([':id' => $_SESSION['user_id']]);
    }

    session_destroy();
    header('Location: login.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-yellow-400 via-blue-300 to-[#13274F] p-4">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50"></div>

    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
        
        <!-- Right Side (Welcome Message with Logo) -->
        <div class="md:w-1/2 bg-blue-500 p-8 text-white flex flex-col justify-center items-center">
            <img src="<?php echo BASE_URL; ?>assets/images/logos/logo.png" alt="Logo" class="w-24 h-24 mb-4">
            <h2 class="text-2xl font-bold">Welcome back!</h2>
            <p class="mt-2 text-center">Welcome back! We are so happy to have you here.</p>
            <a href="registerform.php" class="mt-6 py-2 px-4 bg-white text-blue-500 rounded-lg text-center hover:bg-[#13274F] hover:text-white">
                No account yet? Signup.
            </a>
        </div>
    
        <!-- Left Side (Login Form) -->
        <div class="md:w-1/2 p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800">Signin</h2>

            <form method="POST" class="space-y-4 mt-6">
                <div>
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" name="login"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-[#13274F] transition duration-300">
                    Signin
                </button>
            </form>

            <p class="text-center text-gray-600 mt-2 text-[15px] font-semibold">
                <a href="forgotpassword.php" class="text-[#13274F] hover:underline">Forgot Password?</a>
            </p>
        </div>
    </div>

    <script>
        function showToast(message, type) {
            const colors = {
                success: "bg-green-500",
                error: "bg-red-500"
            };

            const toast = document.createElement("div");
            toast.className = `flex items-center ${colors[type]} text-white p-3 rounded-lg shadow-lg mb-3 transition-all duration-300`;
            toast.innerHTML = `
                <span class="mr-2">${type === "success" ? "" : ""}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto px-2">✖</button>
            `;

            document.getElementById("toast-container").appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Display error or success messages as toast notifications
        window.onload = function () {
            <?php if (!empty($error_message)) : ?>
                showToast("<?php echo htmlspecialchars($error_message); ?>", "error");
            <?php endif; ?>

            <?php if (!empty($success_message)) : ?>
                showToast("<?php echo htmlspecialchars($success_message); ?>", "success");
            <?php endif; ?>
        };
    </script>

</body>
</html>
