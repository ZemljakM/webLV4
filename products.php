<?php

include('db.php');
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM products");
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<div class="products">
    <div class="navbar">
      <h1>Products</h1>
      <div class = "navbar-right">
        <div class="cart-container">
          <a href="dashboard.php?page=new_product" class="new-product-button">Add new product</a>
        </div>
      </div>
      
    </div>
    <?php if (count($products) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Update product</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo "$".$product['price']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><a href="dashboard.php?page=edit_product&id=<?php echo $product['id']; ?>" class="update_product">Update</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No products.</p>
    <?php endif; ?>
</div>
