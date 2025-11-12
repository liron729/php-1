<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}
?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <div class="admin-grid" style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">
        <a class="btn" href="add_product.php">Add Product</a>
        <a class="btn" href="manage_products.php">Manage Products</a>
        <a class="btn" href="manage_orders.php">Manage Orders</a>
        <a class="btn" href="manage_users.php">Manage Users</a>
        <!-- <a class="btn" href="logs.php">Logs</a> -->
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
