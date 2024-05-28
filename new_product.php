<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $code = mysqli_real_escape_string($con, $_POST['code']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $image = mysqli_real_escape_string($con, $_POST['image']);

    $query = "INSERT INTO products (name, code, price, image, quantity) VALUES ('$name', '$code', '$price', '$image', '$quantity')";
    
    if (mysqli_query($con, $query)) {
        header("Location: dashboard.php?page=products");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}


?>


<div class="new_product">
    <h1>Add new product</h1>
    <div class="form-container">
        <form action="new_product.php" method="post">
            <div class="row">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Name" required>
            </div>
            
            <div class="row">
                <label for="code">Code:</label>
                <input type="text" id="code" name="code" placeholder="Code" required>
            </div>
            
            <div class="row">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            
            <div class="row">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="0" required>
            </div>
            
            <div class="row">
                <label for="image">Image:</label>
                <input type="url" id="image" name="image" placeholder="Insert link" required>
            </div>
            
            <button type='add' class="add_product">Add product</button>
        </form>
    </div>
</div>