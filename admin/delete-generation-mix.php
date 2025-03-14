<?php
session_start();
include '../conn.php';
include '../function.php';

$functions = new Functions();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $functions->deleteGenerationMix($id);
    
    if ($success) {
        $_SESSION['msg'] = "Generation Mix deleted successfully!";
    } else {
        $_SESSION['msg'] = "Failed to delete Generation Mix.";
    }
}

header("Location: generation-mix.php");
exit();
?>