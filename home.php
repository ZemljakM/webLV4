<?php

//include('functions.php');

include('db.php');

if (isset($_GET['getProducts']) && $_GET['getProducts'] == 'true') {
    $result = mysqli_query($con, "SELECT * FROM `products`");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($products);
    exit;
}

if (isset($_GET['success']) && $_GET['success'] == 'true'){
    echo "<script>document.addEventListener('DOMContentLoaded', function() { document.querySelector('.success-message').style.display = 'block'; });</script>";
}

$isAdmin = isset($_SESSION['user_logged_in']) && $_SESSION['is_admin'];

include('index.html');
?>

<?php if (!$isAdmin): ?>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('logout-button').style.display = 'block';
    });
</script>
<?php endif; ?>