<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="navbar-dash">
        <h1>Admin dashboard</h1>
        <div class="navbar-right">
            <a href="dashboard.php?page=home">Home</a>
            <a href="dashboard.php?page=products#">Products</a>
            <a href="dashboard.php?page=orders">Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
    <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'home':
                    include('home.php');
                    break;
                case 'products':
                    include('products.php');
                    break;
                case 'orders':
                    include('orders.php');
                    break;
                case 'new_product':
                    include('new_product.php');
                    break;
                case 'edit_product':
                    include('edit_product.php');
                    break;
                default:
                    echo "<p>Page not found.</p>";
            }
        } 
        ?>
    </div>
</body>
</html>
