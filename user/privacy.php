<?php
session_start();
include '../conn.php'; // Include the database connection class
include '../components/header.php';
include '../function.php';

// Instantiate the database connection class
$database = new conn();
$function = new Functions();
$conn = $database->conn; // Get the PDO connection


// Check if the user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    // Fetch user status based on user type
    if ($user_type === 'tbl_users') {
        $query = "SELECT status FROM tbl_users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn(); // Fetch the status column (online/offline)
    } elseif ($user_type === 'tbl_accreditation') {
        $query = "SELECT online_status FROM tbl_accreditation WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn(); // Fetch the online_status column (online/offline)
    } else {
        $user_status = 'offline'; // Fallback in case user_type is invalid
    }
} else {
    $user_status = 'offline'; // Default status for guests or if session data is missing
}

// Display the appropriate navbar
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../components/navbar-u.php'; // Navbar for other logged-in users (e.g., tbl_users)
    }
} else {
    include '../components/navbar.php'; // Navbar for guests or offline users
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
 <?php
        echo '<div class="header-image w-full h-[250px] overflow-hidden">';
        echo '<img src="' . 'https://static.vecteezy.com/system/resources/previews/037/145/251/non_2x/soft-focus-of-light-bulb-in-market-shop-with-walking-street-free-photo.jpg' . '" alt="Bridge" class="w-full h-full object-cover" />';
        echo '</div>';
    ?>
<body class="bg-white">

    <div class="container mx-auto py-6 flex flex-col md:flex-row md:space-x-4">
        <!-- Main Content Section -->
        <div class="order-1 md:order-2 w-full md:w-3/5 bg-white p-6 rounded-md">
            <h2 class="text-3xl font-bold text-[#87CEEB] mb-4">PRIVACY POLICY</h2>
            <hr class="border-t-4 border-b-4 border-[ffdb19] mt-1 mb-8">
        <p class="text-justify pb-4">This website does not share personal information with third parties nor do we store any information about your visit to this blog other than to analyze and optimize your content and reading experience through the use of cookies. </p>
        <p class="text-justify pb-4">You can turn off the use of cookies at anytime by changing your specific browser settings.</p>
        <p class="text-justify pb-4">We are not responsible for republished content from this website on other blogs or websites without our permission.</p> 
        <p class="text-justify pb-4">This privacy policy is subject to change without notice and was last updated on January 12, 2018. If you have any questions feel free to contact us directly at bileco1973@gmail.com.</p>    
            </hr>
        </div>

         <!-- Left Sidebar Section -->
         <div class="order-2 md:order-1 w-full md:w-1/5 bg-white border-r p-6 rounded-md md:pt-20">
            <ul class="space-y-4 text-right font-semibold">
                 <li><a href="<?php echo BASE_URL; ?>user/about/history.php" class="text-black hover:text-blue-800 text-sm">Brief History</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/vision.php" class="text-black hover:text-blue-800 text-sm">Vision, Mission, & Values</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/logo.php" class="text-black hover:text-blue-800 text-sm">Our Logo</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/board.php" class="text-black hover:text-blue-800 text-sm">The Board of Directors</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/management.php" class="text-black hover:text-blue-800 text-sm">The Management</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/franchise.php" class="text-black hover:text-blue-800 text-sm">Franchise Area</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/practices.php" class="text-black hover:text-blue-800 text-sm">Best Practices</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/awards.php" class="text-black hover:text-blue-800 text-sm">Awards & Citations</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/power.php" class="text-black hover:text-blue-800 text-sm">Power Sources</a></li>
            </ul>
        </div>

        <!-- Right Sidebar Section -->
        <div class="order-3 md:order-3 w-full md:w-1/5 bg-white p-6 rounded-md border-l">
            <div class="search-box mb-6">
                <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none">
            </div>

            <h2 class="text-xl font-semibold text-black border-l-4 pl-2 border-blue-500 mb-4">Categories</h2>
            <ul class="space-y-2">
                <li><a href="<?php echo BASE_URL; ?>user/categories/announcement.php" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/bids-awards.php" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/csr-programs.php" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/generation-mix.php" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/maintenance.php" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/national-stories.php" class="text-black hover:text-blue-800 text-sm">National Stories</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/news.php" class="text-black hover:text-blue-800 text-sm">News & Events</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/power-rate.php" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/uncategorized.php" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
            </ul>

            <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
            <ul class="space-y-2">
            <?php
            $archives = $function->getArchives(); // Fetch archives from the database

            if (!empty($archives)) {
                foreach ($archives as $archive) {
                    echo '<li>
                            <a href="../archives.php?date=' . $archive['archive_link'] . '" class="text-blue-600 hover:underline">' . $archive['archive_date'] . '</a>
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
    include '../components/links.php';
    include '../components/footer.php';
    include '../chatbot.php';
 
    ?>
</body>
</html>