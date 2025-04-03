<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<script src="https://cdn.tailwindcss.com"></script>
<nav x-data="{ isOpen: false, searchOpen: false }" @keydown.escape="searchOpen = false" class="relative bg-[#13274F] text-white sticky top-0 z-30 hidden lg:block">
    <div class="container px-6 py-3 mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12 max-w-full sm-h-auto">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex  space-x-4 font-bold text-[12px]">
            <a href="<?php echo BASE_URL; ?>index.php"
               class="relative px-3 py-2 transition-colors duration-300
                      hover:text-yellow-500
                      after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500
                      after:scale-x-0 after:transition-transform after:duration-300
                      after:origin-left hover:after:scale-x-100
                      <?php echo ($currentPage == 'index.php') ? 'text-yellow-500' : ''; ?>">
                HOME
            </a>

            <!-- About Us Dropdown -->
            <div x-data="{ isOpen: false }" @mouseleave="isOpen = false" class="relative group">
                <button @click="isOpen = !isOpen"
                        :class="{'text-yellow-500': <?php echo ($currentPage == 'about.php') ? 'true' : 'false'; ?>}"
                        class="relative flex items-center px-3 py-2 transition-colors duration-300 hover:text-yellow-500
                               after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-500 after:origin-left hover:after:scale-x-100 whitespace-nowrap">
                    ABOUT US
                    <i class="fas fa-chevron-down ml-1 transition-transform duration-500"
                       :class="{'transform rotate-180': isOpen}"></i>
                </button>
                <!-- Flyout Menu -->
                <div x-show="isOpen" x-transition:leave="transition-opacity duration-200" class="absolute left-0 mt-2 bg-white text-gray-700 rounded-lg shadow-lg w-96 p-4 hidden group-hover:block">
                    <div class="grid grid-cols-1">
                        <a href="<?php echo BASE_URL; ?>user/about/about.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'about.php') ? 'text-yellow-500' : ''; ?>">About Us</a>
                        <a href="<?php echo BASE_URL; ?>user/about/history.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'history.php') ? 'text-yellow-500' : ''; ?>">Brief History</a>
                        <a href="<?php echo BASE_URL; ?>user/about/vision.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'vision.php') ? 'text-yellow-500' : ''; ?>">Vision, Mission & Core Values</a>
                        <a href="<?php echo BASE_URL; ?>user/about/logo.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'logo.php') ? 'text-yellow-500' : ''; ?>">Our Logo</a>
                        <a href="<?php echo BASE_URL; ?>user/about/board.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'board.php') ? 'text-yellow-500' : ''; ?>">Board of Directors</a>
                        <a href="<?php echo BASE_URL; ?>user/about/management.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'management.php') ? 'text-yellow-500' : ''; ?>">Management</a>
                        <a href="<?php echo BASE_URL; ?>user/about/franchise.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'franchise.php') ? 'text-yellow-500' : ''; ?>">Franchise</a>
                        <a href="<?php echo BASE_URL; ?>user/about/practices.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'practices.php') ? 'text-yellow-500' : ''; ?>">Best Practice</a>
                        <a href="<?php echo BASE_URL; ?>user/about/award.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'award.php') ? 'text-yellow-500' : ''; ?>">Award & Citations</a>
                        <a href="<?php echo BASE_URL; ?>user/about/power.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'power.php') ? 'text-yellow-500' : ''; ?>">Power Sources</a>
                    </div>
                </div>
            </div>

            <!-- Consumer Corner Dropdown -->
            <div x-data="{ isOpen: false }" @mouseleave="isOpen = false" class="relative group">
                <button @click="isOpen = !isOpen"
                        :class="{'text-yellow-500': <?php echo ($currentPage == 'apply.php') ? 'true' : 'false'; ?>}"
                        class="relative flex items-center px-3 py-2 transition-colors duration-300 hover:text-yellow-500
                               after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-500 after:origin-left hover:after:scale-x-100 whitespace-nowrap">
                    CONSUMER CORNER
                    <i class="fas fa-chevron-down ml-1 transition-transform duration-500"
                       :class="{'transform rotate-180': isOpen}"></i>
                </button>
                <!-- Flyout Menu -->
                <div x-show="isOpen" x-transition:leave="transition-opacity duration-200" class="absolute left-0 mt-2 bg-white text-gray-700 rounded-lg shadow-lg w-96 p-4 hidden group-hover:block">
                    <div class="grid grid-cols-1">
                        <a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="block px-4 py-2 text-sm hover:bg-gray-200 <?php echo ($currentPage == 'apply.php') ? 'text-yellow-500' : ''; ?>">Apply for New Connection</a>
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
            </div>

            <a href="<?php echo BASE_URL; ?>user/news.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'news.php') ? 'text-yellow-500' : ''; ?>">NEWS & EVENT</a>
            <a href="<?php echo BASE_URL; ?>user/downloads/download.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'download.php') ? 'text-yellow-500' : ''; ?>">DOWNLOADS</a>
            <a href="<?php echo BASE_URL; ?>user/contact.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100 <?php echo ($currentPage == 'contact.php') ? 'text-yellow-500' : ''; ?>">CONTACT</a>

           
<!-- Alpine.js Search Component -->
<div x-data="{ searchOpen: false }" @keydown.escape.window="searchOpen = false">
    <!-- Search Icon -->
    <div class="flex items-center space-x-4 ml-6 pt-2">
        <button @click="searchOpen = true" class="text-white hover:text-[#ffdb19] focus:outline-none">
            <i class="fas fa-search w-10 h-4"></i>
        </button>
    </div>

    <!-- Search Bar (Hidden by Default) -->
    <div x-show="searchOpen" x-transition.opacity x-cloak class="fixed inset-0 bg-gray-800 bg-opacity-90 flex items-center justify-center z-50"
         @click="searchOpen = false">
        <!-- Prevent Closing When Clicking Inside -->
        <form action="<?php echo BASE_URL; ?>user/result.php" method="GET" 
              class="flex items-center bg-white p-4 rounded-lg shadow-lg w-80"
              @click.stop @submit="searchOpen = false">
              
            <input type="text" name="query" id="searchInput" placeholder="Search..." 
                class="w-60 border-b-2 border-blue-500 focus:outline-none text-black placeholder-gray-500 p-2"
                required @keydown.stop>
            
            <button type="submit" class="text-blue-500 hover:text-white focus:outline-none">
                <i class="fas fa-search"></i>
            </button>

            <!-- Close Button -->
            <button @click="searchOpen = false" type="button" class="text-blue-500 hover:text-white focus:outline-none ml-2">
                <i class="fas fa-times"></i>
            </button>
        </form>
    </div>
</div>

<!-- Add this CSS to prevent flickering -->
<style>
    [x-cloak] { display: none; }
</style>

<script>
    // Auto-submit when pressing "Enter"
    document.getElementById("searchInput").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();  // Prevent default form submission
            this.form.submit();
        }
    });
</script>




        </div>

<!-- Profile Dropdown -->
<div class="relative">
    <!-- Button to toggle dropdown -->
    <button id="dropdownButton" class="flex items-center space-x-2 focus:outline-none">
        <i class="fas fa-user-circle text-2xl"></i>
    </button>

    <!-- Dropdown menu -->
    <ul id="dropdownMenu" class="hidden absolute right-0 mt-3 w-52 p-2 bg-white text-gray-700 shadow-lg rounded-box z-50">
        <li>
            <a href="<?php echo BASE_URL; ?>user/dashboard/dashboard.php" class="block px-4 py-2 text-sm hover:bg-gray-200 rounded">
                Acccount Information
            </a>
        </li>
        <hr>
        <li>
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="block px-4 py-2 text-sm hover:bg-gray-200 rounded">
                Logout
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.getElementById("dropdownButton");
        const menu = document.getElementById("dropdownMenu");

        button.addEventListener("click", function () {
            menu.classList.toggle("hidden");
        });

        document.addEventListener("click", function (event) {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add("hidden");
            }
        });
    });
</script>


    </div>
</nav>
<!-- ✅ Mobile Navigation Bar (NO MENU) -->
<div class="lg:hidden sticky top-0 z-30 w-full bg-[#13274F] h-14 flex items-center px-4 z-50">
    <!-- Logo -->
    <a href="<?php echo BASE_URL; ?>" class="flex items-center">
        <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12">
    </a>

    <!-- Spacer to push Logout to the right -->
    <div class="flex-grow"></div>

    <!-- Logout Button -->
    <a href="<?php echo BASE_URL; ?>auth/logout.php" class="flex items-center space-x-1 text-white px-3 py-1 rounded-lg hover:text-yellow-500">
        <i class="fas fa-sign-out-alt text-lg"></i>
        <span class="text-sm font-bold">LOGOUT</span>
    </a>
</div>

<div class="fixed z-50 w-full max-w-md md:max-w-lg h-16 max-w-full -translate-x-1/2 bg-gray-900 backdrop-blur-lg bg-opacity-50 border border-gray-200 rounded-2xl bottom-4 left-1/2 dark:bg-blue-700 dark:border-blue-600 lg:hidden px-4 sm:px-8">
    <div class="grid h-full max-w-full grid-cols-7 mx-auto">
        <!-- Home -->
        <a href="<?php echo BASE_URL; ?>index.php"
            class="inline-flex flex-col items-center justify-center px-5 rounded-s-full group">
            <i class="fas fa-home w-5 h-5 <?php echo ($currentPage == 'index.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">Home</span>
        </a>

        <!-- About Us -->
        <a href="<?php echo BASE_URL; ?>user/about/about.php" class="inline-flex flex-col items-center justify-center px-5 rounded-s-full group">
            <i class="fas fa-users w-5 h-5 <?php echo ($currentPage == 'about.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">About Us</span>
        </a>

        <!-- Consumer Corner -->
        <a href="<?php echo BASE_URL; ?>user/consumer/apply.php" class="inline-flex flex-col items-center justify-center px-5 group">
            <i class="fas fa-list w-5 h-5 <?php echo ($currentPage == 'apply.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">Consumer</span>
        </a>

        <!-- Center Search Button -->
        <div class="flex items-center justify-center">
            <button class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#ffdb19] rounded-full hover:bg-[#13274F] focus:ring-4 focus:ring-[#13274F] focus:outline-none">
                <i class="fas fa-search w-10 h-4 text-[#13274F] hover:text-yellow-500"></i>
                <span class="sr-only">Search</span>
            </button>
        </div>

        <!-- News -->
        <a href="<?php echo BASE_URL; ?>user/news.php" class="inline-flex flex-col items-center justify-center px-5 group">
            <i class="fas fa-newspaper w-5 h-5 <?php echo ($currentPage == 'news.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">News</span>
        </a>

        <!-- Downloads -->
        <a href="<?php echo BASE_URL; ?>user/downloads/download.php" class="inline-flex flex-col items-center justify-center px-5 group">
            <i class="fas fa-download w-5 h-5 <?php echo ($currentPage == 'download.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">Downloads</span>
        </a>

        <!-- Contact -->
        <a href="<?php echo BASE_URL; ?>user/contact.php" class="inline-flex flex-col items-center justify-center px-5 rounded-e-full group">
            <i class="fas fa-envelope w-5 h-5 <?php echo ($currentPage == 'contact.php') ? 'text-white' : 'text-yellow-500'; ?> group-hover:text-[#13274F]"></i>
            <span class="sr-only">Contact</span>
        </a>
    </div>
</div>

<hr class="border-b-2 border-white mt-0 mb-0">

<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
<!-- Add Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
