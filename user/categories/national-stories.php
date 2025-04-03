<?php
session_start();
include '../../conn.php'; // Include database connection class
include '../../function.php'; // Include Functions class
include '../../components/header.php';

// Instantiate database connection
$database = new conn();
$conn = $database->conn;

// Fetch all national stories
$function = new Functions();
$nationalStoriesList = $function->getPaginatedNationalStories(9, 0); // Initial fetch for display
$totalNationalStories = $function->getNationalStoriesCount();
$totalPages = ceil($totalNationalStories / 9);

// Pagination settings
$limit = 9; // Number of national stories per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page
$offset = ($page - 1) * $limit; // Calculate the offset

// Fetch paginated national stories
$nationalStoriesList = $function->getPaginatedNationalStories($limit, $offset);

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
        $query = "SELECT online_status, status FROM tbl_accreditation WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_status = $result['online_status'] ?? 'offline'; // Online/offline status
        $accred_status = $result['status'] ?? 'not verified'; // Verification status
    } else {
        $user_status = 'offline'; // Fallback for invalid user_type
    }
} else {
    $user_status = 'offline'; // Default status for guests
    $accred_status = null; // No accreditation status for non-logged-in users
}

// Display appropriate navbar
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../../components/navbar-u.php'; // Navbar for other logged-in users
    }
} else {
    include '../../components/navbar.php'; // Navbar for guests or offline users
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
    <title>National Stories</title>
</head>
<body class="bg-white">
    <div class="container mx-auto py-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Main Content Section -->
        <div class="col-span-3 bg-white p-6 rounded-md">
            <h2 class="text-3xl font-bold text-[#87CEEB] mb-4">NATIONAL STORIES</h2>
            <hr class="border-t-4 border-b-4 border-[#ffdb19] mt-1 mb-8">

            <!-- National Stories List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($nationalStoriesList)): ?>
                    <?php foreach ($nationalStoriesList as $nationalStory): ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow-md flex flex-col h-full">
                            <!-- National Story Image -->
                            <?php if (!empty($nationalStory['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($nationalStory['image']); ?>" class="w-full h-48 object-cover rounded-md mb-3">
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
                                    <i class="bx bx-calendar"></i> <?php echo date("M d, Y", strtotime($nationalStory['date'])); ?>
                                </p>
                            </div>

                            <!-- National Story Title -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                <?php
                                $title = htmlspecialchars($nationalStory['title']);
                                echo strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;
                                ?>
                            </h3>

                            <!-- Read More -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($user_type === 'tbl_accreditation' && $accred_status === 'pending'): ?>
                                    <button class="text-blue-600 hover:underline font-medium mt-auto" onclick="openVerificationModal(<?php echo $nationalStory['id']; ?>)">
                                        Read More →
                                    </button>
                                <?php else: ?>
                                    <a href="national-stories-details.php?id=<?php echo $nationalStory['id']; ?>" class="text-blue-600 hover:underline font-medium mt-auto">
                                        Read More →
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="text-blue-600 hover:underline font-medium mt-auto" onclick="openLoginModal(<?php echo $nationalStory['id']; ?>)">
                                    Read More →
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">No national stories available at the moment.</p>
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
                <li><a href="announcement.php" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <li><a href="bids-awards.php" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <li><a href="csr-programs.php" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <li><a href="generation-mix.php" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <li><a href="maintenance.php" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <li><a href="national-stories.php" class="text-black hover:text-blue-800 text-sm font-bold">National Stories</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/news.php" class="text-black hover:text-blue-800 text-sm">News & Events</a></li>
                <li><a href="power-rate.php" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <li><a href="uncategorized.php" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
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

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-8 rounded-md shadow-md">
            <h2 class="text-2xl font-bold mb-4">Please Log In</h2>
            <p class="mb-4">You need to log in to continue reading.</p>
            <a id="loginLink" href="<?php echo BASE_URL; ?>auth/login.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Log In</a>
            <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mt-2" onclick="closeLoginModal()">Close</button>
        </div>
    </div>

    <!-- Verification Modal for Accredited Users -->
    <div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-8 rounded-md shadow-md">
            <h2 class="text-2xl font-bold mb-4">Verification Required</h2>
            <p class="mb-4">Please be fully verified first to continue.</p>
            <a href="<?php echo BASE_URL; ?>auth/verify_pin.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Verify Now</a>
            <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mt-2" onclick="closeVerificationModal()">Close</button>
        </div>
    </div>

    <script>
        function openLoginModal(nationalStoryId) {
            const loginLink = document.getElementById('loginLink');
            loginLink.href = '<?php echo BASE_URL; ?>auth/login.php?redirect=user/categories/national-stories-details.php?id=' + nationalStoryId;
            document.getElementById('loginModal').classList.remove('hidden');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        function openVerificationModal(nationalStoryId) {
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function closeVerificationModal() {
            document.getElementById('verificationModal').classList.add('hidden');
        }
    </script>

    <?php
    include '../../components/links.php';
    include '../../components/footer.php';
    ?>
</body>
</html>