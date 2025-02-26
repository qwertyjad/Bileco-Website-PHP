<?php
function fetchUsers($conn) {
    $query = "SELECT id, accountnum, firstname, middlename, lastname, status 
              FROM tbl_users 
              WHERE role NOT IN ('admin', 's_admin') 
              AND verified = 1";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
