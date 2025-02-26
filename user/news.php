<?php
session_start();
include '../conn.php'; // Include the database connection class
include '../function.php'; // Include Functions class
include '../components/header.php';

// Instantiate database connection
$database = new conn();
$conn = $database->conn;

// Fetch all news posts
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
    include '../components/navbar-u.php';
} else {
    include '../components/navbar.php';
}
?>

<title>News & Events</title>

<div class="header-image w-full h-[250px] overflow-hidden">
    <img src="https://images.unsplash.com/photo-1534504969382-fec3d9ffdd73?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDE4fHx8ZW58MHx8fHx8" alt="Bridge" class="w-full h-full object-cover">
</div>

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
                        <div class="bg-gray-100 p-4 rounded-md shadow-md">
                            <!-- News Image -->
                            <?php if (!empty($news['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" class="w-full h-48 object-cover rounded-md mb-3">
                            <?php else: ?>
                                <div class="w-full h-48 flex items-center justify-center bg-gray-200 rounded-md">
                                    <i class="bx bx-image text-5xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>

                            <!-- News Title -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($news['title']); ?>
                            </h3>

                            <!-- News Date -->
                            <p class="text-sm text-gray-500 mb-2">
                                <i class="bx bx-calendar"></i> <?php echo date("M d, Y", strtotime($news['date'])); ?>
                            </p>

                            <!-- Read More -->
                            <a href="news-details.php?id=<?php echo $news['id']; ?>" class="text-blue-600 hover:underline font-medium">
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
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Announcements</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">National Stories</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">News & Events</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li><hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li><hr>
            </ul>

            <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
            <ul>
                <!-- Add archive links here -->
            </ul>
        </div>
    </div>

    <?php
    include '../components/links.php';
    include '../components/footer.php';
    ?>
</body>
