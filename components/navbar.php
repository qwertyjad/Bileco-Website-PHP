

<nav x-data="{ isOpen: false }" class="relative bg-[#13274F] text-white sticky top-0 z-30">
    <div class="container px-6 py-3 mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex space-x-6">
            <a href="<?php echo BASE_URL; ?>" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">HOME</a>

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
                <button class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">
                    CONSUMER CORNER
                </button>
                <!-- Dropdown Menu -->
                <div x-show="isHovering" x-transition:leave="transition-opacity duration-400" class="absolute left-0 mt-2 bg-white text-gray-700 rounded-lg shadow-lg w-48">
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Services</a>
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Careers</a>
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-200">Blog</a>
                </div>
            </div>

            <a href="#" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">NEWS & EVENT</a>
            <a href="#" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">DOWNLOADS</a>
            <a href="<?php echo BASE_URL; ?>user/contact.php" class="relative px-3 py-2 transition-colors duration-300 hover:text-yellow-500 after:block after:content-[''] after:h-[2px] after:w-full after:bg-yellow-500 after:scale-x-0 after:transition-transform after:duration-300 after:origin-left hover:after:scale-x-100">CONTACT</a>
        </div>

        <!-- Search Bar -->
        <div class="relative lg:block">
            <input type="text" class="w-full py-1 pl-10 pr-4 text-gray-700 placeholder-gray-600 bg-white border-b border-gray-600 focus:outline-none focus:border-gray-600" placeholder="Search">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden flex items-center space-x-4">
            <button @click="isOpen = !isOpen" class="text-white focus:outline-none" aria-label="toggle menu">
                <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                </svg>
                <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="isOpen" @resize.window="if (window.innerWidth >= 1024) isOpen = false" class="fixed inset-y-0 left-0 w-64 bg-[#13274F] text-white transform transition-transform duration-300 ease-in-out" :class="isOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="p-6 space-y-4">
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">HOME</a>
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">ABOUT US</a>
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">CONSUMER CORNER</a>
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">NEWS & EVENT</a>
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">DOWANLOADS</a>
            <a href="#" class="block px-3 py-2 transition-colors duration-300 hover:text-yellow-500">CONTACT</a>
        </div>
    </div>
    <hr class="border-t border-white mt-0 mb-0">
</nav>


<!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
