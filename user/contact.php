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
<body class="bg-gray-100 font-sans">

<div class="header-image">
    <img src="<?= 'https://www.biliranisland.com/wp-content/uploads/2016/03/Biliran-Bridge-5-1030x773.jpg' ?>" alt="Bridge" />
</div>
<style>
    .header-image {
        width: 100%;
        height: 300px; /* You can adjust this height */
        overflow: hidden; /* Ensures content doesn’t overflow */
    }

    .header-image img {
        width: 100%;
        height: 100%; /* Make sure the image fills the container's height */
        object-fit: cover; /* Keeps the aspect ratio and fills the container */
    }
</style>

<style>
    
.map-section {
    text-align: center;
    margin: 20px auto; /* Add some space around the map section */
    flex: 10;
}

.map-container {
    width: 100%; /* Makes sure the container spans the full width */
    max-width: 800px; /* Optional: Set a maximum width for the map */
    margin: 0 auto; /* Centers the map */
   
}

.responsive-map {
    width: 100%; /* Make the map responsive to the container's width */
    height: 400px; /* Adjust the height as needed */
    border: none; /* Removes border if it's unnecessary */
}

.map-section h3 {
    text-align: start;
    min-width: 300px; /* Ensures map is not too small */
    font-family: Arial, sans-serif;
    font-size: 30px;
    font-weight: bold;
    color:#00aced;
}
</style>

<div class="container mx-auto p-4 text-center">
<div class="map-container">
    <iframe src="<?= 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31692.869493409205!2d124.3784586345783!3d11.573076350676893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33085f4e66e0a3d1%3A0x653e1e0b00cc89bb!2sNaval%2C%20Biliran!5e0!3m2!1sen!2sph!4v1677086501027!5m2!1sen!2sph' ?>"
        class="responsive-map" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>

    <h1 class="text-3xl font-bold text-gray-800 mb-4">Contact Us</h1>
    <p class="text-lg text-gray-600">Get in touch with us using the form below.</p>
    <form method="post" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <input type="text" name="name" placeholder="Your Name" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg">
        <input type="email" name="email" placeholder="Your Email" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg">
        <textarea name="message" placeholder="Your Message" required class="w-full p-3 mb-4 border border-gray-300 rounded-lg"></textarea>
        <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">Send</button>
    </form>
</div>
</div>
<?php
// Include footer
include '../components/footer.php';
?>
</body>
</html>
