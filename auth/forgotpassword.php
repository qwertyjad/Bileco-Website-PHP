<?php
session_start();
include '../conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$db = new conn();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $sql = "SELECT * FROM tbl_users WHERE email = :email";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $otp = rand(100000, 999999);
        $expiration_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Check if OTP already exists
        $sql = "SELECT * FROM tbl_otp WHERE email = :email";
        $stmt = $db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $existing_otp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_otp) {
            // Update existing OTP
            $sql = "UPDATE tbl_otp SET otp = :otp, expiration_time = :expiration_time WHERE email = :email";
        } else {
            // Insert new OTP if none exists
            $sql = "INSERT INTO tbl_otp (email, otp, expiration_time) VALUES (:email, :otp, :expiration_time)";
        }

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':otp' => $otp,
            ':expiration_time' => $expiration_time
        ]);

        // Send OTP via email
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '1973.bileco@gmail.com';  // Replace with your email
            $mail->Password = 'xbgo qbep afwr fvel';  // Replace with app password (never hardcode passwords)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email details
            $mail->setFrom('1973.bileco@gmail.com', 'Bileco Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP is: <b>$otp</b>. It expires in 15 minutes.";

            $mail->send();

            $_SESSION['success_message'] = "OTP has been sent to your email: $email";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Mailer Error: {$mail->ErrorInfo}";
        }

        sleep(5); // Delay for 2 seconds
        header("Location: verify-otp.php?email=" . urlencode($email));
    exit();
    } else {
        $_SESSION['error_message'] = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script>
        <?php if (!empty($_SESSION['success_message'])): ?>
            // Show toast for successful OTP sent
            window.onload = function() {
                Toastify({
                    text: "<?php echo $_SESSION['success_message']; ?>",
                    duration: 5000, // Toast duration
                    close: true,
                    gravity: "top", // Position at the top
                    position: "right", // Right side
                    backgroundColor: "#4caf50", // Success color
                }).showToast();
                // Clear success message after showing
                <?php unset($_SESSION['success_message']); ?>
            }
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
            // Show toast for error message
            window.onload = function() {
                Toastify({
                    text: "<?php echo $_SESSION['error_message']; ?>",
                    duration: 5000, // Toast duration
                    close: true,
                    gravity: "top", // Position at the top
                    position: "right", // Right side
                    backgroundColor: "#f44336", // Error color
                }).showToast();
                // Clear error message after showing
                <?php unset($_SESSION['error_message']); ?>
            }
        <?php endif; ?>
    </script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>

        <?php if (!empty($_SESSION['error_message'])) : ?>
            <p class="text-red-500 text-center mb-4"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" placeholder="Enter your email" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Send OTP
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Remembered your password? <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
