<?php
session_start();
include '../../conn.php';
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

function fetchAccreditations($conn) {
    // Include document fields in the SELECT query
    $query = "SELECT id, email, firstname, middlename, lastname, suffix, status, valid_until, pin, 
              dti_cert, tax_payer_id, bir_value_added, blacklist_cert, vat_payments, tax_clearance, 
              contract_details, accreditation_fee 
              FROM tbl_accreditation ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generatePIN() {
    return sprintf("%06d", mt_rand(0, 999999));
}

$error_message = '';
$success_message = ''; // For toast notifications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $email = trim($_POST['email']);
        $firstname = trim($_POST['firstname']);
        $middlename = trim($_POST['middlename']);
        $lastname = trim($_POST['lastname']);
        $suffix = trim($_POST['suffix']);
        $dti_cert = isset($_POST['dti_cert']) ? 1 : 0;
        $tax_payer_id = isset($_POST['tax_payer_id']) ? 1 : 0;
        $bir_value_added = isset($_POST['bir_value_added']) ? 1 : 0;
        $blacklist_cert = isset($_POST['blacklist_cert']) ? 1 : 0;
        $vat_payments = isset($_POST['vat_payments']) ? 1 : 0;
        $tax_clearance = isset($_POST['tax_clearance']) ? 1 : 0;
        $contract_details = isset($_POST['contract_details']) ? 1 : 0;
        $accreditation_fee = isset($_POST['accreditation_fee']) ? 1 : 0;
        $valid_until = date('Y-m-d', strtotime('+1 year'));

        if (empty($email) || empty($firstname) || empty($lastname)) {
            throw new Exception("Required fields cannot be empty");
        }

        // Determine status based on action
        if ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            // For edit action, update status based on documents
            $required_docs = [
                'dti_cert' => $dti_cert,
                'tax_payer_id' => $tax_payer_id,
                'bir_value_added' => $bir_value_added,
                'blacklist_cert' => $blacklist_cert,
                'vat_payments' => $vat_payments,
                'tax_clearance' => $tax_clearance,
                'contract_details' => $contract_details,
                'accreditation_fee' => $accreditation_fee
            ];
            $all_docs_provided = true;
            foreach ($required_docs as $doc => $value) {
                if ($value != 1) {
                    $all_docs_provided = false;
                    break;
                }
            }
            $status = $all_docs_provided ? 'verified' : 'pending';

            // Update query without changing the PIN
            $query = "UPDATE tbl_accreditation SET email = :email, firstname = :firstname, 
                     middlename = :middlename, lastname = :lastname, suffix = :suffix, status = :status,
                     dti_cert = :dti_cert, tax_payer_id = :tax_payer_id, bir_value_added = :bir_value_added,
                     blacklist_cert = :blacklist_cert, vat_payments = :vat_payments, tax_clearance = :tax_clearance,
                     contract_details = :contract_details, accreditation_fee = :accreditation_fee,
                     valid_until = :valid_until WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $success_message = "Record updated successfully!";
        } elseif ($_POST['action'] === 'update_password' && isset($_POST['id'])) {
            // Update only the password (hash with BCRYPT)
            if (empty($_POST['password'])) {
                throw new Exception("Password cannot be empty");
            }
            $hashedPassword = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash with BCRYPT
            $query = "UPDATE tbl_accreditation SET password = :password WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $success_message = "Password updated successfully!";
        } else {
            // Add new record (includes PIN and hashed password)
            if (empty($_POST['password'])) {
                throw new Exception("Password cannot be empty for new records");
            }
            $hashedPassword = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash with BCRYPT
            $pin = generatePIN();
            $status = 'not verified'; // Default status for new records
            $query = "INSERT INTO tbl_accreditation (email, firstname, middlename, lastname, suffix, status,
                     dti_cert, tax_payer_id, bir_value_added, blacklist_cert, vat_payments, tax_clearance,
                     contract_details, accreditation_fee, password, pin, valid_until)
                     VALUES (:email, :firstname, :middlename, :lastname, :suffix, :status,
                     :dti_cert, :tax_payer_id, :bir_value_added, :blacklist_cert, :vat_payments, :tax_clearance,
                     :contract_details, :accreditation_fee, :password, :pin, :valid_until)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':pin', $pin);
            $success_message = "Record added successfully!";
        }

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':dti_cert', $dti_cert, PDO::PARAM_BOOL);
        $stmt->bindParam(':tax_payer_id', $tax_payer_id, PDO::PARAM_BOOL);
        $stmt->bindParam(':bir_value_added', $bir_value_added, PDO::PARAM_BOOL);
        $stmt->bindParam(':blacklist_cert', $blacklist_cert, PDO::PARAM_BOOL);
        $stmt->bindParam(':vat_payments', $vat_payments, PDO::PARAM_BOOL);
        $stmt->bindParam(':tax_clearance', $tax_clearance, PDO::PARAM_BOOL);
        $stmt->bindParam(':contract_details', $contract_details, PDO::PARAM_BOOL);
        $stmt->bindParam(':accreditation_fee', $accreditation_fee, PDO::PARAM_BOOL);
        $stmt->bindParam(':valid_until', $valid_until);

        if ($stmt->execute()) {
            // No redirect; JavaScript will handle the toast and refresh
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Check for delete success message (from delete_accreditation.php)
if (isset($_GET['delete_success']) && $_GET['delete_success'] == '1') {
    $success_message = "Record deleted successfully!";
}

$accreditations = fetchAccreditations($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accreditation Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include '../navbar-s.php'; ?>
        
        <div class="flex-1 flex flex-col p-8">
            <!-- Toast Notification -->
            <div id="toast" class="fixed top-5 right-5 z-50 hidden">
                <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
                    <span id="toastMessage"></span>
                </div>
            </div>

            <main class="overflow-auto">
                <div class="rounded-lg shadow-3xl grid grid-cols-1 gap-6">
                    <div class="flex justify-between mt-5 mr-5">
                        <button onclick="openModal('add')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add New</button>
                        <input type="text" id="search" name="search" placeholder="Search by Name or Email"
                            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="overflow-x-auto rounded-lg shadow-xl">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                            <thead class="bg-[#002244] text-white">
                                <tr>
                                    <th class="py-4 px-6 text-left">ID</th>
                                    <th class="py-4 px-6 text-left">Email Address</th>
                                    <th class="py-4 px-6 text-left">Full Name</th>
                                    <th class="py-4 px-6 text-left">Status</th>
                                    <th class="py-4 px-6 text-left">Valid Until</th>
                                    <th class="py-4 px-6 text-left">PIN (Admin Only)</th>
                                    <th class="py-4 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="accreditationTableBody" class="divide-y divide-gray-200">
                                <?php $counter = 1; ?>
                                <?php foreach ($accreditations as $acc): ?>
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
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for updating password -->
    <div id="updatePasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Update Password</h2>
            <form id="updatePasswordForm" method="POST" class="space-y-4">
                <input type="hidden" name="id" id="passwordModalId">
                <input type="hidden" name="action" id="passwordModalAction" value="update_password">
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password *</label>
                    <input type="password" name="password" id="newPassword" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                    <button type="button" onclick="closePasswordModalUpdate()" class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="accreditationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 id="modalTitle" class="text-2xl font-bold mb-4 text-center"></h2>
            <?php if ($error_message): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form id="accreditationForm" method="POST" class="space-y-4">
                <input type="hidden" name="id" id="modalId">
                <input type="hidden" name="action" id="modalAction">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="firstname" id="modalFirstName" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" name="middlename" id="modalMiddleName" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="lastname" id="modalLastName" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suffix</label>
                        <input type="text" name="suffix" id="modalSuffix" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address *</label>
                        <input type="email" name="email" id="modalEmail" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                    <div id="passwordField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Password *</label>
                        <input type="password" name="password" id="modalPassword" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Documents (Check if provided)</label>
                    <div class="grid grid-cols-1 gap-2 p-2 bg-gray-50 rounded-lg">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dti_cert" id="modalDtiCert" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">DTI/SEC Certificate</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="tax_payer_id" id="modalTaxPayerId" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Tax Payer's Identification Number</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="bir_value_added" id="modalBirValueAdded" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">BIR Value Added Tax Registration</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="blacklist_cert" id="modalBlacklistCert" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Blacklist Certificate</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="vat_payments" id="modalVatPayments" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Proof of VAT Payments (last 6 months)</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="tax_clearance" id="modalTaxClearance" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Tax Clearance (last 2 quarters)</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="contract_details" id="modalContractDetails" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Contract Details</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="accreditation_fee" id="modalAccreditationFee" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Accreditation Fee (Php 1,000)</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search");
        const tableBody = document.getElementById("accreditationTableBody");

        function fetchAccreditations(searchValue = '') {
            fetch(`search_accreditation.php?search=${encodeURIComponent(searchValue)}`)
                .then(response => response.text())
                .then(data => {
                    tableBody.innerHTML = data;
                })
                .catch(error => console.error("Error fetching search results:", error));
        }

        fetchAccreditations();
        searchInput.addEventListener("input", function () {
            fetchAccreditations(searchInput.value.trim());
        });

        // Show toast notification if there's a success message
        <?php if ($success_message): ?>
            showToast(<?php echo json_encode($success_message); ?>);
        <?php endif; ?>
    });

    function openModal(action, data = {}) {
        console.log("Data received in openModal:", data); // Debug the data

        const modal = document.getElementById("accreditationModal");
        const title = document.getElementById("modalTitle");
        const passwordField = document.getElementById("passwordField");
        const modalPassword = document.getElementById("modalPassword");
        
        document.getElementById("modalId").value = data.id || '';
        document.getElementById("modalAction").value = action;
        document.getElementById("modalEmail").value = data.email || '';
        document.getElementById("modalFirstName").value = data.firstname || '';
        document.getElementById("modalMiddleName").value = data.middlename || '';
        document.getElementById("modalLastName").value = data.lastname || '';
        document.getElementById("modalSuffix").value = data.suffix || '';

        // Ensure the values are treated as numbers and set checkbox states
        document.getElementById("modalDtiCert").checked = Number(data.dti_cert) === 1;
        document.getElementById("modalTaxPayerId").checked = Number(data.tax_payer_id) === 1;
        document.getElementById("modalBirValueAdded").checked = Number(data.bir_value_added) === 1;
        document.getElementById("modalBlacklistCert").checked = Number(data.blacklist_cert) === 1;
        document.getElementById("modalVatPayments").checked = Number(data.vat_payments) === 1;
        document.getElementById("modalTaxClearance").checked = Number(data.tax_clearance) === 1;
        document.getElementById("modalContractDetails").checked = Number(data.contract_details) === 1;
        document.getElementById("modalAccreditationFee").checked = Number(data.accreditation_fee) === 1;

        // Show password field only for 'add' action
        if (action === 'add') {
            passwordField.classList.remove('hidden');
            modalPassword.setAttribute('required', 'required');
        } else {
            passwordField.classList.add('hidden');
            modalPassword.removeAttribute('required');
        }
        
        title.textContent = action === 'edit' ? 'Edit Accreditation' : 'Add New Accreditation';
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById("accreditationModal").classList.add('hidden');
        document.getElementById("accreditationForm").reset();
    }

    function openPasswordModal(id) {
        const modal = document.getElementById("updatePasswordModal");
        document.getElementById("passwordModalId").value = id;
        modal.classList.remove('hidden');
    }

    function closePasswordModalUpdate() {
        document.getElementById("updatePasswordModal").classList.add('hidden');
        document.getElementById("updatePasswordForm").reset();
    }

    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this accreditation?")) {
            window.location.href = `delete_accreditation.php?id=${id}&delete_success=1`;
        }
    }

    // Toast notification function
    function showToast(message) {
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toastMessage");
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
            // Refresh the page after the toast disappears
            window.location.href = 'accreditation.php';
        }, 2000); // Toast disappears after 2 seconds
    }

    // Handle form submission for add/edit and update password
    document.getElementById("accreditationForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('accreditation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            closeModal();
            const action = formData.get('action');
            if (action === 'edit') {
                showToast("Record updated successfully!");
            } else {
                showToast("Record added successfully!");
            }
        })
        .catch(error => console.error("Error:", error));
    });

    document.getElementById("updatePasswordForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('accreditation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            closePasswordModalUpdate();
            showToast("Password updated successfully!");
        })
        .catch(error => console.error("Error:", error));
    });
    </script>
</body>
</html>