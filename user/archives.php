<?php
include '../conn.php'; // Include database connection class
include '../function.php'; // Include Functions class
include '../components/header.php';

session_start();

// Instantiate database connection
$database = new conn();
$conn = $database->conn;
$function = new Functions();

// Fetch filtered news by month/year if applicable
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$newsList = $function->getNewsByMonth($date_filter);

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details (status & role)
    $user = Functions::getUserDetails($conn, $user_id);

    if ($user) {
        $user_status = $user['status'];
        $user_role = $user['role'];

        // Redirect admin users to the appropriate dashboard
        Functions::redirectBasedOnRole($user_role);
    } else {
        $user_status = 'offline'; // Default status for guests
    }
} else {
    $user_status = 'offline'; // Default status for guests
}

// Fetch super admin first name
$superAdminFirstName = $function->getSuperAdminFirstName();

// Display the appropriate navbar
Functions::includeNavbarBasedOnStatus($user_status);
?>

<title>Archives</title>

<body class="bg-white">
    <div class="container mx-auto py-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Main Content Section -->
        <div class="col-span-3 bg-white p-6 rounded-md">
        <h2 class="text-3xl font-bold text-[#87CEEB] mb-4">MONTHLY ARCHIVES : 
    <?php echo !empty($date_filter) ? date("F Y", strtotime($date_filter . "-01")) : "Archives"; ?>
</h2>

            <hr class="border-t-4 border-b-4 border-[#ffdb19] mt-1 mb-8">

            <!-- News List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($newsList)): ?>
                    <?php foreach ($newsList as $news): ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow-md flex flex-col h-full">
                            <!-- News Image -->
                            <!-- News Image -->
<?php if (!empty($news['image'])): ?>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" 
         class="w-full h-48 object-cover rounded-md mb-3" alt="News Image">
<?php else: ?>
    <div class="w-full h-48 flex items-center justify-center bg-gray-200 rounded-md">
        <i class="bx bx-image text-5xl text-gray-400"></i>
    </div>
<?php endif; ?>


                            <!-- Admin Name and Date -->
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm text-gray-500">
                                    <i class="bx bx-user"></i> <?php echo htmlspecialchars($superAdminFirstName); ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="bx bx-calendar"></i> <?php echo date("M d, Y", strtotime($news['date'])); ?>
                                </p>
                            </div>

                            <!-- News Title -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                <?php
                                $title = htmlspecialchars($news['title']);
                                echo strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;
                                ?>
                            </h3>

                            <!-- Read More -->
                            <a href="news-details.php?id=<?php echo $news['id']; ?>" class="text-blue-600 hover:underline font-medium mt-auto">
                                Read More →
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">No news available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Sidebar Section -->
        <div class="col-span-1 bg-white p-6 rounded-md md:border-l border-l-0">
            <div class="search-box mb-6">
                <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none">
            </div>

            <h2 class="text-xl font-semibold text-black border-l-4 pl-2 border-blue-500 mb-4">Categories</h2>
            <ul class="space-y-2">
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">National Stories</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">News & Events</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
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
    include '../components/links.php';
    include '../components/footer.php';
    include '../chatbot.php';
    ?>
</body>
