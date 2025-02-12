<?php
// Start the session
session_start();

// Reset the session variable to show the loader on refresh
if (isset($_SESSION['loaded'])) {
    unset($_SESSION['loaded']);
}

// Set the session variable to indicate the loader was shown
$_SESSION['loaded'] = true;

// Redirect to index.php after 3 seconds
header("Refresh: 4; url=index.php");

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Get a random image from the 'files' directory
$files = glob('files/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$bgImage = !empty($files) ? $files[array_rand($files)] : 'assets/images/logo.jpg'; // Fallback image
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
</head>
<body class="flex items-center justify-center h-screen bg-cover bg-center bg-[#002D62]">

    <div class="w-96 h-60 flex flex-col items-center justify-center  bg-opacity-10 backdrop-blur-lg p-6 text-center animate-fade-in">
        <img src="assets/images/logos/logo.png" alt="Logo" class="animate-pulse"> <!-- Replace with your actual logo -->
        <p class="mt-4 text-lg text-white font-semibold animate-blink">Please wait, loading the site ...</p>
        <p class="text-3xl mt-2 text-[#ffbf00] whitespace-nowrap font-medium italic pt-8">We Serve Because We Care</p> <!-- Added caption -->
    </div>

    <!-- Animations -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fade-in 1.5s ease-in-out;
        }

        @keyframes blink {
            from { opacity: 1; }
            to { opacity: 0.5; }
        }
        .animate-blink {
            animation: blink 1.5s infinite alternate;
        }
    </style>

</body>
</html>
