<?php


// Set session lifetime to 5 minutes (300 seconds)
ini_set('session.gc_maxlifetime', 300);
ini_set('session.cookie_lifetime', 300);
// Start the session
session_start(); 

include 'config.php'; // Include database configuration

// Check if the session has expired and the user is redirected
if (isset($_GET['session_expired']) && $_GET['session_expired'] === 'true') {
    echo '<script>alert("Your session has expired. Please log in again.");</script>';
}

// Check if the user is already logged in via "Remember Me" cookie
if (isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
    $token = $_COOKIE['remember_token'];

    // Query to validate the token
    $select_user = mysqli_query($conn, "SELECT * FROM `userss` WHERE remember_token = '$token'") or die('Query failed: ' . mysqli_error($conn));

    if (mysqli_num_rows($select_user) > 0) {
        $row = mysqli_fetch_assoc($select_user);

        // Set session variables
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['last_activity'] = time(); // Track last activity time

        // Redirect based on user_type
        if ($row['user_type'] == 'admin') {
            header('location: admin_page.php'); // Redirect to admin dashboard
        } else {
            header('location: home.php'); // Redirect to user home page
        }
        exit();
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Collect Input Data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); // Consider using password_hash() and password_verify() for better security
    $remember = isset($_POST['remember']); // Check if "Remember Me" is checked

    // Query to check user credentials
    $select_users = mysqli_query($conn, "SELECT * FROM `userss` WHERE email = '$email' AND password = '$pass'") or die('Query failed: ' . mysqli_error($conn));

    if (mysqli_num_rows($select_users) > 0) {
        $row = mysqli_fetch_assoc($select_users);

        // Set session variables based on user type
        $_SESSION['id'] = $row['id']; // Use 'id' for both admin and user
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_type'] = $row['user_type']; // Store user_type in session
        $_SESSION['last_activity'] = time(); // Track last activity time

        // Set admin_id for admin users
        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
        }

        // Regenerate session ID for security
        session_regenerate_id(true);

        // Set "Remember Me" cookie if checked
        if ($remember) {
            $token = bin2hex(random_bytes(32)); // Generate a secure token
            $expiry = time() + (86400 * 30); // 30 days

            // Save token in database
            mysqli_query($conn, "UPDATE `userss` SET remember_token = '$token' WHERE id = '{$row['id']}'") or die('Query failed: ' . mysqli_error($conn));

            // Set cookie
            setcookie('remember_token', $token, $expiry, '/');
        }

        // Redirect based on user_type
        if ($row['user_type'] == 'admin') {
            header('location: admin_page.php'); // Redirect to admin dashboard
        } else {
            header('location: home.php'); // Redirect to user home page
        }
        exit();
    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h3>Login Now</h3>
            <!-- Email Input -->
            <input type="email" name="email" placeholder="Enter your email" required class="box">
            <!-- Password Input -->
            <input type="password" name="password" placeholder="Enter your password" required class="box">
            <!-- Remember Me Checkbox -->
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>
            <!-- Submit Button -->
            <input type="submit" name="submit" value="Login Now" class="btn">
            <!-- Forgot Password Link -->
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            <!-- Register Link -->
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </div>
</body>
</html>