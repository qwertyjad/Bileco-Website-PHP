<?php
session_start();
include '../../conn.php';

$database = new conn();
$conn = $database->conn;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT id, accountnum, CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname, status 
          FROM tbl_users 
          WHERE (firstname LIKE :search OR middlename LIKE :search OR lastname LIKE :search OR accountnum LIKE :search) 
          AND role NOT IN ('admin', 's_admin')
          AND verified = 1";

$stmt = $conn->prepare($query);
$searchTerm = "%{$search}%";
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$counter = 1;
foreach ($users as $user): ?>
    <tr class="hover:bg-gray-50 transition duration-200">
        <td class="py-2 px-4 border-r"><?php echo $counter++; ?></td>
        <td class="py-2 px-4 border-r"><?php echo htmlspecialchars($user['accountnum']); ?></td>
        <td class="py-2 px-4 border-r"><?php echo htmlspecialchars($user['fullname']); ?></td>
        <td class="py-2 px-4 border-r">
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
            <?php echo ($user['status'] == 'online') ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
                <?php echo htmlspecialchars($user['status']); ?>
            </span>
        </td>
        <td class="py-2 px-4 flex gap-2">
            <!-- Edit Button -->
            <a href="edit-user.php?id=<?php echo $user['id']; ?>" 
               class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-yellow-600">
                Edit
            </a>

            <!-- Delete Button -->
            <button onclick="confirmDelete(<?php echo $user['id']; ?>)" 
                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">
                Delete
            </button>
        </td>
    </tr>
<?php endforeach; ?>
