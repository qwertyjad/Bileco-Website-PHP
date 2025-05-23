<?php
session_start();
include '../conn.php';
include '../function.php';

// Create Functions instance
$functions = new Functions();

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch bids and awards using Functions class (assuming we'll add these methods)
$bidsAwardsList = $functions->getPaginatedBidsAwards($limit, $offset);
$total = $functions->getBidsAwardsCount();
$totalPages = ceil($total / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bids and Awards</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex flex-col h-screen md:flex-row">
        <?php include 'navbar-a.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-auto">
            <main class="p-4 md:p-6">
                <div class="bg-white">
                    <div class="max-w-6xl mx-auto bg-white p-2 rounded-lg">
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class='bx bx-gavel text-blue-600 text-xl md:text-2xl mr-2'></i> Manage Bids and Awards
                        </h2>

                        <!-- Session Messages -->
                        <?php if(isset($_SESSION['msg'])): ?>
                            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md border border-green-300">
                                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                            </div>
                        <?php endif; ?>

                        <a href="add-edit-bids-awards.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md font-medium flex items-center w-fit">
                            <i class='bx bx-plus text-lg mr-1'></i> Add Bids/Awards
                        </a>

                        <!-- Bids and Awards Table -->
                        <div class="overflow-x-auto mt-6">
                            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-lg text-sm">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-700 text-left">
                                        <th class="py-3 px-4 text-center hidden sm:table-cell"><i class='bx bx-image'></i></th>
                                        <th class="py-3 px-4 text-center"><i class='bx bx-gavel'></i> Title</th>
                                        <th class="py-3 px-4 text-center"><i class='bx bx-calendar'></i> Date</th>
                                        <th class="py-3 px-4 text-center"><i class='bx bx-cog'></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($bidsAwardsList)): ?>
                                        <?php foreach ($bidsAwardsList as $item): ?>
                                            <tr class="border-b hover:bg-gray-100 transition-all">
                                                <td class="py-3 px-4 flex justify-center items-center hidden sm:table-cell">
                                                    <?php if (!empty($item['image'])): ?>
                                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" class="w-14 h-14 object-cover rounded-md shadow-md">
                                                    <?php else: ?>
                                                        <i class='bx bx-image text-gray-400 text-2xl'></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-4 font-medium text-gray-800 truncate max-w-[200px]">
                                                    <?php echo htmlspecialchars($item['title']); ?>
                                                </td>
                                                <td class="py-3 px-4 text-gray-600 text-sm text-center">
                                                    <?php echo date("M d, Y", strtotime($item['date'])); ?>
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <div class="flex justify-center space-x-2">
                                                        <a href="add-edit-bids-awards.php?id=<?php echo $item['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg shadow-md transition-all">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                        <a href="delete-bids-awards.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure?');" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg shadow-md transition-all">
                                                            <i class='bx bx-trash'></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="py-4 px-4 text-center text-gray-500">No bids or awards available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 flex justify-center space-x-2">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="bids-awards.php?page=<?php echo $i; ?>" class="px-4 py-2 border rounded-lg text-sm font-medium shadow-md transition-all
                                <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </main>

            <?php include '../components/footer.php'; ?>
        </div>
    </div>
</body>
</html>