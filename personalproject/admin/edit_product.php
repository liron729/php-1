<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $name, $price, $image, $id);

    if ($stmt->execute()) {
        $success = "Product updated successfully!";
    } else {
        $error = "Error updating product: " . $conn->error;
    }

    $stmt->close();
}
?>

<div class="container">
    <h1>Edit Product</h1>

    <?php if ($success): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required />

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required />

        <label>Image Filename:</label>
        <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required />

        <button type="submit" class="btn">Update Product</button>
    </form>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
