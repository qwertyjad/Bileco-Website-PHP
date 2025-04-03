<?php
session_start();
include '../../conn.php';
include '../../function.php';
include '../../components/header.php';

// Instantiate the database connection class
$database = new conn();
$conn = $database->conn; // Get the PDO connection

$function = new Functions();
$newsList = $function->getAllNews();

// Check if the user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    // Fetch user status based on user type
    if ($user_type === 'tbl_users') {
        $query = "SELECT status FROM tbl_users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn(); // Fetch the status column (online/offline)
    } elseif ($user_type === 'tbl_accreditation') {
        $query = "SELECT online_status FROM tbl_accreditation WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_status = $stmt->fetchColumn(); // Fetch the online_status column (online/offline)
    } else {
        $user_status = 'offline'; // Fallback for invalid user_type
    }
} else {
    $user_status = 'offline'; // Default status for guests or if session data is missing
}

// Display the appropriate navbar
if ($user_status === 'online') {
    if ($user_type === 'tbl_accreditation') {
        include '../../components/navbar-accre.php'; // Navbar for accredited users
    } else {
        include '../../components/navbar-u.php'; // Navbar for other logged-in users (e.g., tbl_users)
    }
} else {
    include '../../components/navbar.php'; // Navbar for guests or offline users
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        /* Custom styles for the page */
        body {
            font-family: 'Arial', sans-serif;
        }
        .orgchart-container {
            min-height: 400px; /* Kept compact */
            width: 100%;
            margin: 0 auto;
            overflow-x: auto; /* Allow horizontal scrolling if needed */
        }
        /* Customize Google Charts nodes */
        .google-visualization-orgchart-node {
            border: 2px solid #87CEEB !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            background: #fff !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 8px !important; /* Kept reduced padding */
            width: 180px !important; /* Kept reduced width */
            min-height: 140px !important; /* Slightly increased height to accommodate wrapped text */
        }
        .google-visualization-orgchart-node:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2) !important;
        }
        .google-visualization-orgchart-nodetext {
            font-size: 0.75rem !important; /* Kept reduced font size */
            color: #1E3A8A !important;
            font-weight: bold !important;
        }
        .google-visualization-orgchart-line {
            border-color: #87CEEB !important;
        }
        /* Style the image inside nodes */
        .orgchart-image {
            width: 50px; /* Kept reduced image size */
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ffdb19;
            margin: 0 auto 6px; /* Kept reduced bottom margin */
            display: block;
        }
        /* Name styling */
        .orgchart-name {
            font-size: 0.7rem; /* Further reduced font size */
            color: #1E3A8A;
            font-weight: bold;
            white-space: normal; /* Allow text wrapping */
            overflow-wrap: break-word; /* Ensure long words break */
            text-align: center;
            line-height: 1.1; /* Adjusted line height for better spacing */
            max-height: 3.3rem; /* Allow up to 3 rows (line-height * 3) */
            overflow: hidden; /* Hide overflow beyond 3 rows */
        }
        .orgchart-title {
            font-size: 0.6rem; /* Further reduced font size */
            color: #4B5563;
            white-space: normal; /* Allow text wrapping */
            overflow-wrap: break-word; /* Ensure long words break */
            text-align: center;
            line-height: 1.1; /* Adjusted line height */
            max-height: 3.3rem; /* Allow up to 3 rows (line-height * 3) */
            overflow: hidden; /* Hide overflow beyond 3 rows */
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .google-visualization-orgchart-node {
                width: 140px !important; /* Kept reduced for mobile */
                min-height: 120px !important; /* Adjusted for mobile */
            }
            .orgchart-image {
                width: 40px; /* Kept reduced for mobile */
                height: 40px;
            }
            .orgchart-name {
                font-size: 0.65rem; /* Further reduced for mobile */
                max-height: 3rem; /* Adjusted for smaller font size */
            }
            .orgchart-title {
                font-size: 0.55rem; /* Further reduced for mobile */
                max-height: 3rem; /* Adjusted for smaller font size */
            }
        }
    </style>
</head>
<body class="bg-white">

<!-- Header Image -->
<div class="w-full">
    <img src="<?php echo BASE_URL; ?>assets/images/about/power.jpg" alt="Header Image" class="w-full h-64 object-cover rounded-md shadow-lg">
</div>

<div class="container mx-auto py-8 flex flex-col md:flex-row md:space-x-6">

    <!-- Main Content Section -->
    <div class="order-1 md:order-2 w-full md:w-3/5 p-8 rounded-lg">
        <h2 class="text-3xl font-bold text-[#87CEEB]">THE BOARD OF DIRECTORS</h2>
        <hr class="border-t-4 border-[#ffdb19] mb-10">

        <!-- Google Charts Container -->
        <div id="orgchart" class="orgchart-container"></div>

        <script type="text/javascript">
            // Load Google Charts
            google.charts.load('current', {packages: ['orgchart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                // Add rows for the organizational chart
                data.addRows([
                    // Board President
                    [
                        {
                            v: 'Wilberto',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Caridad.png" class="orgchart-image" /><div class="orgchart-name">DIR. WILBERTO B. CARIDAD</div><div class="orgchart-title">Board President<br>Kawayan District Representative</div></div>'
                        },
                        '',
                        'Board President'
                    ],
                    // Vice President
                    [
                        {
                            v: 'Cesar',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Cordeta.png" class="orgchart-image" /><div class="orgchart-name">DIR. CESAR D. CORDETA</div><div class="orgchart-title">Vice-President<br>Cabucgayan District Representative</div></div>'
                        },
                        'Wilberto',
                        'Vice-President'
                    ],
                    // Secretary
                    [
                        {
                            v: 'Salvacion',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Jornales.png" class="orgchart-image" /><div class="orgchart-name">DIR. SALVACION C. JORNALES</div><div class="orgchart-title">Secretary<br>Biliran District Representative</div></div>'
                        },
                        'Wilberto',
                        'Secretary'
                    ],
                    // Members under Vice President
                    [
                        {
                            v: 'Juan',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Avila.png" class="orgchart-image" /><div class="orgchart-name">DIR. JUAN R. AVILA JR.</div><div class="orgchart-title">Member<br>Caibiran District Representative</div></div>'
                        },
                        'Cesar',
                        'Member'
                    ],
                    [
                        {
                            v: 'Rodolfo',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Devio.png" class="orgchart-image" /><div class="orgchart-name">DIR. RODOLFO S. DEVIO</div><div class="orgchart-title">Member<br>Culaba District</div></div>'
                        },
                        'Cesar',
                        'Member'
                    ],
                    // Members under Secretary
                    [
                        {
                            v: 'Milagros',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Caneja.png" class="orgchart-image" /><div class="orgchart-name">DIR. MILAGROS O. CANEJA</div><div class="orgchart-title">Member<br>Naval District Representative</div></div>'
                        },
                        'Salvacion',
                        'Member'
                    ],
                    [
                        {
                            v: 'Pastor',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/Dir.Mecaydor.png" class="orgchart-image" /><div class="orgchart-name">DIR. PASTOR M. MECAYDOR</div><div class="orgchart-title">Member<br>Almeria District Representative</div></div>'
                        },
                        'Salvacion',
                        'Member'
                    ],
                    // General Manager
                    [
                        {
                            v: 'Gerardo',
                            f: '<div style="text-align:center;"><img src="<?php echo BASE_URL; ?>assets/images/about/GM.png" class="orgchart-image" /><div class="orgchart-name">ENGR. GERARDO N. OLEDAN</div><div class="orgchart-title">General Manager<br>Ex-Officio Member</div></div>'
                        },
                        'Salvacion',
                        'General Manager'
                    ]
                ]);

                // Create the chart
                var chart = new google.visualization.OrgChart(document.getElementById('orgchart'));

                // Draw the chart with options
                chart.draw(data, {
                    allowHtml: true,
                    nodeClass: 'google-visualization-orgchart-node',
                    allowCollapse: true,
                    size: 'small' // Kept small size for better spacing
                });
            }
        </script>
    </div>

    <!-- Left Sidebar Section -->
    <div class="order-2 md:order-1 w-full md:w-1/5 border-r p-6 rounded-lg">
        <ul class="space-y-4 text-right font-semibold">
            <li><a href="<?php echo BASE_URL; ?>user/about/history.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Brief History</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/vision.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Vision, Mission, & Values</a></li>
            <li><a href="<?php echo BASE_URL; ?>assets/images/about/logo.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Our Logo</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/board.php" class="text-blue-800 hover:text-blue-800 text-sm transition-colors">The Board of Directors</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/management.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">The Management</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/franchise.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Franchise Area</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/practices.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Best Practices</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/awards.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Awards & Citations</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/about/power.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Power Sources</a></li>
        </ul>
    </div>

    <!-- Right Sidebar Section -->
    <div class="order-3 md:order-3 w-full md:w-1/5 p-6 rounded-lg border-l">
        <div class="search-box mb-6">
            <input type="text" placeholder="Search" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mb-4">Categories</h2>
        <ul class="space-y-2">
            <li><a href="<?php echo BASE_URL; ?>user/categories/announcement.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Announcements</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/bids-awards.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Bids & Awards</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/csr-programs.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">CSR Programs</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/generation-mix.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Generation Mix</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/maintenance.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Maintenance Schedule</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/national-stories.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">National Stories</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/news.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">News & Events</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/power-rate.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Power Rate</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/categories/uncategorized.php" class="text-gray-700 hover:text-blue-800 text-sm transition-colors">Uncategorized</a></li>
        </ul>

        <h2 class="text-xl font-semibold text-gray-800 border-l-4 pl-2 border-blue-500 mt-8 mb-4">Archives</h2>
        <ul class="space-y-2">
            <?php
            $archives = $function->getArchives(); // Fetch archives from the database
            if (!empty($archives)) {
                foreach ($archives as $archive) {
                    echo '<li>
                            <a href="../archives.php?date=' . $archive['archive_link'] . '" class="text-blue-600 hover:underline">' . $archive['archive_date'] . '</a>
                        </li>';
                }
            } else {
                echo '<li class="text-gray-500">No archives available</li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php
include '../../components/links.php';
include '../../components/footer.php';
?>
</body>
</html>