<?php
session_start(); // Start the session
include 'config.php'; // Include database configuration

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header('location: login.php'); // Redirect to login page if not logged in or not an admin
    exit();
}





// Fetch user data from the database
$admin_id = $_SESSION['id'];
$select_user = mysqli_query($conn, "SELECT * FROM `userss` WHERE id = '$admin_id'") or die('Query failed: ' . mysqli_error($conn));

if (mysqli_num_rows($select_user) > 0) {
    $user_data = mysqli_fetch_assoc($select_user);
} else {
    die("User not found in the database.");
}

// Extract user data with fallbacks
$username = $user_data['username'] ?? 'Guest';
$email = $user_data['email'] ?? 'No email';
$profile_picture = $user_data['profile_picture'] ?? 'default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <header class="header">
        <div class="flex">
            <!-- Admin Panel Logo -->
            <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

            <!-- User Profile Section -->
            <div class="user-profile">
                <div class="profile-picture">
                    <img src="uploads/<?php echo $profile_picture; ?>" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <p class="username"><?php echo $username; ?></p>
                    <p class="email"><?php echo $email; ?></p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="navbar">
            <a href="#" id="dashboard-btn"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>

                <a href="admin_page.php" id="home-btn"><i class="fas fa-home"></i> <span>home</span></a>
                <a href="admin_products.php"><i class="fas fa-box"></i> <span>products</span></a>
                <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> <span>orders</span></a>
                <a href="admin_users.php"><i class="fas fa-users"></i> <span>users</span></a>
                <a href="admin_contacts.php"><i class="fas fa-envelope"></i> <span>messages</span></a>
            </nav>

            <!-- Logout Section -->
            <div class="logout-section">
                <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> <span>logout</span></a>
            </div>
        </div>
    </header>

    <section class="dashboard">

<h1 class="title">dashboard</h1>

<div class="box-container">

   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
         if(mysqli_num_rows($select_pending) > 0){
            while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
               $total_price = $fetch_pendings['total_price'];
               $total_pendings += $total_price;
            };
         };
      ?>
      <h3>$<?php echo $total_pendings; ?>/-</h3>
      <p>total pendings</p>
   </div>

   <div class="box">
      <?php
         $total_completed = 0;
         $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
         if(mysqli_num_rows($select_completed) > 0){
            while($fetch_completed = mysqli_fetch_assoc($select_completed)){
               $total_price = $fetch_completed['total_price'];
               $total_completed += $total_price;
            };
         };
      ?>
      <h3>$<?php echo $total_completed; ?>/-</h3>
      <p>completed payments</p>
   </div>

   <div class="box">
      <?php 
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
         $number_of_orders = mysqli_num_rows($select_orders);
      ?>
      <h3><?php echo $number_of_orders; ?></h3>
      <p>order placed</p>
   </div>

   <div class="box">
      <?php 
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         $number_of_products = mysqli_num_rows($select_products);
      ?>
      <h3><?php echo $number_of_products; ?></h3>
      <p>products added</p>
   </div>

   <div class="box">
      <?php 
         $select_users = mysqli_query($conn, "SELECT * FROM `userss` WHERE user_type = 'user'") or die('query failed');
         $number_of_users = mysqli_num_rows($select_users);
      ?>
      <h3><?php echo $number_of_users; ?></h3>
      <p>normal users</p>
   </div>

   <div class="box">
      <?php 
         $select_admins = mysqli_query($conn, "SELECT * FROM `userss` WHERE user_type = 'admin'") or die('query failed');
         $number_of_admins = mysqli_num_rows($select_admins);
      ?>
      <h3><?php echo $number_of_admins; ?></h3>
      <p>admin users</p>
   </div>

   <div class="box">
      <?php 
         $select_account = mysqli_query($conn, "SELECT * FROM `userss`") or die('query failed');
         $number_of_account = mysqli_num_rows($select_account);
      ?>
      <h3><?php echo $number_of_account; ?></h3>
      <p>total accounts</p>
   </div>

   <div class="box">
      <?php 
         $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
         $number_of_messages = mysqli_num_rows($select_messages);
      ?>
      <h3><?php echo $number_of_messages; ?></h3>
      <p>new messages</p>
   </div>

</div>

</section>
    <!-- Custom Modal for Session Expiry -->
    <div id="sessionExpiredModal" class="modal">
        <div class="modal-content">
            <p>Your session has expired. You will be redirected to the login page.</p>
            <button id="okButton">OK</button>
        </div>
    </div>


    <!-- Custom admin JS file link -->
    <script src="js/admin_script.js"></script>
    
</body>
</html>