<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<div class="container">
    <h1>Manage Products</h1>
    <a class="btn" href="add_product.php">+ Add New Product</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td>$<?php echo number_format($product['price'],2); ?></td>
                <td><?php echo htmlspecialchars($product['image']); ?></td>
                <td>
                    <a class="btn" href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                    <a class="btn" href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
