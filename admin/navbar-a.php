<?php $currentPage = basename($_SERVER['REQUEST_URI']); ?>

<!-- ✨ Desktop Sidebar -->
<aside id="sidebar-desktop" class="hidden md:flex w-64 bg-[#002244] text-white p-5 flex-col transition-all duration-300 ease-in-out fixed top-0 left-0 h-full z-10">
    <div class="flex items-center justify-between mb-6">
        <a href="index.php">
            <img id="desktop-logo" src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12 max-w-full sm-h-auto transition-all duration-300">
        </a>
        <button id="toggle-btn" class="text-white p-1 rounded-lg hover:bg-gray-700 transition-all duration-200">
            <i id="icon" class='bx bxs-chevrons-left text-xl'></i>
        </button>
    </div>
    <nav class="flex-1">
        <ul>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'index.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-news text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">News & Event</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">News & Event</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/announcement.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'announcement.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-home text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Announcements</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Announcements</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/bids-awards.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'bids-awards.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-trophy text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Bids & Awards</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Bids & Awards</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/csr-programs.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'csr-programs.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-heart text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">CSR Programs</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">CSR Programs</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/generation-mix.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'generation-mix.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-pie-chart-alt-2 text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Generation Mix</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Generation Mix</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/maintenance.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'maintenance.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-wrench text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Maintenance</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Maintenance</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/national-stories.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'national-stories.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-world text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">National Stories</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">National Stories</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/power-rate.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'power-rate.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-dollar text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Power Rate</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Power Rate</span>
            </li>
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>admin/uncategorized.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200 <?php echo ($currentPage == 'uncategorized.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-category-alt text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Uncategorized</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Uncategorized</span>
            </li>
            
            <hr class="mb-4 border-gray-600">
            <li class="mb-4 relative group">
                <a href="<?php echo BASE_URL; ?>auth/logout.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 transition-all duration-200">
                    <i class='bx bx-exit text-xl'></i>
                    <span class="nav-text transition-opacity duration-200">Logout</span>
                </a>
                <span class="tooltip absolute left-16 bg-gray-800 text-white text-sm rounded-lg px-3 py-1 opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 z-50">Logout</span>
            </li>
        </ul>
    </nav>
</aside>

<!-- ✨ Mobile Sidebar (Icon Only) -->
<div id="sidebar-mobile" class="md:hidden fixed bottom-0 left-0 w-full bg-[#002244] text-white flex justify-around p-4 shadow-lg rounded-t-lg h-16 items-center">
    <a href="<?php echo BASE_URL; ?>index.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 <?php echo ($currentPage == 'index.php') ? 'text-yellow-500' : 'hover:bg-gray-700'; ?>">
        <i class='bx bx-news <?php echo ($currentPage == 'index.php') ? 'text-3xl' : 'text-2xl'; ?>'></i>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/announcements.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 <?php echo ($currentPage == 'announcements.php') ? 'text-yellow-500' : 'hover:bg-gray-700'; ?>">
        <i class='bx bx-bullhorn <?php echo ($currentPage == 'announcements.php') ? 'text-3xl' : 'text-2xl'; ?>'></i>
    </a>
    <a href="<?php echo BASE_URL; ?>auth/logout.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 hover:bg-gray-700">
        <i class='bx bx-exit text-2xl'></i>
    </a>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const sidebarDesktop = document.getElementById('sidebar-desktop');
    const toggleBtn = document.getElementById('toggle-btn');
    const icon = document.getElementById('icon');
    const navTextElements = document.querySelectorAll('.nav-text');
    const menuItems = document.querySelectorAll("#sidebar-desktop ul li a");
    const tooltips = document.querySelectorAll('.tooltip');
    const desktopLogo = document.getElementById('desktop-logo');
    const mainContent = document.querySelector('main'); // Target the main content area

    let sidebarState = localStorage.getItem("sidebarState") || "expanded";

    function applySidebarState(state) {
        if (window.innerWidth <= 768) {
            sidebarDesktop.classList.add('hidden'); // Hide desktop sidebar on mobile
            mainContent.classList.remove('md:pl-64', 'md:pl-16'); // Reset padding on mobile
            return;
        }

        sidebarDesktop.classList.remove('hidden');
        if (state === "collapsed") {
            sidebarDesktop.classList.remove('w-64');
            sidebarDesktop.classList.add('w-16');
            icon.classList.replace('bxs-chevrons-left', 'bxs-chevrons-right');
            desktopLogo.classList.add('h-8');
            desktopLogo.classList.remove('h-12');
            navTextElements.forEach(element => element.classList.add("opacity-0", "hidden"));
            menuItems.forEach(item => item.classList.add("justify-center"));
            tooltips.forEach(tooltip => tooltip.classList.remove("hidden"));
            mainContent.classList.remove('md:pl-64');
            mainContent.classList.add('md:pl-16'); // Adjust padding for collapsed state
        } else {
            sidebarDesktop.classList.remove('w-16');
            sidebarDesktop.classList.add('w-64');
            icon.classList.replace('bxs-chevrons-right', 'bxs-chevrons-left');
            desktopLogo.classList.remove('h-8');
            desktopLogo.classList.add('h-12');
            navTextElements.forEach((element, index) => {
                element.classList.remove("hidden");
                setTimeout(() => element.classList.remove("opacity-0"), index * 50);
            });
            menuItems.forEach(item => item.classList.remove("justify-center"));
            tooltips.forEach(tooltip => tooltip.classList.add("hidden"));
            mainContent.classList.remove('md:pl-16');
            mainContent.classList.add('md:pl-64'); // Adjust padding for expanded state
        }
    }

    applySidebarState(sidebarState);

    window.addEventListener("resize", () => {
        setTimeout(() => applySidebarState(localStorage.getItem("sidebarState") || "expanded"), 100);
    });

    toggleBtn.addEventListener("click", () => {
        if (window.innerWidth <= 768) return; // Prevent toggling in mobile view

        if (sidebarDesktop.classList.contains('w-16')) {
            sidebarDesktop.classList.remove('w-16');
            sidebarDesktop.classList.add('w-64');
            icon.classList.replace('bxs-chevrons-right', 'bxs-chevrons-left');
            desktopLogo.classList.remove('h-8');
            desktopLogo.classList.add('h-12');
            localStorage.setItem("sidebarState", "expanded");

            navTextElements.forEach((element, index) => {
                element.classList.remove("hidden");
                setTimeout(() => element.classList.remove("opacity-0"), index * 50);
            });
            menuItems.forEach(item => item.classList.remove("justify-center"));
            tooltips.forEach(tooltip => tooltip.classList.add("hidden"));
            mainContent.classList.remove('md:pl-16');
            mainContent.classList.add('md:pl-64');
        } else {
            navTextElements.forEach(element => {
                element.classList.add("opacity-0");
                setTimeout(() => element.classList.add("hidden"), 200);
            });
            sidebarDesktop.classList.remove('w-64');
            sidebarDesktop.classList.add('w-16');
            icon.classList.replace('bxs-chevrons-left', 'bxs-chevrons-right');
            desktopLogo.classList.remove('h-12');
            desktopLogo.classList.add('h-8');
            localStorage.setItem("sidebarState", "collapsed");
            menuItems.forEach(item => item.classList.add("justify-center"));
            tooltips.forEach(tooltip => tooltip.classList.remove("hidden"));
            mainContent.classList.remove('md:pl-64');
            mainContent.classList.add('md:pl-16');
        }
    });
});
</script>