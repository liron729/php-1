<?php
include('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

$slug = $_GET['slug'] ?? '';
$stmt = $conn->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$cat = $stmt->get_result()->fetch_assoc();

if (!$cat) {
  echo "<h2>Category not found.</h2>";
  include('../includes/footer.php');
  exit;
}

echo "<section class='products'>";
echo "<h2>{$cat['name']} Parts</h2>";

$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->bind_param("i", $cat['id']);
$stmt->execute();
$res = $stmt->get_result();

echo "<div class='product-grid'>";
while ($row = $res->fetch_assoc()) {
  echo "
  <div class='product-card'>
    <img src='../assets/images/{$row['image']}' alt='{$row['name']}'>
    <h3>{$row['name']}</h3>
    <p>\${$row['price']}</p>
    <a href='product.php?id={$row['id']}' class='btn'>View</a>
  </div>";
}
echo "</div></section>";

include('../includes/footer.php');
?>
