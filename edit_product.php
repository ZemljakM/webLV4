<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM products WHERE id = '$product_id'");
$product = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $code = mysqli_real_escape_string($con, $_POST['code']);
        $price = mysqli_real_escape_string($con, $_POST['price']);
        $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
        $image = mysqli_real_escape_string($con, $_POST['image']);

        $query = "UPDATE products SET name='$name', code='$code', price='$price', image='$image', quantity='$quantity' WHERE id=$product_id";
        
        if (mysqli_query($con, $query)) {
            header("Location: dashboard.php?page=products");
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con);
        }
    } elseif (isset($_POST['delete'])) {
        $query = "DELETE FROM products WHERE id=$product_id";
        
        if (mysqli_query($con, $query)) {
            header("Location: dashboard.php?page=products");
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con);
        }
    }
}


?>


<div class="new_product">
    <h1>Add new product</h1>
    <div class="form-container">
        <form action="edit_product.php?id=<?php echo $product_id; ?>" method="post">
            <div class="row">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            
            <div class="row">
                <label for="code">Code:</label>
                <input type="text" id="code" name="code" value="<?php echo $product['code']; ?>" required>
            </div>
            
            <div class="row">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="row">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
            </div>
            
            <div class="row">
                <label for="image">Image:</label>
                <input type="url" id="image" name="image" value="<?php echo $product['image']; ?>" required>
            </div>
            
            <button type='submit' name="update" class="update_product">Update product</button>
            <button type='submit' name="delete" class="delete_product">Delete product</button>
        </form>
    </div>
</div>