<?php
session_start();
include '../../conn.php'; // Database connection
include '../../function.php'; // Functions class
include '../../components/header.php';

// Instantiate database connection and functions class
$database = new conn();
$conn = $database->conn;
$function = new Functions();

// Validate maintenance ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid maintenance schedule ID.'); window.location.href='maintenance.php';</script>";
    exit();
}

$maintenance_id = $_GET['id'];
$maintenance = $function->getMaintenanceById($maintenance_id);

$maintenance_id = isset($maintenance_id) ? intval($maintenance_id) : 0;
$comment_count = $function->getCommentCount($maintenance_id);

// Fetch previous and next posts
$prevPost = $function->getPreviousPost($maintenance_id);
$nextPost = $function->getNextPost($maintenance_id);

// Fetch related posts based on category
$relatedPosts = $function->getRelatedPosts($maintenance_id);

// Fetch super admin first name
$superAdminQuery = "SELECT firstname FROM tbl_users WHERE role = 's_admin' LIMIT 1";
$superAdminStmt = $conn->prepare($superAdminQuery);
$superAdminStmt->execute();
$superAdminFirstName = $superAdminStmt->fetchColumn();

// If maintenance not found, redirect
if (!$maintenance) {
    echo "<script>alert('Maintenance Schedule not found.'); window.location.href='maintenance.php';</script>";
    exit();
}

// Check user session
$user_status = isset($_SESSION['user_id']) ? 'online' : 'offline';
$user_status === 'online' ? include '../../components/navbar-u.php' : include '../../components/navbar.php';
?>

<title><?php echo htmlspecialchars($maintenance['title']); ?></title>

<body class="bg-white">
    <div class="container mx-auto py-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Main Content Section -->
        <div class="col-span-3 bg-white p-6 rounded-md">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-4">
                <a href="maintenance.php" class="text-blue-600 hover:underline">Maintenance Schedule</a> >
                <span class="text-gray-500"><?php echo htmlspecialchars($maintenance['title']); ?></span>
            </nav>

            <!-- Featured Image -->
            <?php if (!empty($maintenance['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($maintenance['image']); ?>"
                    class="w-full h-auto object-cover rounded-md shadow-md mb-6">
            <?php endif; ?>

            <!-- Maintenance Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <?php echo htmlspecialchars($maintenance['title']); ?>
            </h1>
            <hr class="m-5">

            <!-- Meta Data -->
            <div class="text-gray-500 text-sm p-5 flex items-center space-x-4">
                <span><i class="bx bx-user"></i> <?php echo htmlspecialchars($superAdminFirstName); ?></span>
                <span><i class="bx bx-calendar"></i> <?php echo date("F d, Y", strtotime($maintenance['date'])); ?></span>
                <span><i class="bx bx-comment"></i> <?php echo $comment_count; ?> Comments</span>
            </div>
            <hr class="mb-5">

            <!-- Social Share Buttons -->
            <div class="flex space-x-4 mb-6">
                <a href="#" class="text-blue-500 hover:text-blue-600"><i class="bx bxl-facebook-square text-2xl"></i></a>
                <a href="#" class="text-red-500 hover:text-red-600"><i class="bx bxl-pinterest text-2xl"></i></a>
                <a href="#" class="text-blue-400 hover:text-blue-500"><i class="bx bxl-twitter text-2xl"></i></a>
                <a href="#" class="text-gray-700 hover:text-gray-800"><i class="bx bxl-linkedin text-2xl"></i></a>
            </div>

            <!-- Maintenance Content -->
            <div class="text-gray-700 text-base leading-relaxed mb-6 text-justify">
                <?php echo nl2br(htmlspecialchars($maintenance['content'])); ?>
            </div>

            <!-- Previous & Next Post Navigation -->
            <div class="flex justify-between border-t pt-4">
                <?php if ($prevPost): ?>
                    <a href="maintenance-details.php?id=<?php echo $prevPost['id']; ?>" class="text-blue-600 hover:underline">
                        <i class="bx bx-left-arrow-alt"></i> Previous
                    </a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>

                <?php if ($nextPost): ?>
                    <a href="maintenance-details.php?id=<?php echo $nextPost['id']; ?>" class="text-blue-600 hover:underline">
                        Next <i class="bx bx-right-arrow-alt"></i>
                    </a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
            </div>

            <!-- "You Might Also Like" Section -->
            <h2 class="text-xl font-semibold mt-8 mb-4">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($relatedPosts as $post): ?>
                    <a href="maintenance-details.php?id=<?php echo $post['id']; ?>" class="block bg-white p-4 rounded-md shadow-md hover:shadow-lg transition-shadow">
                        <div>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" class="w-full h-32 object-cover rounded-md mb-2">
                            <p class="text-blue-600 hover:underline truncate">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Comment Section -->
            <?php if ($user_status === 'online'): ?>
                <div class="mt-10 p-5 bg-gray-100 rounded-md">
                    <h2 class="text-xl font-semibold mb-4">Leave a Comment</h2>
                    <form action="submit_comment.php" method="POST">
                        <input type="hidden" name="maintenance_id" value="<?php echo $maintenance_id; ?>">
                        <textarea name="comment" class="w-full p-3 border rounded-md focus:outline-none" placeholder="Write your comment..." required></textarea>
                        <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Submit</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Toast Notification -->
            <?php if (isset($_SESSION['toast'])): ?>
                <div id="toast" class="fixed bottom-5 right-5 px-4 py-2 rounded-md text-white
                    <?php echo $_SESSION['toast']['status'] === 'success' ? 'bg-green-500' : 'bg-red-500'; ?>">
                    <span><?php echo $_SESSION['toast']['message']; ?></span>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('toast').style.display = 'none';
                    }, 3000);
                </script>
                <?php unset($_SESSION['toast']); ?> <!-- Remove toast after displaying -->
            <?php endif; ?>

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