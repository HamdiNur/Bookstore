<?php
session_start(); // Start the session
include 'config.php'; // Include database configuration

// Check if the user is logged in
$is_logged_in = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    // Display messages if any
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>
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
                <a href="#" class="logo">Bookly.</a>

                <nav class="navbar">
                    <a href="#">home</a>
                    <?php if ($is_logged_in): ?>
                        <a href="#">about</a>
                        <a href="#">shop</a>
                        <a href="#">contact</a>
                        <a href="#">orders</a>
                    <?php else: ?>
                        <a href="javascript:void(0);" onclick="alert('Please login or register to access this page.')">about</a>
                        <a href="javascript:void(0);" onclick="alert('Please login or register to access this page.')">shop</a>
                        <a href="javascript:void(0);" onclick="alert('Please login or register to access this page.')">contact</a>
                        <a href="javascript:void(0);" onclick="alert('Please login or register to access this page.')">orders</a>
                    <?php endif; ?>
                </nav>

                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                    <a href="#" class="fas fa-search"></a>
                    <div id="user-btn" class="fas fa-user"></div>
                    <?php
                    // Only query the cart if the user is logged in
                    if ($is_logged_in) {
                        $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '{$_SESSION['id']}'") or die('query failed');
                        $cart_rows_number = mysqli_num_rows($select_cart_number);
                    } else {
                        $cart_rows_number = 0; // Set cart rows to 0 if the user is not logged in
                    }
                    ?>
                    <a href="#"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
                </div>

                <div class="user-box">
                    <?php if ($is_logged_in): ?>
                        <p>username : <span><?php echo $_SESSION['username']; ?></span></p>
                        <p>email : <span><?php echo $_SESSION['email']; ?></span></p>
                        <a href="logout.php" class="delete-btn">logout</a>
                    <?php else: ?>
                        <p>You are not logged in.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <section class="home">
        <div class="content">
            <h3>Hand Picked Book to your door.</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, quod? Reiciendis ut porro iste totam.</p>
            <?php if ($is_logged_in): ?>
                <a href="about.php" class="white-btn">discover more</a>
            <?php else: ?>
                <button type="button" onclick="alert('Please login or register to access this page.')" class="white-btn">discover more</button>
            <?php endif; ?>
        </div>
    </section>

    <section class="products">
        <h1 class="title">latest products</h1>
        <div class="box-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
            <form action="" method="post" class="box">
                <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
                <input type="number" min="1" name="product_quantity" value="1" class="qty">
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <?php if ($is_logged_in): ?>
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                <?php else: ?>
                    <button type="button" onclick="alert('Please login or register to add items to cart.')" class="btn">add to cart</button>
                <?php endif; ?>
            </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
        <div class="load-more" style="margin-top: 2rem; text-align:center">
            <?php if ($is_logged_in): ?>
                <a href="shop.php" class="option-btn">load more</a>
            <?php else: ?>
                <button type="button" onclick="alert('Please login or register to access this page.')" class="option-btn">load more</button>
            <?php endif; ?>
        </div>
    </section>

    <section class="about">
        <div class="flex">
            <div class="image">
                <img src="images/about-img.jpg" alt="">
            </div>
            <div class="content">
                <h3>about us</h3>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
                <?php if ($is_logged_in): ?>
                    <a href="about.php" class="btn">read more</a>
                <?php else: ?>
                    <button type="button" onclick="alert('Please login or register to access this page.')" class="btn">read more</button>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="home-contact">
        <div class="content">
            <h3>have any questions?</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
            <?php if ($is_logged_in): ?>
                <a href="contact.php" class="white-btn">contact us</a>
            <?php else: ?>
                <button type="button" onclick="alert('Please login or register to access this page.')" class="white-btn">contact us</button>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>
</body>
</html>