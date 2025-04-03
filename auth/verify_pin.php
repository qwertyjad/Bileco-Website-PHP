<?php
session_start();
include '../conn.php'; // Include database connection

$db = new conn();
$success_message = "";
$error_message = "";

// Check if temp_accred_user is set in session
if (!isset($_SESSION['temp_accred_user'])) {
    header("Location: login.php");
    exit();
}

$temp_user = $_SESSION['temp_accred_user'];

if (isset($_POST['verify_pin'])) {
    $entered_pin = trim($_POST['pin']);

    if (empty($entered_pin)) {
        $error_message = "Please enter your PIN.";
    } elseif ($entered_pin === $temp_user['pin']) {
        // PIN is correct, check required documents and set status
        $sql = "SELECT * FROM tbl_accreditation WHERE id = :id LIMIT 1";
        $stmt = $db->conn->prepare($sql);
        $stmt->execute([':id' => $temp_user['id']]);
        $accred_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($accred_user) {
            $required_docs = [
                'dti_cert',
                'tax_payer_id',
                'bir_value_added',
                'blacklist_cert',
                'vat_payments',
                'tax_clearance',
                'contract_details',
                'accreditation_fee'
            ];
            $all_docs_provided = true;

            foreach ($required_docs as $doc) {
                if ($accred_user[$doc] != 1) {
                    $all_docs_provided = false;
                    break;
                }
            }

            // Determine the new status
            $new_status = $all_docs_provided ? 'verified' : 'pending';

            // Update the status if necessary
            if ($accred_user['status'] !== $new_status) {
                $update_status_sql = "UPDATE tbl_accreditation SET status = :status WHERE id = :id";
                $update_status_stmt = $db->conn->prepare($update_status_sql);
                $update_status_stmt->execute([
                    ':status' => $new_status,
                    ':id' => $temp_user['id']
                ]);
                if ($new_status === 'verified') {
                    $success_message = "Your account has been auto-verified!";
                }
            }

            // Complete the login process
            $session_token = bin2hex(random_bytes(32));

            // Store user details in session
            $_SESSION['user_id'] = $temp_user['id'];
            $_SESSION['role'] = 'accredited';
            $_SESSION['user_name'] = ucfirst($temp_user['firstname']) . " " . ucfirst($temp_user['lastname']);
            $_SESSION['session_token'] = $session_token;
            $_SESSION['user_type'] = 'tbl_accreditation';
            $_SESSION['pin'] = $temp_user['pin'];

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Update user status to "online"
            $update_status_sql = "UPDATE tbl_accreditation SET status = :status, session_token = :session_token WHERE id = :id";
            $update_status_stmt = $db->conn->prepare($update_status_sql);
            $update_status_stmt->execute([
                ':status' => $new_status, // Ensure status is updated
                ':session_token' => $session_token,
                ':id' => $temp_user['id']
            ]);

            // Clear temp data from session
            unset($_SESSION['temp_accred_user']);

            // Redirect to index.php
            header("Location: " . BASE_URL . "index.php");
            exit();
        } else {
            $error_message = "Error: Unable to retrieve user data.";
        }
    } else {
        $error_message = "Invalid PIN. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify PIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-yellow-400 via-blue-300 to-[#13274F] p-4">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50"></div>

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800">Verify Your PIN</h2>
        <p class="text-center text-gray-600 mt-2">Please enter the PIN provided by the super admin to complete your login.</p>

        <form method="POST" class="space-y-4 mt-6">
            <div>
                <input type="text" name="pin" placeholder="Enter your PIN" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="verify_pin"
                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-[#13274F] transition duration-300">
                Verify PIN
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4 text-[15px] font-semibold">
            <a href="login.php?logout=1" class="text-[#13274F] hover:underline">Cancel and Logout</a>
        </p>
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