<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.classList.add('toast', 'fixed', 'top-5', 'left-1/2', 'transform', '-translate-x-1/2', 'px-6', 'py-3', 'rounded-lg', 'shadow-lg', 'text-white', 'font-medium');
            if (type === 'success') {
                toast.classList.add('bg-green-500');
            } else {
                toast.classList.add('bg-red-500');
            }
            toast.innerHTML = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 3000);
        }

        function redirectToVerify() {
            setTimeout(function() {
                window.location.href = 'verify.php';
            }, 3000);
        }

        function showLoader() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('registerForm').classList.add('hidden');
        }

        window.onload = function() {
            <?php
            if (isset($_SESSION['register_success'])) {
                echo "showToast('success', '" . $_SESSION['register_success'] . "');";
                unset($_SESSION['register_success']);
                echo "redirectToVerify();";
            }
            if (isset($_SESSION['register_error'])) {
                echo "showToast('error', '" . $_SESSION['register_error'] . "');";
                unset($_SESSION['register_error']);
            }
            ?>
        };
    </script>

    <style>
        .spinner {
           position: relative;
           width: 35.2px;
           height: 35.2px;
        }

        .spinner::before,
        .spinner::after {
           content: '';
           width: 100%;
           height: 100%;
           display: block;
           animation: spinner-b4c8mmhg 0.7s backwards, spinner-49opz7hg 1.75s 0.7s infinite ease;
           border: 8.8px solid #fff947;
           border-radius: 50%;
           box-shadow: 0 -52.8px 0 -8.8px #fff947;
           position: absolute;
        }

        .spinner::after {
           animation-delay: 0s, 1.75s;
        }

        @keyframes spinner-b4c8mmhg {
           from {
              box-shadow: 0 0 0 -8.8px #fff947;
           }
        }

        @keyframes spinner-49opz7hg {
           to {
              transform: rotate(360deg);
           }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen bg-gradient-to-br from-yellow-400 via-blue-300 to-[#13274F]">
    
    <!-- Loader (Initially Hidden) -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center z-50">
        <div class="spinner"></div>
        <p class="mt-10 text-white text-x font-semibold">Please wait...</p>
    </div>


    <!-- Registration Form -->
     <!-- Registration Form -->
     <section class="w-full max-w-3xl p-8 bg-white rounded-lg shadow-md">
        <h2 class="text-4xl font-bold text-center text-gray-700">REGISTER ACCOUNT</h2>

        <form action="register.php" method="POST" class="mt-6" onsubmit="showLoader()">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
    <label class="text-gray-700" for="accountnum">Account Number</label>
    <input id="accountnum" name="accountnum" type="text" required 
        class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none"
        maxlength="12" placeholder="01-2345-6789">
</div>

<script>
document.getElementById("accountnum").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, ""); // Remove non-numeric characters
    let formattedValue = "";

    if (value.length > 0) formattedValue += value.substring(0, 2); // First 2 digits
    if (value.length > 2) formattedValue += "-" + value.substring(2, 6); // Next 4 digits
    if (value.length > 6) formattedValue += "-" + value.substring(6, 10); // Last 4 digits

    e.target.value = formattedValue;
});
</script>


                <div>
                    <label class="text-gray-700" for="email">Email Address</label>
                    <input id="email" name="email" type="email" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="text-gray-700" for="firstname">First Name</label>
                    <input id="firstname" name="firstname" type="text" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="text-gray-700" for="middlename">Middle Name</label>
                    <input id="middlename" name="middlename" type="text" class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="text-gray-700" for="lastname">Last Name</label>
                    <input id="lastname" name="lastname" type="text" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="text-gray-700" for="suffix">Suffix</label>
                    <select id="suffix" name="suffix" class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                        <option value="">N/A</option>
                        <option value="Jr">Jr</option>
                        <option value="Sr">Sr</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="text-gray-700" for="address">Address</label>
                    <input id="address" name="address" type="text" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="text-gray-700" for="contactnumber">Contact Number</label>
                    <input id="contactnumber" name="contactnumber" type="tel" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>
               
            </div>


            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="text-gray-700" for="password">Password</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="text-gray-700" for="confirmpassword">Confirm Password</label>
                    <input id="confirmpassword" name="confirmpassword" type="password" required class="w-full px-4 py-2 mt-1 text-gray-700 border rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>
            </div>

            <div class="flex flex-col items-center mt-6">
                <button type="submit" name="register" class="w-full sm:w-auto px-6 py-2.5 text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none">
                    Register
                </button>
                <p class="mt-4 text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a></p>
            </div>
        </form>
    </section>
</body>
</html>
