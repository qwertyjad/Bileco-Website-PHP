<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteUncategorized($id);
    
    if ($success) {
        $_SESSION['msg'] = "Uncategorized entry deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete Uncategorized entry.";
    }
}

header("Location: uncategorized.php");
exit();
?>