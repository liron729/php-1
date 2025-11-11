<?php
// session_start(); // FIX: Start session
// include('../../config/db.php');
// include('../../includes/functions.php'); // Required for isAdmin()
// include('../../includes/header.php');
// include('../../includes/navbar.php');
?>

<?php
session_start();

// Correct include paths (only one level up)
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/functions.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

// Check admin access
if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}
?>
<?php
// // FIX: Enforce admin access
// if (!isAdmin()) {
//     header("Location: ../login.php");
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // FIX: Sanitize and validate inputs
  $name = trim($_POST['name'] ?? '');
  $cat = filter_var($_POST['category'] ?? 0, FILTER_VALIDATE_INT); 
  $desc = trim($_POST['description'] ?? '');
  $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT); 
  $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT);
  $image = trim($_POST['image'] ?? '');

  if ($cat !== false && $price !== false && $stock !== false && !empty($name)) {
      $stmt = $conn->prepare("INSERT INTO products (name, category_id, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sisdis", $name, $cat, $desc, $price, $stock, $image);
      
      $stmt->execute();
      $stmt->close(); 
  }
  
  $conn->close(); 
  header("Location: manage_products.php");
  exit;
}

$cats = $conn->query("SELECT * FROM categories");
?>
<section class="admin">
<h2>Add New Product</h2>
<form method="POST" style="max-width:500px;">
  <input type="text" name="name" placeholder="Product name" required><br>
  <select name="category">
    <?php 
    if ($cats) {
        // FIX: Sanitize category output
        while($c=$cats->fetch_assoc()) echo "<option value='" . htmlspecialchars($c['id']) . "'>" . htmlspecialchars($c['name']) . "</option>";
        $cats->free(); 
    }
    ?>
  </select><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input type="number" step="0.01" name="price" placeholder="Price" required><br>
  <input type="number" name="stock" placeholder="Stock" required><br>
  <input type="text" name="image" placeholder="Image filename (e.g. rs6_exhaust.jpg)"><br>
  <button type="submit">Add Product</button>
</form>
</section>
<?php 
if ($conn->ping()) {
    $conn->close();
}
include('../../includes/footer.php'); 
?>