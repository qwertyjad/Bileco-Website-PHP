<?php
include '../conn.php';
include '../web.php';
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
<body class="bg-gray-100 font-sans">
    <!-- Header Image -->
    <?php
        echo '<div class="header-image w-full h-[250px] overflow-hidden">';
        echo '<img src="' . 'https://images.unsplash.com/photo-1534504969382-fec3d9ffdd73?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDE4fHx8ZW58MHx8fHx8' . '" alt="Bridge" class="w-full h-full object-cover" />';
        echo '</div>';
    ?>
       
    <!-- Main Content -->
    <div class="my-12 px-4 md:px-20">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Map Section -->
            <div class="map-section w-full md:w-2/3 rounded-lg overflow-hidden">
                <h1 class="text-3xl font-bold text-[#87CEEB] mb-4">CONTACT US</h1>
                <hr class="border-t-4 border-b-4 border-[ffdb19] mt-1 mb-8">

                <div class="map-container w-full h-[400px]">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31692.869493409205!2d124.3784586345783!3d11.573076350676893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33085f4e66e0a3d1%3A0x653e1e0b00cc89bb!2sNaval%2C%20Biliran!5e0!3m2!1sen!2sph!4v1677086501027!5m2!1sen!2sph" class="w-full h-full border-none" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="contact-us w-full md:w-1/3 rounded-lg p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Get in touch with us:</h1>
                <p class="text-gray-600 mb-4">We would love to hear from you! Please fill out the form below.</p>
                <form method="post" class="space-y-6">
                    <input type="text" name="name" placeholder="Your Name" required class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <input type="email" name="email" placeholder="Your Email" required class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <textarea name="message" placeholder="Your Message" required class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                    <button type="submit" class="w-full bg-[#002D62] text-white p-4 rounded-lg hover:bg-[#ffdb19] focus:outline-none focus:ring-2 focus:ring-blue-600">Send Message</button>
                </form>
            </div>
        </div>
    </div>

    
</div>

    <?php
    include '../components/links.php';
    include '../components/footer.php';
    ?>
</body>
</html>
