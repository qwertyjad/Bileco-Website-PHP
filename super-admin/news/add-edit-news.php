<?php
include '../../conn.php';
include '../../function.php';
$function = new Functions();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$news = $id ? $function->getNewsById($id) : ['title' => '', 'content' => '', 'image' => '', 'date' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];
    $image = null;

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($id) {
        $function->updateNews($id, $title, $content, $date, $image);
        $_SESSION['msg'] = "News updated successfully!";
    } else {
        $function->addNews($title, $content, $date, $image);
        $_SESSION['msg'] = "News added successfully!";
    }

    header('Location: mng-news.php');
    exit();
}
?>

<title><?php echo $id ? 'Edit News' : 'Add News'; ?></title>
<div class="flex h-screen bg-gray-100">
    <?php include '../navbar-a.php'; ?>
    <div class="flex-1 overflow-y-auto p-6">
    
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-md shadow-md">
    <h2 class="text-xl font-semibold"><?php echo $id ? 'Edit' : 'Add'; ?> News</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700">Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" class="w-full p-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Content</label>
            <textarea name="content" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($news['content']); ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" value="<?php echo $news['date']; ?>" class="w-full p-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Image</label>
            <input type="file" name="image" class="w-full p-2 border rounded-lg">
            <?php if (!empty($news['image'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" class="w-32 h-32 mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md">
            <?php echo $id ? 'Update' : 'Add'; ?> News
        </button>
    </form>
</div>
    </div>
</div>