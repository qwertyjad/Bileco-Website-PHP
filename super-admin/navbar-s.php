<?php 

$currentPage = basename($_SERVER['REQUEST_URI']); 
$newsActive = ($currentPage == 'mng-news.php' || $currentPage == 'add-edit-news.php') ? 'bg-yellow-500 font-bold' : ''; 
$iconSize = ($currentPage == 'mng-news.php' || $currentPage == 'add-edit-news.php') ? 'text-3xl' : 'text-2xl'; 
?>


<!-- ✨ Desktop Sidebar -->
<aside id="sidebar-desktop" class="hidden md:flex w-64 bg-[#002244] text-white p-5 flex-col transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
        <a href="index.php">
            <img id="desktop-logo" src="<?php echo BASE_URL; ?>assets/images/logos/logos.png" alt="Logo" class="w-auto h-12 max-w-full sm-h-auto">
        </a>
        <button id="toggle-btn" class="text-white p-1 rounded-lg hover:bg-gray-700">
            <i id="icon" class='bx bxs-chevrons-left'></i>
        </button>
    </div>
    <nav class="flex-1">
        <ul>
            <li class="mb-4">
                <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 <?php echo ($currentPage == 'index.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-home'></i><span class="nav-text">Dashboard</span>
                </a>
            </li>
           
            <li class="mb-4">
                <a href="<?php echo BASE_URL; ?>super-admin/user/mng-user.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 <?php echo ($currentPage == 'mng-user.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-group'></i><span class="nav-text">Manage Consumer</span>
                </a>
            </li>
            <li class="mb-4">
                <a href="<?php echo BASE_URL; ?>super-admin/acrreditation_account/accreditation.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500 <?php echo ($currentPage == 'accreditation.php') ? 'bg-yellow-500 font-bold' : ''; ?>">
                    <i class='bx bx-group'></i><span class="nav-text">Register Accreditation</span>
                </a>
            </li>
            <hr class="mb-4">
            <li class="mb-4">
                <a href="<?php echo BASE_URL; ?>auth/logout.php" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-yellow-500">
                    <i class='bx bx-exit'></i><span class="nav-text">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- ✨ Mobile Sidebar (Icon Only) -->
<div id="sidebar-mobile" class="md:hidden fixed bottom-0 left-0 w-full bg-[#002244] text-white flex justify-around p-4 shadow-lg rounded-t-lg h-16 items-center">
    <a href="<?php echo BASE_URL; ?>index.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 <?php echo ($currentPage == 'index.php') ? 'text-yellow-500' : 'hover:bg-gray-700'; ?>">
        <i class='bx bx-home <?php echo ($currentPage == 'index.php') ? 'text-3xl' : 'text-2xl'; ?>'></i>
    </a>
    <a href="<?php echo BASE_URL; ?>super-admin/news/mng-news.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 <?php echo $newsActive; ?>">
    <i class='bx bx-news <?php echo $iconSize; ?>'></i>
</a>
    <a href="<?php echo BASE_URL; ?>super-admin/user/mng-user.php" class="flex flex-col items-center p-3 rounded-lg w-1/4 <?php echo ($currentPage == 'mng-user.php') ? 'text-yellow-500' : 'hover:bg-gray-700'; ?>">
        <i class='bx bx-group <?php echo ($currentPage == 'mng-user.php') ? 'text-3xl' : 'text-2xl'; ?>'></i>
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

    let sidebarState = localStorage.getItem("sidebarState") || "expanded";

    function applySidebarState(state) {
        if (window.innerWidth <= 768) {
            sidebarDesktop.classList.add('hidden'); // Hide desktop sidebar on mobile
        } else {
            sidebarDesktop.classList.remove('hidden');
            if (state === "collapsed") {
                sidebarDesktop.classList.add('w-16');
                sidebarDesktop.classList.remove('w-64');
                icon.classList.replace('bxs-chevrons-left', 'bxs-chevrons-right');
                navTextElements.forEach(element => element.classList.add("hidden"));
                menuItems.forEach(item => item.classList.add("justify-center"));
            } else {
                sidebarDesktop.classList.add('w-64');
                sidebarDesktop.classList.remove('w-16');
                icon.classList.replace('bxs-chevrons-right', 'bxs-chevrons-left');
                setTimeout(() => {
                    navTextElements.forEach((element, index) => {
                        setTimeout(() => element.classList.remove("hidden"), index * 50);
                    });
                }, 300);
                menuItems.forEach(item => item.classList.remove("justify-center"));
            }
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
            localStorage.setItem("sidebarState", "expanded");

            setTimeout(() => {
                navTextElements.forEach((element, index) => {
                    setTimeout(() => element.classList.remove("hidden"), index * 50);
                });
            }, 300);

            menuItems.forEach(item => item.classList.remove("justify-center"));
        } else {
            navTextElements.forEach(element => element.classList.add("hidden"));
            sidebarDesktop.classList.add('w-16');
            sidebarDesktop.classList.remove('w-64');
            icon.classList.replace('bxs-chevrons-left', 'bxs-chevrons-right');
            localStorage.setItem("sidebarState", "collapsed");
            menuItems.forEach(item => item.classList.add("justify-center"));
        }
    });
});
</script>
