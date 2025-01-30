<?php
include 'conn.php';
include 'components/header.php';
include 'components/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BILECO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    

    <!-- Hero Section -->
    <section class="bg-blue-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold">Biliran Electric Cooperative, Inc.</h2>
            <p class="text-lg">Brgy. Caraycaray, Naval, Biliran</p>
            <p class="mt-4 italic">"We serve, because we care."</p>
        </div>
    </section>

    <!-- Vision, Mission, Core Values -->
    <section class="bg-gray-200 py-8">
        <div class="container mx-auto px-4 grid md:grid-cols-3 gap-4 text-center md:text-left">
            <div>
                <h3 class="text-xl font-semibold">Vision</h3>
                <p>An electric distribution utility recognized as a hallmark of excellence by providing premium customer satisfaction by 2030.</p>
            </div>
            <div>
                <h3 class="text-xl font-semibold">Mission</h3>
                <p>To provide reliable, safe, quality, and efficient electric service for a developed and progressive Biliran Province.</p>
            </div>
            <div>
                <h3 class="text-xl font-semibold">Core Values</h3>
                <p>Godliness | Discipline | Honesty | Excellence | Accountability | Respect | Teamwork</p>
            </div>
        </div>
    </section>

    <!-- Status of Electrification -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h3 class="text-2xl font-bold text-center mb-6">Status of Electrification</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <img src="https://via.placeholder.com/600x400" alt="Workers on electric lines" class="w-full rounded-lg shadow">
                </div>
                <div>
                    <p>BILECO has been adamant in fulfilling its mandate on total electrification within its franchise area. Since the start of its operation in 1983, BILECO made significant turns which eventually changed the landscape of electrification in Biliran...</p>
                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span>Barangay Energization</span>
                            <span class="font-bold">100%</span>
                        </div>
                        <div class="bg-gray-300 h-2 rounded-full">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 100%;"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Sitio Energization</span>
                            <span class="font-bold">92%</span>
                        </div>
                        <div class="bg-gray-300 h-2 rounded-full">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 92%;"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>House Connection</span>
                            <span class="font-bold">94%</span>
                        </div>
                        <div class="bg-gray-300 h-2 rounded-full">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 94%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News and Events -->
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h3 class="text-2xl font-bold text-center mb-6">News and Events</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-blue-900 text-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-bold mb-4">News and Events</h4>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <ul class="space-y-4">
                        <li><a href="#" class="text-blue-900 hover:underline">National Stories</a></li>
                        <li><a href="#" class="text-blue-900 hover:underline">Announcements</a></li>
                        <li><a href="#" class="text-blue-900 hover:underline">Scheduled Outages</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>
</body>
</html>
