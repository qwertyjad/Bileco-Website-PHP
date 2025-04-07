<?php
session_start();
include '../conn.php'; // Include database connection class
include '../function.php'; // Include Functions class
include '../components/header.php';

// Instantiate database connection
$database = new conn();
$conn = $database->conn;

// Pagination settings
$limit = 9; // Number of news per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page
$offset = ($page - 1) * $limit; // Calculate the offset

// Instantiate function class
$function = new Functions();
$newsList = $function->getPaginatedNews($limit, $offset);
$totalNews = $function->getNewsCount();
$totalPages = ceil($totalNews / $limit);

// Check if user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    // Fetch user status based on user type
    if ($user_type === 'tbl_users') {
        $query = "SELECT status FROM tbl_users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn();
    } elseif ($user_type === 'tbl_accreditation') {
        $query = "SELECT online_status FROM tbl_accreditation WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn() ?? 'offline'; // Only online/offline status
    } else {
        $user_status = 'offline'; // Fallback for invalid user_type
    }
} else {
    $user_status = 'offline'; // Default status for guests
}

// Display appropriate navbar
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../components/navbar-u.php'; // Navbar for other logged-in users
    }
} else {
    include '../components/navbar.php'; // Navbar for guests or offline users
}

// Fetch super admin first name
$superAdminQuery = "SELECT firstname FROM tbl_users WHERE role = 's_admin' LIMIT 1";
$superAdminStmt = $conn->prepare($superAdminQuery);
$superAdminStmt->execute();
$superAdminFirstName = $superAdminStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
</head>
<body class="bg-white">
    <div class="container mx-auto py-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Main Content Section -->
        <div class="col-span-3 bg-white p-6 rounded-md">
            <h2 class="text-3xl font-bold text-[#87CEEB] mb-4">NEWS & EVENTS</h2>
            <hr class="border-t-4 border-b-4 border-[#ffdb19] mt-1 mb-8">

            <!-- News List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($newsList)): ?>
                    <?php foreach ($newsList as $news): ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow-md flex flex-col h-full">
                            <!-- News Image -->
                            <?php if (!empty($news['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" class="w-full h-48 object-cover rounded-md mb-3">
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

            <!-- Pagination -->
            <div class="mt-6 flex justify-center space-x-4">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo ($page - 1); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="px-4 py-2 border <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-100'; ?> rounded-md hover:bg-blue-600">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo ($page + 1); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Next
                    </a>
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
                <li><a href="<?php echo BASE_URL; ?>user/categories/announcement.php" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/bids-awards.php" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/csr-programs.php" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/generation-mix.php" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/maintenance.php" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/national-stories.php" class="text-black hover:text-blue-800 text-sm">National Stories</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/news.php" class="text-black hover:text-blue-800 text-sm font-bold">News & Events</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/power-rate.php" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/categories/uncategorized.php" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
            </ul>
            <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
            <ul class="space-y-2">
                <?php
                $archives = $function->getArchives();
                if (!empty($archives)) {
                    foreach ($archives as $archive) {
                        echo '<li><a href="archives.php?date=' . $archive['archive_link'] . '" class="text-blue-600 hover:underline">' . $archive['archive_date'] . '</a></li>';
                    }
                } else {
                    echo '<li class="text-gray-500">No archives available</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <?php
    include '../chatbot.php';
    include '../components/links.php';
    include '../components/footer.php';
    ?>
</body>
</html>