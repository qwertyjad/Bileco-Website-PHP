<?php $currentPage = basename($_SERVER['REQUEST_URI']); ?>
<nav x-data="{ isOpen: false }" class="relative bg-[#13274F] text-white sticky top-0 z-30 hidden lg:block">
    <div class="container px-6 py-3 mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12">
        </a>

<!-- Desktop Menu -->
<div class="hidden lg:flex items-center justify-center space-x-6 font-bold text-xs">
<a href="<?php echo BASE_URL; ?>" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <<?php echo ($currentPage == '/') ? 'text-yellow-500' : ''; ?>">HOME</a>

<!-- About Us Dropdown -->
<div x-data="{ isHovering: false }" class="relative group" @mouseover="isHovering = true" @mouseleave="isHovering = false">
    <button class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">
        ABOUT US
    </button>
    <!-- Dropdown Menu -->
    <div x-show="isHovering" x-transition:leave="transition-opacity duration-400" class="absolute left-0 mt-2 bg-white text-gray-700 rounded-lg shadow-lg w-48">
        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Services</a>
        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Careers</a>
        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Blog</a>
    </div>
</div>

<!-- Consumer Corner Dropdown -->
<div x-data="{ isHovering: false }" class="relative group" @mouseover="isHovering = true" @mouseleave="isHovering = false">
      <a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="relative">
           <button class="px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-500 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'apply.php') ? 'text-yellow-500' : ''; ?>">
             CONSUMER CORNER
           </button>
      </a>

 <!-- Dropdown Menu -->
        <div x-show="isHovering" x-transition:leave="transition-opacity duration-400" class="absolute left-0 mt-2 bg-white text-gray-700 rounded-lg shadow-lg w-48">
            <a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'apply.php') ? 'text-yellow-500' : ''; ?>">Apply for New Conection</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/rights.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'rights.php') ? 'text-yellow-500' : ''; ?>">Rights & Obligations</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/billing.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'billing.php') ? 'text-yellow-500' : ''; ?>">Billing Information</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/qualification.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'qualification.php') ? 'text-yellow-500' : ''; ?>">Qualifications of EC Board</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/senior.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'senior.php') ? 'text-yellow-500' : ''; ?>">Senior Citizen Discount</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/maintenance.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'maintenance.php') ? 'text-yellow-500' : ''; ?>">Maintenance Schedule</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/power.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'power.php') ? 'text-yellow-500' : ''; ?>">Power Rates</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/generation.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'generation.php') ? 'text-yellow-500' : ''; ?>">Generation Mix</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/safety.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'safety.php') ? 'text-yellow-500' : ''; ?>">Safety Tips</a>
            <a href="<?php echo BASE_URL; ?>user/consumer/tipid.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'tipid.php') ? 'text-yellow-500' : ''; ?>">Tipid Tips</a>
        </div>
    </div>
            <a href="<?php echo BASE_URL; ?>user/news.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'news.php') ? 'text-yellow-500' : ''; ?>">NEWS & EVENT</a>
            <a href="<?php echo BASE_URL; ?>user/download.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'download.php') ? 'text-yellow-500' : ''; ?>">DOWNLOADS</a>
            <a href="<?php echo BASE_URL; ?>user/contact.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'contact.php') ? 'text-yellow-500' : ''; ?>">CONTACT</a>
        
    <!-- Search Bar -->
    <div class="flex items-center space-x-4 ml-6 pt-2">
        <form action="#" method="GET" class="flex items-center">
            <input type="text" name="search" placeholder="Search..." class="border-b-2 border-bottom-[#ffdb19] focus:border-[#ffdb19] outline-none text-[#ffdb19] placeholder-gray-500 bg-transparent w-50">
            
            <button type="submit" class="text-white hover:text-[#ffdb19] focus:outline-none">
                <i class="fas fa-search w-10 h-4"></i> 
            </button>

            
        </form>
    </div>
</div>

        <!-- Profile Dropdown -->
<div class="relative">
    <button @click="isOpen = !isOpen" class="flex items-center space-x-2 p-2 rounded-full  text-white  focus:outline-none transition duration-200">
        <!-- Profile Icon with hover effect -->
        <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Profile" class="w-8 h-8 rounded-full border-2  hover:border-yellow-500 transition duration-200">
    </button>
    
    <!-- Dropdown Menu -->
    <div x-show="isOpen" x-transition class="absolute right-0 mt-2 w-40 bg-white text-black rounded-lg z-30">
        <a href="<?php echo BASE_URL; ?>user/login.php" class="block px-4 py-2 text-sm hover:bg-gray-100 transition duration-200">Login</a>
        <a href="<?php echo BASE_URL; ?>user/signup.php" class="block px-4 py-2 text-sm hover:bg-gray-100 transition duration-200">Sign Up</a>
    </div>
</div>

    </div>
</nav>


<!-- ✅ Mobile Navigation Bar (NO MENU) -->
<div class="lg:hidden fixed top-0 w-full bg-[#13274F] h-14 flex items-center px-4 z-50">
    <!-- Logo -->
    <a href="<?php echo BASE_URL; ?>" class="flex items-center">
        <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-10">
    </a>

    
</div>

<!-- Mobile Floating Bottom Navigation -->
<div class="fixed z-50 w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600 lg:hidden">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto">
        <a href="<?php echo BASE_URL; ?>index.php" class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
            </svg>
            <span class="sr-only">Home</span>
        </a>

        <a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M11.074 4 8.442.408A.95.95 0 0 0 7.014.254L2.926 4h8.148ZM9 13v-1a4 4 0 0 1 4-4h6V6a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h17a1 1 0 0 0 1-1v-2h-6a4 4 0 0 1-4-4Z"/>
            </svg>
            <span class="sr-only">Consumer</span>
        </a>

        <div class="flex items-center justify-center">
            <button class="inline-flex items-center justify-center w-10 h-10 font-medium bg-blue-600 rounded-full hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none">
                <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a6 6 0 1 0-6-6 6 6 0 0 0 6 6Zm0-10a4 4 0 1 1-4 4 4 4 0 0 1 4-4ZM21 21l-4.35-4.35"/>
                </svg>
                <span class="sr-only">Search</span>
            </button>
        </div>

        <a href="<?php echo BASE_URL; ?>user/news.php" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2"/>
            </svg>
            <span class="sr-only">News</span>
        </a>

        <a href="<?php echo BASE_URL; ?>user/contact.php" class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z"/>
            </svg>
            <span class="sr-only">Contact</span>
        </a>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
<!-- Add Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

