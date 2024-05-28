<?php

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

include('db.php');

$query = "SELECT * FROM orders";
$result = mysqli_query($con, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="orders">
        <h1>All Orders</h1>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <h3>Order ID: <?php echo $order['order_id']; ?></h3>
                    <p>Customer: <?php echo $order['name']; ?></p>
                    <p>User id: <?php echo $order['user_id']; ?></p>
                    <p>Address: <?php echo $order['address']; ?></p>
                    <p>Email: <?php echo $order['email']; ?></p>
                    <p>Phone: <?php echo $order['phone']; ?></p>
                    <p>Order details: <?php echo $order['order_details']; ?></p>
                    <p>Total Price: $<?php echo $order['total_price']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
</body>
</html>
