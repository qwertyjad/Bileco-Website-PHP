<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteBidsAwards($id);
    
    if ($success) {
        $_SESSION['msg'] = "Bids/Awards deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete bids/awards.";
    }
}

header("Location: bids-awards.php");
exit();
?>