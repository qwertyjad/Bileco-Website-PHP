<?php
session_start();
include '../../conn.php';
$database = new conn();
$conn = $database->conn;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access";
    exit();
}

// Get user role
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM tbl_users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_role = $stmt->fetchColumn();

// Search logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT id, email, firstname, middlename, lastname, suffix, status, valid_until, pin, 
          dti_cert, tax_payer_id, bir_value_added, blacklist_cert, vat_payments, tax_clearance, 
          contract_details, accreditation_fee 
          FROM tbl_accreditation WHERE 
          email LIKE :search OR 
          firstname LIKE :search OR 
          middlename LIKE :search OR 
          lastname LIKE :search OR 
          suffix LIKE :search";
$stmt = $conn->prepare($query);
$search_param = "%$search%";
$stmt->bindParam(':search', $search_param);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$counter = 1;
foreach ($results as $acc) {
    ?>
    <tr class="hover:bg-gray-50 transition duration-200">
        <td class="py-2 px-4 border-r"><?php echo $counter++; ?></td>
        <td class="py-2 px-4 border-r"><?php echo htmlspecialchars($acc['email']); ?></td>
        <td class="py-2 px-4 border-r">
            <?php 
                $fullName = "{$acc['firstname']} {$acc['middlename']} {$acc['lastname']}";
                if (!empty($acc['suffix'])) {
                    $fullName .= " {$acc['suffix']}";
                }
                echo htmlspecialchars($fullName);
            ?>
        </td>
        <td class="py-2 px-4 border-r">
            <span class="px-3 py-1 rounded-full text-sm font-semibold 
            <?php 
                if ($acc['status'] == 'verified') echo 'bg-green-200 text-green-800';
                elseif ($acc['status'] == 'pending') echo 'bg-yellow-200 text-yellow-800';
                else echo 'bg-red-200 text-red-800';
            ?>">
                <?php echo htmlspecialchars($acc['status']); ?>
            </span>
        </td>
        <td class="py-2 px-4 border-r"><?php echo htmlspecialchars($acc['valid_until']); ?></td>
        <td class="py-2 px-4 border-r"><?php echo ($user_role === 's_admin') ? htmlspecialchars($acc['pin']) : 'Hidden'; ?></td>
        <td class="py-2 px-4 flex gap-1">
            <button title="Edit" onclick='openModal("edit", <?php echo json_encode($acc); ?>)' 
                    class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">
                <i class="fas fa-pen"></i>
            </button>
            <button title="Update Password" onclick='openPasswordModal(<?php echo $acc['id']; ?>)' 
                    class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600">
                <i class="fas fa-key"></i>
            </button>
            <button title="Delete" onclick="confirmDelete(<?php echo $acc['id']; ?>)" 
                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    <?php
}
?>