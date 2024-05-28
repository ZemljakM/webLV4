<?php
session_start();
include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);
    
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_logged_in'] = true;
        
        if ($user['email'] == 'admin@admin.com') {
            $_SESSION['is_admin'] = true;
            setcookie('user_logged_in', true, time() + (30 * 60), "/");
            setcookie('user_id', $user['id'], time() + (30 * 60), "/");
            setcookie('is_admin', $user['is_admin'], time() + (30 * 60), "/");
            header("Location: dashboard.php");
        } else {
            $_SESSION['is_admin'] = false;
            header("Location: home.php");
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="login">
    <h1>Login</h1>
    <form method="POST" action="index.php">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" class="submit-btn">Login</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
