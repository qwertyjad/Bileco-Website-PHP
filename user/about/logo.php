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
        $user_status = 'offline'; // Fallback for invalid user_type
    }
} else {
    $user_status = 'offline'; // Default status for guests or if session data is missing
}

// Display the appropriate navbar
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../../components/navbar-u.php'; // Navbar for other logged-in users (e.g., tbl_users)
    }
} else {
    include '../../components/navbar.php'; // Navbar for guests or offline users
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">

<!-- Header Image -->  
<div class="w-full">  
        <img src="<?php echo BASE_URL; ?>assets/images/about/power.jpg" alt="Header Image" class="w-full h-64 object-cover rounded-md ">  
    </div>

    <div class="container mx-auto py-6 flex flex-col md:flex-row md:space-x-4">

        <!-- Main Content Section -->
        <div class="order-1 md:order-2 w-full md:w-3/5 bg-white p-6 rounded-md">
            <h2 class="text-2xl font-bold text-[#87CEEB] mb-4">OUR LOGO</h2>
            <hr class="border-t-4 border-b-4 border-[ffdb19] mt-1 mb-8">
            <ol class="list-decimal pl-5 text-gray-700 space-y-3">

            
    <img src="<?php echo BASE_URL; ?>assets/images/about/logo.png" alt="BILECO Logo" class="w-64 h-64 flex justify-center mt-8 mx-auto"> 
            

            


<p class="text-gray-700 text-justify py-6 ">The new logo depicts the salient characteristics and the vital role played by BILECO in the development of Biliran province. It portrays its new vision and imbibes new corporate values. The following are the brief descriptions of the logo:"</p>  

 

<div class="space-y-4 mt-8">
        <!-- Example Core Values (You Can Fetch This from Database) -->
        <?php
        $values = [
            "Lightning Bolts" => "It symbolizes BILECO’s pursuit to rural electrification within its franchise area ",
            "Seven Bolts" => "The seven bolts represent its seven corporate values, namely: Godliness, Discipline, Honesty, Excellence, Accountability, Respect and Teamwork",
            "Biliran Map" => "It represents its franchise area where BILECO operates",
            "Biliran Bridge" => "It serves an iconic landmark of the province which symbolize ",
            "The Name ‘BILECO’" => "The placement of ‘BILECO’ under the bridge signifies BILECO’s role in building the foundation of a developed and progressive Biliran province through the provision of a reliable and efficient electric service",
            "Colors Blue and Yellow" => "The official colors of BILECO",
           
        ];

        foreach ($values as $key => $value) {
            echo "
                <div class='flex items-start space-x-10'>
                    <strong class='text-gray-700 py-2 font-bold w-1/4 text-right'>$key</strong>
                    <p class='ml-4 text-gray-700 text-justify w-3/4'>$value</p>
                </div>
                   ";
        }
        ?>
</ol>
</ol></div>
        <!-- Left Sidebar Section -->
        <div class="order-2 md:order-1 w-full md:w-1/5 bg-white border-r p-6 rounded-md md:pt-20">
            <ul class="space-y-4 text-right font-bold">
                <li><a href="<?php echo BASE_URL; ?>user/about/history.php" class="text-black hover:text-blue-800 text-sm">Brief History</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/vision.php" class="text-black hover:text-blue-800 text-sm">Vision, Mission, & Values</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/about/logo.php" class="text-blue-800 hover:text-blue-800 text-sm">Our Logo</a></li>
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
include '../../components/links.php';
include '../../components/footer.php';
?>

</body>
</html>