<?php
session_start();
include '../conn.php'; // Database connection
include '../function.php'; // Functions class
include '../components/header.php';

// Instantiate database connection and functions class
$database = new conn();
$conn = $database->conn;
$function = new Functions();

// Validate news ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid news ID.'); window.location.href='news.php';</script>";
    exit();
}

$news_id = $_GET['id'];
$news = $function->getNewsById($news_id);

$news_id = isset($news_id) ? intval($news_id) : 0;
$comment_count = $function->getCommentCount($news_id);

// Fetch previous and next posts
$prevPost = $function->getPreviousPost($news_id);
$nextPost = $function->getNextPost($news_id);

// Fetch related posts based on category
$relatedPosts = $function->getRelatedPosts($news_id);

// Fetch super admin first name
$superAdminQuery = "SELECT firstname FROM tbl_users WHERE role = 's_admin' LIMIT 1";
$superAdminStmt = $conn->prepare($superAdminQuery);
$superAdminStmt->execute();
$superAdminFirstName = $superAdminStmt->fetchColumn();

// If news not found, redirect
if (!$news) {
    echo "<script>alert('News not found.'); window.location.href='news.php';</script>";
    exit();
}

// Check user session
$user_status = isset($_SESSION['user_id']) ? 'online' : 'offline';
$user_status === 'online' ? include '../components/navbar-u.php' : include '../components/navbar.php';
?>

<title><?php echo htmlspecialchars($news['title']); ?></title>

<body class="bg-white">
    <div class="container mx-auto py-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Main Content Section -->
        <div class="col-span-3 bg-white p-6 rounded-md">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-4">
                <a href="news.php" class="text-blue-600 hover:underline">News & Events</a> >
                <span class="text-gray-500"><?php echo htmlspecialchars($news['title']); ?></span>
            </nav>

            <!-- Featured Image -->
            <?php if (!empty($news['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>"
                    class="w-full h-auto object-cover rounded-md shadow-md mb-6">
            <?php endif; ?>

            <!-- News Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <?php echo htmlspecialchars($news['title']); ?>
            </h1>
            <hr class="m-5">

            <!-- Meta Data -->
            <div class="text-gray-500 text-sm p-5 flex items-center space-x-4">
                <span><i class="bx bx-user"></i> <?php echo htmlspecialchars($superAdminFirstName); ?></span>
                <span><i class="bx bx-calendar"></i> <?php echo date("F d, Y", strtotime($news['date'])); ?></span>
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

            <!-- News Content -->
            <div class="text-gray-700 text-base leading-relaxed mb-6 text-justify">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>

            <!-- Previous & Next Post Navigation -->
            <div class="flex justify-between border-t pt-4">
                <?php if ($prevPost): ?>
                    <a href="news-details.php?id=<?php echo $prevPost['id']; ?>" class="text-blue-600 hover:underline">
                        <i class="bx bx-left-arrow-alt"></i> Previous
                    </a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>

                <?php if ($nextPost): ?>
                    <a href="news-details.php?id=<?php echo $nextPost['id']; ?>" class="text-blue-600 hover:underline">
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
        <a href="news-details.php?id=<?php echo $post['id']; ?>" class="block bg-white p-4 rounded-md shadow-md hover:shadow-lg transition-shadow">
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
                        <input type="hidden" name="news_id" value="<?php echo $news_id; ?>">
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
            <ul>
                <li><a href="#" class="hover:underline">February 2025</a></li>
                <li><a href="#" class="hover:underline">January 2025</a></li>
                <li><a href="#" class="hover:underline">December 2024</a></li>
                <li><a href="#" class="hover:underline">November 2024</a></li>
                <li><a href="#" class="hover:underline">October 2024</a></li>
            </ul>
        </div>
    </div>

    <?php
    include '../components/links.php';
    include '../components/footer.php';
    ?>
</body>
