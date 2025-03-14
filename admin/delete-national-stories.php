<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteNationalStories($id);
    
    if ($success) {
        $_SESSION['msg'] = "National Story deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete National Story.";
    }
}

header("Location: national-stories.php");
exit();
?>