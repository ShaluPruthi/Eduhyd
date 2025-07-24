<?php
include("../connect.php");
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM staff WHERE email='$email'";
    $data = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($data);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['staff_id'] = $row['id'];
        $_SESSION['staff_name'] = $row['fname'];
        $_SESSION['staff_email'] = $row['email']; // ✅ Add this line
        echo "<script>window.location.href='http://localhost:8082/eduhyd/';</script>";
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Admin Login</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login Now</button>
            </form>
            
        </div>
    </div>
</body>
</html>
