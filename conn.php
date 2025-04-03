<?php


// Prevent redefinition of the BASE_URL constant
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/crud/');

}

// Prevent class redeclaration
if (!class_exists('conn')) {
    class conn {
        public $conn;

        public function __construct() {
            // Database Configuration
            $servername = "localhost";
            $username = "root";  // Update for production
            $password = "";      // Update for production
            $dbname = "bileco_db";

            try {
                $this->conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Database Connection Failed: " . $e->getMessage());
                die("Database connection failed!"); // Hide error details from users
            }
        }
    }
}

?>

<!-- Favicon -->
<link rel="icon" href="./assets/favicon.ico" type="image/x-icon">

<!-- Scripts and Styles -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
