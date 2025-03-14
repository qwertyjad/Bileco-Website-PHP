<?php
session_start();
include '../conn.php';
include '../function.php';

// Initialize Functions class
$functions = new Functions();

// Check if we're editing an existing item
$id = isset($_GET['id']) ? $_GET['id'] : null;
$item = $id ? $functions->getAnnouncementById($id) : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];
    $image = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($id) {
        // Update existing item
        $success = $functions->updateAnnouncement($id, $title, $content, $date, $image);
        $message = $success ? "Announcement updated successfully!" : "Failed to update Announcement.";
    } else {
        // Add new item
        $success = $functions->addAnnouncement($title, $content, $date, $image);
        $message = $success ? "Announcement added successfully!" : "Failed to add Announcement.";
    }

    if ($success) {
        $_SESSION['msg'] = $message;
        header("Location: announcement.php");
        exit();
    } else {
        $error = $message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col h-screen md:flex-row">
        <?php include 'navbar-a.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-auto">
            <main class="p-4 md:p-6">
                <div class="bg-white">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                            <i class='bx bx-bullhorn text-blue-600 text-xl md:text-2xl mr-2'></i>
                            <?php echo $id ? 'Edit' : 'Add'; ?> Announcement
                        </h2>

                        <!-- Error Message -->
                        <?php if (isset($error)): ?>
                            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md border border-red-300">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="title" required
                                    value="<?php echo $item ? htmlspecialchars($item['title']) : ''; ?>"
                                    class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="content" rows="4" required
                                    class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"><?php echo $item ? htmlspecialchars($item['content']) : ''; ?></textarea>
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" required
                                    value="<?php echo $item ? date('Y-m-d', strtotime($item['date'])) : date('Y-m-d'); ?>"
                                    class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                                <?php if ($item && $item['image']): ?>
                                    <div class="mb-2">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" 
                                            class="w-32 h-32 object-cover rounded-md shadow-md">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md font-medium flex items-center">
                                    <i class='bx bx-save text-lg mr-2'></i> Save
                                </button>
                                <a href="announcements.php" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md font-medium flex items-center">
                                    <i class='bx bx-arrow-back text-lg mr-2'></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>