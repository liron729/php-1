<?php
include(__DIR__ . '/../config/config.php');
session_start();
?>
<header>
  <div class="container">
    <a href="<?php echo $basePath; ?>/index.php" class="logo">AudiVerse</a>
    <nav>
      <a href="<?php echo $basePath; ?>/index.php">Home</a>
      <a href="<?php echo $basePath; ?>/pages/about.php">About</a>
      <a href="<?php echo $basePath; ?>/pages/contact.php">Contact</a>
      <a href="<?php echo $basePath; ?>/pages/cart.php">Cart</a>
      <a href="<?php echo $basePath; ?>/pages/wishlist.php">Wishlist</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $basePath; ?>/logout.php">Logout</a>
      <?php else: ?>
        <a href="<?php echo $basePath; ?>/login.php">Login</a>
        <a href="<?php echo $basePath; ?>/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
