<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deletePowerRate($id);
    
    if ($success) {
        $_SESSION['msg'] = "Power Rate deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete Power Rate.";
    }
}

header("Location: power-rate.php");
exit();
?>