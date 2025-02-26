<?php
session_start();
session_regenerate_id(true);

include '../conn.php';
$database = new conn();
$conn = $database->conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (first name & role)
$query = "SELECT firstname, role FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !in_array($user['role'], ['s_admin'])) {
    header("Location: ../index.php");
    exit();
}

$firstName = htmlspecialchars($user['firstname']);
$role = ($user['role'] == 's_admin') ? 'Super Admin' : '';

// Get real-time greeting
date_default_timezone_set('Asia/Manila');
$hour = date('H');

if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}


// Query to count total verified consumers and active users
$query = "SELECT 
            COUNT(*) AS total_consumers, 
            SUM(CASE WHEN status = 'online' THEN 1 ELSE 0 END) AS active_users 
          FROM tbl_users 
          WHERE role NOT IN ('admin', 's_admin') 
          AND verified = 1";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch values
$total_consumers = $result['total_consumers'];
$active_users = $result['active_users'];
?>
        
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Dashboard</title>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'navbar-s.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <main class="p-6 overflow-auto">
                <h1 class="text-3xl font-bold pb-5">
                    <?php echo "$greeting, <span class='text-black'>$firstName ($role)</span>"; ?>
                </h1>

                <div class="grid grid-cols-3 md-grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-lg font-semibold">Registered Consumers</h2>
                    <p class="text-3xl font-bold mt-2"><?php echo $total_consumers; ?></p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-lg font-semibold">Current Active Users</h2>
                    <p class="text-3xl font-bold mt-2"><?php echo $active_users; ?></p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <h2 class="text-lg font-semibold">Current Time</h2>
                    <p id="realTimeClock" class="text-2xl font-bold mt-2"></p>
                </div>

                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <canvas id="myChart"></canvas>
                </div>
            </main>

            <?php include '../components/footer.php'; ?>
        </div>
    </div>
</body>

</html>
<script>
function updateClock() {
    const now = new Date();
    
    // Format time (HH:MM:SS AM/PM)
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();
    let ampm = hours >= 12 ? 'PM' : 'AM';

    // Convert 24-hour format to 12-hour format
    hours = hours % 12;
    hours = hours ? hours : 12; // 12-hour format

    // Add leading zeros if needed
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    // Display the time
    document.getElementById('realTimeClock').innerText = `${hours}:${minutes}:${seconds} ${ampm}`;
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call to avoid delay
</script>