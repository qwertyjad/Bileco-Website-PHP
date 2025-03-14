<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteNews($id);
    
    if ($success) {
        $_SESSION['msg'] = "News deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete News.";
    }
}

header("Location: index.php");
exit();
?>