<?php
// Start session (if needed)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="bootstrap-4.6.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="bootstrap-4.6.0/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="assets/images/icons.png" type="image/x-icon">
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for better organization -->
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<!-- Header Image -->  
<header></header>  

<!-- Main Content -->  
<div class="container">  
    <div class="sidebar">  
        <ul>  
            <li><a href="brief-history.php">Brief History</a></li>  
            <li><a href="vision.php">Vision, Mission, & Core Values</a></li>  
            <li><a href="our-logo.php">Our Logo</a></li>  
            <li><a href="board-of-directors.php">The Board of Directors</a></li>  
            <li><a href="management.php">The Management</a></li>  
            <li><a href="franchise-area.php">Franchise Area</a></li>  
            <li><a href="best-practices.php">Best Practices</a></li>  
            <li><a href="awards.php">Awards & Citations</a></li>  
            <li><a href="power-sources.php">Power Sources</a></li>  
        </ul>  
    </div>  

    <div class="content">  
        <h2>About Us</h2>  
        <p><strong>Brief History</strong></p>  
        <p>Biliran Electric Cooperative, Inc. (BILECO) was organized as a non-stock, nonprofit electric cooperative pursuant to Presidential Decree No. 269. Its Articles of Incorporation was signed on July 6, 1979. Its franchise area includes Almeria, Biliran, Cabucgayan, Caibiran, Culaba, Kawayan, and Naval.</p>  

        <p><strong>Purpose</strong></p>  
        <p>Electric cooperatives like BILECO were formed to provide electricity on an area coverage basis at "the lowest cost consistent with sound economy and prudent management."</p>  

        <p><strong>Powers</strong></p>  
        <p>BILECO is vested with all necessary powers to achieve its cooperative purposes and operate efficiently.</p>  
    </div>  
    
    <div class="sidebar">
        <div class="search-box">
            <input type="text" placeholder="Search">
        </div>
        <h2>Categories</h2>
        <ul>
            <li><a href="#">Announcements</a></li>
            <li><a href="#">Bids & Awards</a></li>
            <li><a href="#">CSR Programs</a></li>
            <li><a href="#">Generation Mix</a></li>
            <li><a href="#">Maintenance Schedule</a></li>
            <li><a href="#">National Stories</a></li>
            <li><a href="#">News & Events</a></li>
            <li><a href="#">Power Rate</a></li>
        </ul>
        <h2>Archives</h2>
        <ul>
            <!-- Archive links here -->
        </ul>
    </div>
</div>  

<?php include 'footer.php'; ?>

<script>
    document.getElementById('mobileSearchButton').addEventListener('click', function() {  
        document.getElementById('mobileSearchOverlay').style.display = 'flex';  
    });  
    
    document.getElementById('mobileSearchOverlay').addEventListener('click', function() {  
        this.style.display = 'none';  
    });  
</script>  

</body>
</html>
