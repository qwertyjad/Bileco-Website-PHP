<?php
include '../../conn.php';
include '../../function.php';

$function = new Functions();
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $function->deleteNews($id);
    $_SESSION['msg'] = "News deleted successfully!";
}

header('Location: mng-news.php');
exit();
