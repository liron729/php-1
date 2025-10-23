<?php
include('../../config/db.php');
include('../../includes/header.php');
include('../../includes/navbar.php');

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

$result = $conn->query("SELECT * FROM products");
?>
<section class="admin">
<h2>Manage Products</h2>
<a href="add_product.php" class="btn">+ Add New Product</a>

<table border="1" cellpadding="10" cellspacing="0" style="margin-top:20px;width:90%;background:#fff;">
<tr>
<th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['name'] ?></td>
  <td>$<?= $row['price'] ?></td>
  <td><?= $row['stock'] ?></td>
  <td>
    <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</section>
<?php include('../../includes/footer.php'); ?>
