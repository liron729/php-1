<?php
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/navbar.php');
include(__DIR__ . '/config/config.php');
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<div class="container">
  <h1>Welcome to AudiVerse</h1>
  <p>Your one-stop shop for Audi parts.</p>

  <?php
  include(__DIR__ . '/config/db.php');
  $result = $conn->query("SELECT * FROM products LIMIT 6");
  echo "<div class='product-grid'>";
  
  if ($result && $result->num_rows > 0) {
      while($product = $result->fetch_assoc()) {
          echo "<div class='product-card'>";
          echo "<img src='" . htmlspecialchars($basePath) . "/assets/images/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['name']) . "'>";
          echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
          echo "<p>$" . number_format($product['price'], 2) . "</p>";
          echo "<a href='" . htmlspecialchars($basePath) . "/pages/product.php?id=" . htmlspecialchars($product['id']) . "' class='btn'>View Details</a>";
          echo "</div>";
      }
      $result->free();
  } else {
      echo "<p>No products found at the moment.</p>";
  }
  
  echo "</div>";
  $conn->close();
  ?>
</div>

<?php include(__DIR__ . '/includes/footer.php'); ?>