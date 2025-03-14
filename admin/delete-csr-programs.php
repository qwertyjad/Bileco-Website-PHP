<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteCSRPrograms($id);
    
    if ($success) {
        $_SESSION['msg'] = "CSR Program deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete CSR Program.";
    }
}

header("Location: csr-programs.php");
exit();
?>