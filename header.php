<?php
include 'config.php'; // Include database configuration

// Check if the user is logged in
if (isset($_SESSION['id'])) { // Use $_SESSION['id'] instead of $_SESSION['user_id']
    $user_id = $_SESSION['id']; // Use $_SESSION['id']
} else {
    $user_id = ''; // Set $user_id to empty if the user is not logged in
}

// Display messages if any
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">
    <div class="header-1">
        <div class="flex">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
        </div>
    </div>

    <div class="header-2">
        <div class="flex">
            <a href="home.php" class="logo">Bookly.</a>

            <nav class="navbar">
                <a href="home.php">home</a>
                <a href="about.php">about</a>
                <a href="shop.php">shop</a>
                <a href="contact.php">contact</a>
                <a href="orders.php">orders</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search_page.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php
                // Only query the cart if the user is logged in
                if ($user_id != '') {
                    $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                    $cart_rows_number = mysqli_num_rows($select_cart_number);
                } else {
                    $cart_rows_number = 0; // Set cart rows to 0 if the user is not logged in
                }
                ?>
                <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
            </div>

            <div class="user-box">
                <?php if (isset($_SESSION['username'])) : ?> <!-- Use $_SESSION['username'] instead of $_SESSION['user_name'] -->
                    <p>username : <span><?php echo $_SESSION['username']; ?></span></p>
                    <p>email : <span><?php echo $_SESSION['email']; ?></span></p> <!-- Use $_SESSION['email'] instead of $_SESSION['user_email'] -->
                    <a href="logout.php" class="delete-btn">logout</a>
                <?php else : ?>
                    <p>You are not logged in.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>