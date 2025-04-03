<?php
session_start();
include '../conn.php'; // Database connection
include '../function.php'; // Functions class

$database = new conn();
$conn = $database->conn;
$function = new Functions();

$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if (!empty($search_query)) {
    $stmt = $conn->prepare("SELECT id, title, content, image, date FROM news WHERE title LIKE ? ORDER BY date DESC");
    $likeQuery = "%$search_query%";
    $stmt->execute([$likeQuery]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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
        $user_status = $stmt->fetchColumn() ?: 'offline';
    } elseif ($user_type === 'tbl_accreditation') {
        $query = "SELECT online_status FROM tbl_accreditation WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn() ?? 'offline'; // Only online/offline status
    } else {
        $user_status = 'offline';
    }
} else {
    $user_status = 'offline';
}

// Include header and navigation bar based on user status
include '../components/header.php';
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../components/navbar-u.php'; // Navbar for other logged-in users
    }
} else {
    include '../components/navbar.php'; // Navbar for guests or offline users
}

// Fetch super admin first name for consistency with news pages
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
    <title>Search Results</title>
</head>
<body class="bg-white">
    <div class="container mx-auto py-6">
        <div class="bg-white p-6 rounded-md">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Search Results</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $news): ?>
                        <div class="bg-gray-100 p-4 rounded-md shadow-md flex flex-col h-full">
                            <!-- News Image -->
                            <?php if (!empty($news['image'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" 
                                     class="w-full h-48 object-cover rounded-md mb-3">
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
                            <a href="news-details.php?id=<?php echo $news['id']; ?>" 
                               class="text-blue-600 hover:underline font-medium mt-auto">
                                Read More →
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">No results found for "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("search")?.addEventListener("input", function() {
            clearTimeout(this.dataset.timer);
            this.dataset.timer = setTimeout(() => {
                this.form.submit();
            }, 500); // Debounce search to reduce form submissions
        });
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>