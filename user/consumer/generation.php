
<?php
session_start();
include '../../conn.php';
include '../../function.php';
include '../../components/header.php';

// Instantiate the database connection class
$database = new conn();
$conn = $database->conn; // Get the PDO connection

$function = new Functions();
$newsList = $function->getAllNews();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user status from the database using PDO
    $query = "SELECT status FROM tbl_users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_status = $stmt->fetchColumn(); // Fetch only the status column

} else {
    $user_status = 'offline'; // Default status for guests
}

// Display the appropriate navbar
if ($user_status === 'online') {
    include '../../components/navbar-u.php';
} else {
    include '../../components/navbar.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
 <?php
        echo '<div class="header-image w-full h-[250px] overflow-hidden">';
        echo '<img src="' . 'https://senderoconsulting.com/wp-content/uploads/2024/03/AdobeStock_327676918-scaled.jpeg' . '" alt="Bridge" class="w-full h-full object-cover" />';
        echo '</div>';
    ?>
<body class="bg-white">

    <div class="container mx-auto py-6 flex flex-col md:flex-row md:space-x-4">
        <!-- Main Content Section -->
        <div class="order-1 md:order-2 w-full md:w-3/5 bg-white p-6 rounded-md">
        <h2 class="text-3xl font-bold text-[#87CEEB] mb-4">GENERATION MIX</h2>
        <hr class="border-t-4 border-b-4 border-[ffdb19] mt-1 mb-8">
        <ul class="space-y-4">
    <?php
    // Data for effective rates, with clickable titles and other date information
    $effective_rates = [
        "Breakdown of Generation Charge for January 2025" => ["January 21, 2025", "#"],
        "Breakdown of Generation Charge for December 2024" => ["DDecember 20, 2024", "#"],
        "Breakdown of Generation Charge for November 2024" => ["November 20, 2024", "#"],
        "Breakdown of Generation Charge for October 2024" => ["November 7, 2024", "#"],
        "Breakdown of Generation Charge for September 2024" => ["September 20, 2024", "#"],
        "Breakdown of Generation Charge for August 2024" => ["August 20, 2024", "#"],
        "Breakdown of Generation Charge for July 2024" => ["July 22, 2024", "#"],
        "Breakdown of Generation Charge for June 2024" => ["July 1, 2024", "#"],
        "Breakdown of Generation Charge for May 2024" => ["May 21, 2024", "#"],
        "Breakdown of Generation Charge for April 2024" => ["April 18, 2024", "#"]
    ];

    // Loop to display the clickable titles and other dates
    foreach ($effective_rates as $title => [$date, $link]) {
        echo "<li class='bg-white p-4 border-b'>
                <strong class='text-lg text-black'>
                    <a href='$link' class='text-black hover:text-[#7CB9E8] active:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500'>$title</a>
                </strong>
                <p class='text-black text-sm italic'>$date / 
                    <a href='#comments' class='text-black hover:text-blue-600 active:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500'>0 Comments</a>
                </p>
              </li>";
    }
    ?>
</ul>
</div>

        <!-- Left Sidebar Section -->
        <div class="order-2 md:order-1 w-full md:w-1/5 bg-white border-r p-6 rounded-md md:pt-20">
            <ul class="space-y-4 md:text-right font-semibold">
                <li><a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="text-black hover:text-blue-800 text-sm">Apply for New Connection</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/rights.php" class="text-black hover:text-blue-800 text-sm">Rights and Obligations</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/billing.php" class="text-black hover:text-blue-800 text-sm">Billing Information</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/qualification.php" class="text-black hover:text-blue-800 text-sm">Qualifications of EC Board</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/senior.php" class="text-black hover:text-blue-800 text-sm">Senior Citizen Discount</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/maintenance.php" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/power.php" class="text-black hover:text-blue-800 text-sm">Power Rates</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/generation.php" class="text-blue-800 hover:text-blue-800 text-sm">Generation Mix</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/safety.php" class="text-black hover:text-blue-800 text-sm">Safety Tips</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/consumer/tipid.php" class="text-black hover:text-blue-800 text-sm">Tipid Tips</a></li>
            </ul>
        </div>


        <!-- Right Sidebar Section -->
        <div class="order-3 md:order-3 w-full md:w-1/5 bg-white p-6 rounded-md border-l">
            <div class="search-box mb-6">
                <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none">
            </div>

            <h2 class="text-xl font-semibold text-black border-l-4 pl-2 border-blue-500 mb-4">Categories</h2>
            <ul class="space-y-2">
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">National Stories</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">News & Events</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
                <hr>
            </ul>

            <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
            <ul class="space-y-2">
            <?php
            $archives = $function->getArchives(); // Fetch archives from the database

            if (!empty($archives)) {
                foreach ($archives as $archive) {
                    echo '<li>
                            <a href="archives.php?date=' . $archive['archive_link'] . '" class="text-blue-600 hover:underline">' . $archive['archive_date'] . '</a>
                        </li>';
                }
            } else {
                echo '<li class="text-gray-500">No archives available</li>';
            }
            ?>
        </ul>
        </div>

    </div>
    <?php
    include '../../components/links.php';
    include '../../components/footer.php';
    ?>
</body>
</html>
