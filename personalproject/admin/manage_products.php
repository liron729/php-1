<?php
session_start(); // FIX: Start session
include('../../config/db.php');
include('../../includes/functions.php'); // FIX: Include functions for auth
include('../../includes/header.php');
include('../../includes/navbar.php');

// FIX: Enforce admin access
if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['delete'])) {
    // FIX: Secured DELETE query using a prepared statement to prevent SQL injection
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id !== false && $id > 0) {
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }
    // FIX: Redirect to clean URL after action
    header("Location: manage_products.php");
    exit;
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
<?php 
// FIX: Check if query was successful and has rows
if ($result && $result->num_rows > 0): 
    while($row = $result->fetch_assoc()): 
?>
<tr>
  <td><?= htmlspecialchars($row['id']) ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td>$<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
  <td><?= htmlspecialchars($row['stock']) ?></td>
  <td>
    <a href="edit_product.php?id=<?= htmlspecialchars($row['id']) ?>">Edit</a> |
    <a href="?delete=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Delete this product?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
<?php 
$result->free(); // FIX: Free result set
else: 
?>
<tr><td colspan="5">No products found.</td></tr>
<?php endif; ?>
</table>
</section>
<?php 
$conn->close(); // FIX: Close database connection
include('../../includes/footer.php'); 
?>