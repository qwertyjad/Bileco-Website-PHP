<?php
$page_name = "Biliran Electric Cooperative, Inc.";
$page_description = "Biliran Electric Cooperative, Inc. is an electric distribution utility incorporated and registered.";
$page_logo =  "https://scontent.fceb1-1.fna.fbcdn.net/v/t39.30808-6/420142696_409554481419176_3506636455421123524_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeGuivNiq9Y7cBxkAUddHHAa3i62IT7doeneLrYhPt2h6TeYrWlc4Xn4ZG75tdlHBLsybwR7Ve_J0xug1540fbt2&_nc_ohc=KabCuO2JYoAQ7kNvgGzZnew&_nc_oc=AdidzTW0L0GLzhMFjQOie3RBuj6LF_qX4wOj8CunD9igFYj3UryWbffuc7KcoJmsDRw&_nc_zt=23&_nc_ht=scontent.fceb1-1.fna&_nc_gid=AGbfATG2cTT3uNvOWIRiJZf&oh=00_AYA0hBBTKwPOI1gXe9nNnm_MiD1rfzatqKZ-ugyu-I1G-w&oe=67B07240";; // Replace with actual logo path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow <?php echo $page_name; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-gray-800">Do You Want to Follow This Page?</h2>
        <div class="flex items-center mt-4">
            <img src="<?php echo $page_logo; ?>" alt="Page Logo" class="w-12 h-12 rounded-full border">
            <div class="ml-3">
                <h3 class="text-md font-semibold text-gray-900"><?php echo $page_name; ?></h3>
                <p class="text-sm text-gray-600"><?php echo $page_description; ?></p>
            </div>
        </div>
        <p class="text-sm text-gray-700 mt-4">Follow this Page to get updates in Feed.</p>
        <div class="mt-6 flex justify-end space-x-2">
    <button onclick="followPage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition">
        Follow
    </button>
    <button onclick="hideFollowPopup()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 focus:ring-2 focus:ring-gray-500 transition">
        Cancel
    </button>
</div>

<script>
    function followPage() {
        // Replace with the actual Facebook page URL
        window.location.href = "https://www.facebook.com/YOUR_PAGE_NAME";
    }

    function hideFollowPopup() {
        // Redirect to index.php
        window.location.href = "index.php";
    }
</script>

    </div>
</body>
</html>
