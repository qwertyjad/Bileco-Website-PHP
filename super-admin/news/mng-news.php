<?php
session_start();
include '../../conn.php';
include '../../function.php';

$function = new Functions();

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch total news count
$totalNews = count($function->getAllNews());
$totalPages = ceil($totalNews / $limit);

// Fetch news for the current page
$newsList = $function->getAllNews($limit, $page, $offset);
?>

<title>Manage News & Events</title>

<!-- Include Boxicons -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<div class="flex flex-col min-h-screen bg-gray-100">
    <!-- Sidebar (Desktop) -->
    <div class="h-screen bg-[#002244] fixed top-0 left-0 shadow-md">
        <?php include '../navbar-s.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64 p-4 pb-20">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class='bx bx-news text-blue-600 text-2xl mr-2'></i> Manage News
            </h2>

            <!-- Session Messages -->
            <?php if(isset($_SESSION['msg'])): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md border border-green-300">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <a href="add-edit-news.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md font-medium flex items-center w-fit">
                <i class='bx bx-plus text-lg mr-1'></i> Add News
            </a>

            <!-- News Table (Responsive) -->
            <div class="overflow-x-auto mt-6">
                <table class="w-full bg-white border border-gray-200 rounded-lg shadow-lg text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-left">
                            <th class="py-3 px-4 text-center"><i class='bx bx-image'></i></th>
                            <th class="py-3 px-4"><i class='bx bx-news'></i> Title</th>
                            <th class="py-3 px-4"><i class='bx bx-calendar'></i> Date</th>
                            <th class="py-3 px-4 text-center"><i class='bx bx-cog'></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($newsList)): ?>
                            <?php foreach ($newsList as $news): ?>
                                <tr class="border-b hover:bg-gray-100 transition-all">
                                    <td class="py-3 px-4 text-center">
                                        <?php if (!empty($news['image'])): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" class="w-14 h-14 object-cover rounded-md shadow-md">
                                        <?php else: ?>
                                            <i class='bx bx-image text-gray-400 text-2xl'></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-800 truncate max-w-[200px]">
                                        <?php echo htmlspecialchars($news['title']); ?>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 text-sm">
                                        <?php echo date("M d, Y", strtotime($news['date'])); ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="add-edit-news.php?id=<?php echo $news['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg shadow-md transition-all">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <a href="delete-news.php?id=<?php echo $news['id']; ?>" onclick="return confirm('Are you sure?');" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg shadow-md transition-all">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-center text-gray-500">No news available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center space-x-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="mng-news.php?page=<?php echo $i; ?>" class="px-4 py-2 border rounded-lg text-sm font-medium shadow-md transition-all 
                    <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>
