<?php
// Start the session only if it's not already active

// Start the session
include 'config.php'; // Include database configuration

// Debug: Print session variables
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// Check if admin_id is set in the session
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php'); // Redirect to login page
    exit(); // Stop further execution
}

// Fetch user data from the database using 'admin_id'
$admin_id = $_SESSION['admin_id'];
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
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        /* Add styles for collapsed sidebar */
        .collapsed {
            width: 60px; /* Adjust width for collapsed state */
            overflow: hidden;
        }
        .collapsed .navbar a span,
        .collapsed .user-profile .profile-info,
        .collapsed .logout-section .logout-link span {
            display: none; /* Hide text when collapsed */
        }
        .collapsed .navbar a i,
        .collapsed .user-profile .profile-picture,
        .collapsed .logout-section .logout-link i {
            display: block; /* Ensure icons are visible */
        }
    </style>
</head>
<body>
<header class="header collapsed"> <!-- Add 'collapsed' class here -->
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
         <a href="admin_page.php"><i class="fas fa-home"></i> <span>Home</span></a>
         <a href="admin_products.php"><i class="fas fa-box"></i> <span>Products</span></a>
         <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a>
         <a href="admin_users.php"><i class="fas fa-users"></i> <span>Users</span></a>
         <a href="admin_contacts.php"><i class="fas fa-envelope"></i> <span>Messages</span></a>
      </nav>

      <!-- Logout Section -->
      <div class="logout-section">
         <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
      </div>
   </div>
</header>











        <!-- Custom admin JS file link -->
        <script src="js/admin_script.js"></script>
</body>
</html>