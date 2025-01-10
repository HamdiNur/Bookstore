<?php
session_start(); // Start the session
include 'config.php'; // Include database configuration

$message = []; // Array to store messages

// Check if the user is authorized to reset their password
if (!isset($_SESSION['reset_user_id'])) {
    header('location: forgot_password.php'); // Redirect if not authorized
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password'])); // Hash the new password
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password'])); // Hash the confirm password

    // Validate that the new password and confirm password match
    if ($new_password != $confirm_password) {
        $message[] = 'New password and confirm password do not match!';
    } else {
        // Update the user's password in the database
        $user_id = $_SESSION['reset_user_id'];
        mysqli_query($conn, "UPDATE `userss` SET password = '$new_password' WHERE id = '$user_id'") or die('Query failed');

        // Clear the session and redirect to the login page
        unset($_SESSION['reset_user_id']);
        $message[] = 'Password updated successfully!';
        header('location: login.php'); // Redirect to login page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    // Display error messages
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>

    <div class="form-container">
        <form action="" method="post">
            <h3>Reset Password</h3>
            <!-- New Password Input -->
            <input type="password" name="new_password" placeholder="Enter your new password" required class="box">
            <!-- Confirm Password Input -->
            <input type="password" name="confirm_password" placeholder="Confirm your new password" required class="box">
            <!-- Submit Button -->
            <input type="submit" name="submit" value="Reset Password" class="btn">
            <!-- Back to Login Link -->
            <p>Remember your password? <a href="login.php">Login now</a></p>
        </form>
    </div>
</body>
</html>