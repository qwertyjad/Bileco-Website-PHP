<?php
session_start();
include '../conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$db = new conn();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM tbl_users WHERE email = :email";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Insert OTP into the tbl_otp table with an expiration time of 15 minutes
        $expiration_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $sql = "INSERT INTO tbl_otp (email, otp, expiration_time) VALUES (:email, :otp, :expiration_time)";
        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':otp' => $otp,
            ':expiration_time' => $expiration_time
        ]);

        // Send OTP to the user's email using PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                        // Specify main SMTP server
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'hadizy.io@gmail.com';  // Replace with correct email
            $mail->Password = 'czdq kiqz ctsq lkiw';           // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
            $mail->Port = 587;                                    // TCP port to connect to

            // Recipients
            $mail->setFrom('your-email@gmail.com', 'Your Name or Company');
            $mail->addAddress($email);                            // Add recipient's email

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password Reset OTP';
            $mail->Body    = "Hello, <br><br>Your OTP for password reset is: <b>$otp</b><br><br>Please use this OTP to reset your password. It will expire in 15 minutes.";

            // Send email
            $mail->send();
        } catch (Exception $e) {
            $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect to OTP verification page
        header("Location: verify-otp.php?email=" . urlencode($email));
        exit();
    } else {
        $error_message = "Email not found in our records.";
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
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>

        <?php if (!empty($error_message)) : ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error_message; ?></p>
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
