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
    <title>Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">

<!-- Header Image -->  
<div class="w-full">  
        <img src="power.jpg" alt="Header Image" class="w-full h-64 object-cover rounded-md ">  
    </div>

    <div class="container mx-auto py-6 flex flex-col md:flex-row md:space-x-4">

        <!-- Main Content Section -->
        <div class="order-1 md:order-2 w-full md:w-3/5 bg-white p-6 rounded-md">
            <h2 class="text-2xl font-bold text-[#87CEEB] mb-4">THE MANAGEMENT</h2>
            <hr class="border-t-4 border-b-4 border-[ffdb19] mt-1 mb-8">
            <ol class="list-decimal pl-5 text-gray-700 space-y-3">

            
            <div class="grid grid-cols-2 gap-16">  
        <?php  
        $teamMembers = [  
            [  
                'name' => 'ENGR. GERARDO N. OLEDAN',  
                'title' => 'General Manager',  
                'image' => 'GM.jpg'  
            ],  
            [  
                'name' => 'MAUREEN D. NIERRA, CPA',  
                'title' => 'Internal Auditor',  
                'image' => 'Mam-Maureen.jpg'  
            ],  
            [  
                'name' => 'MA. LEIZYL Q. GARCIA',  
                'title' => 'Finance Services Department',  
                'image' => 'Mam-Liezyl.jpg'  
            ],  
            [  
                'name' => 'ALLAN JOSEPH S. BORRINAGA',  
                'title' => 'Institutional Services',  
                'image' => 'Sir-Allan.jpg'  
            ],  
            [  
                'name' => 'CARLITUS CAE B. CASINILLO',  
                'title' => 'Technical Services Department Manager',  
                'image' => 'Tsip-LItong.jpg'  
            ],  
            [  
                'name' => 'VACANT',  
                'title' => 'Regulatory and Compliance Officer',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'JEFERSON G. HOLOYOHOY',  
                'title' => 'System Administrator',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'JOHN M. MOCORRO',  
                'title' => 'WESM TRADER OFFICER',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'MICHAEL M. GABING',  
                'title' => 'Human Resource & Administration Supervisor',  
                'image' => 'Sir-Mike.jpg'  
            ],  
            [  
                'name' => 'ANN MARIE B. MERACAP',  
                'title' => 'Executive Secretary',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'ENGR. EDRICH C. SACARE',  
                'title' => 'Operational, Maintenance and Special Equipment Supervisor',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'MA. ROSARIO R. BANTOLA',  
                'title' => 'Accountant',  
                'image' => 'Mam-Lotlot.jpg'  
            ],  
            [  
                'name' => 'RAZEL Q. CASAS',  
                'title' => 'Meter Reading, Billing & Collection (MRBC) Supervisor',  
                'image' => 'Mam-Raz.jpg'  
            ],  
            [  
                'name' => 'VACANT',  
                'title' => 'Area 1 Engineer',  
                'image' => 'logo.png'  
            ],  
            [  
                'name' => 'LUCRECIO S. VIDAL, REE',  
                'title' => 'Area 2 Engineer',  
                'image' => 'Engr-Vidal.jpg'  
            ],  
        ];  

        foreach ($teamMembers as $member) {  
            echo '<div class="flex flex-col items-center text-center  ">';  
            echo '<img src="' . $member['image'] . '" alt="' . $member['name'] . '" class=" w-40 h-40 object-cover">';  
            echo '<h2 class="text-lg font-semibold text-blue-600">' . $member['name'] . '</h2>';  
            echo '<p class="text-gray-700">' . $member['title'] . '</p>';  
            echo '</div>';  
        }  
        ?>  
   

       </div>
    </div>
        <!-- Left Sidebar Section -->
        <!-- Left Sidebar Section -->
        <div class="order-2 md:order-1 w-full md:w-1/5 bg-white border-r p-6 rounded-md md:pt-20">
            <ul class="space-y-8 text-right font-bold">
                <li><a href="<?php echo BASE_URL; ?>user/history.php" class="text-black hover:text-blue-800 text-sm">Brief History</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/vision.php" class="text-black hover:text-blue-800 text-sm">Vision, Mission, & Values</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/logo.php" class="text-black hover:text-blue-800 text-sm">Our Logo</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/board.php" class="text-black hover:text-blue-800 text-sm">The Board of Directors</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/management.php" class="text-black hover:text-blue-800 text-sm">The Management</a></li>
                <li><a href="<?php echo BASE_URL; ?>user/Francise.php" class="text-black hover:text-blue-800 text-sm">Franchise Area</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Best Practices</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Awards & Citations</a></li>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Power Sources</a></li>
            </ul>
        </div>

        <!-- Right Sidebar Section -->
        <div class="order-3 md:order-3 w-full md:w-1/5 bg-white p-6 rounded-md border-l">
            <div class="search-box mb-6">
                <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none">
            </div>

            <h2 class="text-xl font-semibold text-black border-l-4 pl-2 border-blue-500 mb-4">Categories</h2>
            <ul class="space-y-2">
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Announcements</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Bids & Awards</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">CSR Programs</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Generation Mix</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Maintenance Schedule</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">National Stories</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">News & Events</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Power Rate</a></li>
                <hr>
                <li><a href="#" class="text-black hover:text-blue-800 text-sm">Uncategorized</a></li>
                <hr>
            </ul>

            <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
            <ul>
                <!-- Add archive links here -->
            </ul>
        </div>

    </div>
   

<?php
include '../components/footer.php';
?>

</body>
</html>