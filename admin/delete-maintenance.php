<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteMaintenance($id);
    
    if ($success) {
        $_SESSION['msg'] = "Maintenance deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete Maintenance.";
    }
}

header("Location: maintenance.php");
exit();
?>