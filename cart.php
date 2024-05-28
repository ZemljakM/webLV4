<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');
if (!isset($_SESSION['user_logged_in'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($result);

$result_pr = mysqli_query($con, "SELECT * FROM products");
$products = mysqli_fetch_all($result_pr, MYSQLI_ASSOC);
$finish_order = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $cartData = json_decode($_POST['cart'], true);

    if ($cartData !== null) {
        $cartItems = $cartData['items'];
        $total_price = $cartData['totalPrice'];
    }
    $orderDetails = "";
    foreach ($cartItems as $item) {
        $itemName = $item['name'];
        $itemQuantity = $item['quantity'];
        $itemPrice = $item['price'];
        $orderDetails .= "{$itemQuantity}x {$itemName}, ";
        $available_quantity = 0;
        foreach($products as $product){
            if($product['name'] == $itemName){
                if($itemQuantity > $product['quantity']){
                    $finish_order = false;
                }
            }
        }
    }
    $orderDetails = rtrim($orderDetails, ', ');

    if($finish_order == true){
        $query = "INSERT INTO orders (user_id, name, address, email, phone, order_details, total_price)
            VALUES ('$user_id', '$name', '$address', '$email', '$phone', '$orderDetails', '$total_price')";
        if (mysqli_query($con, $query)) {
            foreach ($cartItems as $item) {
                foreach ($products as &$product) {
                    if ($product['name'] === $itemName) {
                        $product['quantity'] -= $itemQuantity;
                        $productId = $product['id'];
                        mysqli_query($con, "UPDATE products SET quantity = '{$product['quantity']}' WHERE id = '$productId'");
                    }
                }
            }
            header("Location: home.php?success=true");
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con);
        }
    }
    else{
        echo "The current quantity of the product you want is not available.";
    }
        
    
    
    
    
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="cart-container-cartPage">
        <div class="order-form-container">
                <form id="order-form" action="cart.php" method="post">
                    <div class="cart-items">
                        <h1>Your Cart</h1>
                        <ul class="cart-items-list"></ul>
                        <div class="total">
                            <p>Total: <span id="cart-total" name="cart-total">$0.00</span></p>
                        </div>
                    </div>
                    <h1>Order Information</h1>
                    <div class="form-container">
                        <div class="row">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class="row">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" placeholder="Address" required>
                        </div>
                        <div class="row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" readonly required>
                        </div>
                        <div class="row">
                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone" placeholder="Phone number" required>
                        </div>
                        <input type="hidden" id="cart" name="cart" value="">
                        <button type="submit" class="confirm-order-btn">Confirm Purchase</button>
                    </div>
                </form>
            
        </div>
    </div>

    <script>
        const cartItemsList = document.querySelector('.cart-items-list');
        const cartTotal = document.getElementById('cart-total');
        const cart = JSON.parse(localStorage.getItem('cartItems')) || [];

        function showCart() {
            cartItemsList.innerHTML = '';
            cart.forEach(item => {
                const cartItemElement = document.createElement('li');
                cartItemElement.innerHTML = `
                    <span >${item.name} - Quantity: ${item.quantity} - Price: $${(item.price * item.quantity).toFixed(2)}</span>`;
                cartItemsList.appendChild(cartItemElement);

            });

            const totalPrice = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            cartTotal.textContent = `$${totalPrice.toFixed(2)}`;

            const cartData = {
                items: cart,
                totalPrice: totalPrice
            };
            document.getElementById('cart').value = JSON.stringify(cartData);
        }

        showCart();
    </script>
</body>
</html>
