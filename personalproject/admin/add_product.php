<?php
include('../../config/db.php');
include('../../includes/header.php');
include('../../includes/navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $cat = $_POST['category'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $image = $_POST['image'];

  $stmt = $conn->prepare("INSERT INTO products (name,category_id,description,price,stock,image) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param("sisdis", $name, $cat, $desc, $price, $stock, $image);
  $stmt->execute();
  header("Location: manage_products.php");
}

$cats = $conn->query("SELECT * FROM categories");
?>
<section class="admin">
<h2>Add New Product</h2>
<form method="POST" style="max-width:500px;">
  <input type="text" name="name" placeholder="Product name" required><br>
  <select name="category">
    <?php while($c=$cats->fetch_assoc()) echo "<option value='{$c['id']}'>{$c['name']}</option>"; ?>
  </select><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input type="number" step="0.01" name="price" placeholder="Price" required><br>
  <input type="number" name="stock" placeholder="Stock" required><br>
  <input type="text" name="image" placeholder="Image filename (e.g. rs6_exhaust.jpg)"><br>
  <button type="submit">Add Product</button>
</form>
</section>
<?php include('../../includes/footer.php'); ?>
