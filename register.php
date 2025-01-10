<?php 
include 'config.php';

if(isset($_POST['submit'])){
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
  
    $userType = $_POST['user_type'];
    $userStatus = $_POST['user_status'];

    // Handle profile picture upload
    $profilePicture = $_FILES['profile_picture']['name'];
    $profilePictureTmp = $_FILES['profile_picture']['tmp_name'];
    $profilePicturePath = "uploads/" . basename($profilePicture);

    // Check if email or username already exists
    $selectUsers = mysqli_query($conn, "SELECT * FROM `userss` WHERE email = '$email' OR username = '$username'") or die('Query failed');
    
    if(mysqli_num_rows($selectUsers) > 0){
        $message[] = 'User already exists!';
    } else {
        // Validate password and confirm password
        if($password != $cpass){
            $message[] = 'Confirm password does not match!';
        } else {
            if(move_uploaded_file($profilePictureTmp, $profilePicturePath)){
                // Insert user data into the database
                mysqli_query($conn, "INSERT INTO `userss` (first_name, last_name, sex, username, password, phone, email, profile_picture, user_type, user_status) 
                VALUES ('$firstName', '$lastName', '$sex', '$username', '$password', '$phone', '$email', '$profilePicture', '$userType', '$userStatus')") or die('Query failed');
                $message[] = 'Registered successfully!';
                header('location: login.php'); // Redirect to login page after registration
                exit();
            } else {
                $message[] = 'Failed to upload profile picture!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    if(isset($message)){
        foreach ($message as $msg){
            echo '
            <div class="message">
                <span>'.$msg.'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>
    
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
            <!-- First Name and Last Name in the same row -->
            <div class="row">
                <input type="text" name="first_name" placeholder="Enter your first name" required class="box">
                <input type="text" name="last_name" placeholder="Enter your last name" required class="box">
            </div>
            <!-- Sex and Username in the same row -->
            <div class="row">
                <input type="text" name="username" placeholder="Enter your username" required class="box">
                <select name="sex" class="box" required>
                    <option value="">Select sex</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <!-- Phone and Email in the same row -->
            <div class="row">
                <input type="email" name="email" placeholder="Enter your email" required class="box">
                <input type="text" name="phone" placeholder="Enter your phone number" required class="box">
            </div>
            <!-- Password and Confirm Password in the same row -->
            <div class="row">
                <input type="password" name="password" placeholder="Enter your password" required class="box">
                <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
            </div>
            <!-- Profile Picture (single row) -->
            <div>
                <input type="file" name="profile_picture" accept="image/*" class="box">
            </div>
            <!-- User Type and User Status (parallel) -->
            <div class="row">
                <select name="user_type" class="box" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <select name="user_status" class="box" required>
                    <option value="">User Status</option>
                    <option value="active">Active</option>
                    <option value="not active">Not Active</option>
                </select>
            </div>
            
<div class="btn-container">
   <input type="submit" name="submit" value="Register Now" class="btn">
   <input type="reset" value="Reset" class="btn">
</div>

            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </div>
</body>
</html>