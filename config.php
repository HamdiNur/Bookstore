<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn=mysqli_connect('localhost','root','root','shops_db') or die('connection fail');





?>