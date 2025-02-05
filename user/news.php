<?php
include '../conn.php';
include '../components/header.php';
include '../components/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Header Image -->
    <?php
        echo '<div class="header-image w-full h-[250px] overflow-hidden">';
        echo '<img src="' . 'https://images.unsplash.com/photo-1534504969382-fec3d9ffdd73?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDE4fHx8ZW58MHx8fHx8' . '" alt="Bridge" class="w-full h-full object-cover" />';
        echo '</div>';
    ?>
       

<?php
    include '../components/links.php';
    include '../components/footer.php';
    ?>
</body>
</html>