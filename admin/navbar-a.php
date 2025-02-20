<?php $currentPage = basename($_SERVER['REQUEST_URI']); ?>

<!-- Include Alpine.js & Tailwind CSS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Side Navbar -->
<div x-data="{ isOpen: false, dropdownOpen: false }" class="flex h-screen">
    <!-- Sidebar -->
    <div :class="isOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:relative inset-y-0 left-0 bg-[#13274F] text-white w-64 transition-transform duration-300 ease-in-out lg:translate-x-0">
        <div class="p-6 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>">
                <img src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12 max-w-full">
            </a>
            <button @click="isOpen = !isOpen" class="lg:hidden text-white text-2xl focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <hr class="border-white">
        <nav class="p-4 space-y-4">
        <a href="index.php" class="block px-4 py-2 text-lg hover:bg-gray-700">Home</a>
        <hr>
            <a href="dashboard.php" class="block px-4 py-2 text-lg hover:bg-gray-700">Dashboard</a>
            <hr>
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="block px-4 py-2 text-lg hover:bg-gray-700">Logout</a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 bg-black flex flex-col fixed">
        <!-- Top Bar -->
        <div class=" text-white p-4 flex justify-between items-center lg:hidden">
            <button @click="isOpen = !isOpen" class="text-[13274F] text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
 
    </div>
</div>

<!-- FontAwesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
