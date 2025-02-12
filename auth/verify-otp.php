<?php
session_start();
include '../conn.php';

$db = new conn();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Query to check if the OTP exists and is not expired
    $sql = "SELECT * FROM tbl_otp WHERE email = :email AND otp = :otp AND expiration_time > NOW()";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([':email' => $email, ':otp' => $otp]);
    $otp_record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($otp_record) {
        // OTP is correct and not expired, proceed to password reset
        header("Location: resetpassword.php?email=" . urlencode($email));
        exit();
    } else {
        // Invalid or expired OTP
        $error_message = "Invalid or expired OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Enter OTP</h2>

        <?php if (!empty($error_message)) : ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" hidden>
                <input type="text" name="otp" placeholder="Enter OTP" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Verify OTP
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Don't have an OTP? <a href="forgotpassword.php" class="text-blue-500 hover:underline">Request again</a>
        </p>
    </div>
</body>
</html>
