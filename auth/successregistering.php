<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-purple-200 flex items-center justify-center min-h-screen">

    <section class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl text-center transform transition-all duration-300 hover:scale-105">
        <div class="flex flex-col items-center">
            <svg class="w-16 h-16 text-green-500 animate-bounce" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <circle cx="12" cy="12" r="10" class="text-green-500"/>
                <path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-800 mt-4">Success!</h2>
        <p class="mt-3 text-gray-600">Your account has been created successfully.</p>
        <p class="text-gray-600">Redirecting you to verification...</p>
        
        <div class="mt-6">
            <a href="verify.php" class="px-6 py-3 text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg hover:opacity-80 transition">Proceed to Verification</a>
        </div>
    </section>

    <script>
        // Redirect to verify.php after 5 seconds
        setTimeout(() => {
            window.location.href = "verify.php";
        }, 5000);
    </script>

</body>
</html>
