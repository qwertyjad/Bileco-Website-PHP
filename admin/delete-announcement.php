<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteAnnouncement($id);
    
    if ($success) {
        $_SESSION['msg'] = "Announcement deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete announcement.";
    }
}

header("Location: announcement.php");
exit();
?>