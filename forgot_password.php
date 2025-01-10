<?php
include 'config.php'; // Include database configuration

$message = []; // Array to store messages

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email exists in the database
    $select_user = mysqli_query($conn, "SELECT * FROM `userss` WHERE email = '$email'") or die('Query failed');

    if (mysqli_num_rows($select_user) > 0) {
        // Email exists, allow the user to reset their password
        $row = mysqli_fetch_assoc($select_user);
        $user_id = $row['id']; // Get the user's ID

        // Store the user's ID in a session for password reset
        session_start();
        $_SESSION['reset_user_id'] = $user_id;

        // Redirect to the reset password page
        header('location: reset_password.php');
        exit();
    } else {
        $message[] = 'Email not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            <h3>Forgot Password</h3>
            <!-- Email Input -->
            <input type="email" name="email" placeholder="Enter your email" required class="box">
            <!-- Submit Button -->
            <input type="submit" name="submit" value="Reset Password" class="btn">
            <!-- Back to Login Link -->
            <p>Remember your password? <a href="login.php">Login now</a></p>
        </form>
    </div>
</body>
</html>