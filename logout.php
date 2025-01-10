<?php
session_start(); // Start the session
include 'config.php'; // Include database configuration

// Destroy session
session_unset();
session_destroy();

// Delete "Remember Me" cookie
if (isset($_COOKIE['remember_token'])) {
    // Clear the token from the database
    $token = $_COOKIE['remember_token'];
    mysqli_query($conn, "UPDATE `userss` SET remember_token = NULL WHERE remember_token = '$token'") or die('Query failed: ' . mysqli_error($conn));

    // Delete the cookie
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to login page with a query parameter
header('location: login.php?session_expired=true');
exit();
?>