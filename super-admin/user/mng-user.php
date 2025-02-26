<?php
session_start();
include '../../conn.php';
include 'fetch-user.php'; // Separate file for fetching users

$database = new conn();
$conn = $database->conn;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_role = $stmt->fetchColumn();

if ($user_role !== 's_admin') {
    header("Location: ../../index.php");
    exit();
}

$users = fetchUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include '../navbar-s.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col p-8">
            <main class="overflow-auto">
                <div class="rounded-lg shadow-3xl grid grid-cols-1 gap-6">
                    
                   <!-- Search Form - Positioned at Top Right with 50% width -->
                    <div class="flex justify-end mt-5 mr-5">
                        <input type="text" id="search" name="search" placeholder="Search by Name or Account #"
                            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="overflow-x-auto rounded-lg shadow-xl">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                        <thead class="bg-[#002244] text-white">
                            <tr>
                                <th class="py-4 px-6 text-left">ID</th>
                                <th class="py-4 px-6 text-left">Account Number</th>
                                <th class="py-4 px-6 text-left">Full Name</th>
                                <th class="py-4 px-6 text-left">Status</th>
                                <th class="py-4 px-6 text-left">Actions</th>  <!-- New Actions Column -->
                            </tr>
                        </thead>

                        <tbody id="userTableBody" class="divide-y divide-gray-200">
                            <?php $counter = 1; ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="py-2 px-4 border-r"><?php echo $counter++; ?></td>
                                <td class="py-2 px-4 border-r"><?php echo htmlspecialchars($user['accountnum']); ?></td>
                                <td class="py-2 px-4 border-r">
                                    <?php echo htmlspecialchars("{$user['firstname']} {$user['middlename']} {$user['lastname']}"); ?>
                                </td>
                                <td class="py-2 px-4 border-r">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                    <?php echo ($user['status'] == 'online') ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
                                        <?php echo htmlspecialchars($user['status']); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-4 flex gap-1">
                                    <!-- Edit Button -->
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" 
                                    class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-yellow-600">
                                        Edit
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <button onclick="confirmDelete(<?php echo $user['id']; ?>)" 
                                            class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>

                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search");
        const tableBody = document.getElementById("userTableBody");

        function fetchUsers(searchValue = '') {
            fetch(`search.php?search=${encodeURIComponent(searchValue)}`)
                .then(response => response.text())
                .then(data => {
                    tableBody.innerHTML = data;
                })
                .catch(error => console.error("Error fetching search results:", error));
        }

        // Load users on page load
        fetchUsers();

        // Fetch users dynamically as user types
        searchInput.addEventListener("input", function () {
            fetchUsers(searchInput.value.trim());
        });
    });
    function confirmDelete(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = `delete-user.php?id=${userId}`;
    }
}

    </script>
</body>
</html>
