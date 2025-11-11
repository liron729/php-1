
<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="about.php">About</a></li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="wishlist.php">Wishlist</a></li>

        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <!-- Admin-only links -->
            <li><a href="../admin/add_product.php">Add Product</a></li>
            <li><a href="../admin/manage_orders.php">Manage Orders</a></li>
            <li><a href="../admin/manage_users.php">Manage Users</a></li>
        <?php endif; ?>

        <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
    <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>
