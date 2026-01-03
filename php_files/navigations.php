<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;
if (isset($_SESSION['user']['id'])) {
    include("connections.php");
    $uid = (int)$_SESSION['user']['id'];
    $res = $conn->query("SELECT SUM(quantity) AS total FROM cart WHERE user_id=$uid");
    $row = $res->fetch_assoc();
    $cartCount = $row['total'] ?? 0;
}

$search_term="";


?>

<link rel="stylesheet" href="../css/navigations.css">

<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&family=Poppins:wght@400;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">


<nav>
    <div class="nav-container">

        <div class="profile">
       
            <div id="displayName">
                <img src="../images/logo.png" alt="loading">
            </div>
        </div>
        <form action="search.php" method="get" class="search-bar">
            <input type="text" name="search" placeholder="Search products..." class="search-input" value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit">Search</button>
        </form>
         
       

        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="ordered_items.php">Orders</a>
                    <div class="cart">
                            <a href="cart.php">🛒 Cart
                             <span id="cart-count"><?php echo $cartCount; ?></span>
                             </a>
                    </div>
            <?php
if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] == 1) {
    echo "<a href='Admin.php'>Admin</a>";
}

?>
            <?php if(isset($_SESSION['user'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Login</a>
            <?php endif; ?>
        </div>

        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </div>
</nav>
<hr>
