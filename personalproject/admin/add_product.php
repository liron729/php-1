<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$success = $error = '';

$cat_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $image = $_POST['image'] ?? '';
    $category_id = $_POST['category_id'] ?? null;

    if (!$category_id) {
        $error = "Please select a category!";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $name, $price, $image, $category_id);

        if ($stmt->execute()) {
            $success = "Product '$name' added successfully!";
        } else {
            $error = "Error adding product: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<div class="container">
    <h1>Add Product</h1>

    <?php if ($success): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" required />

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required />

        <label>Image Filename:</label>
        <input type="text" name="image" required />

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while($cat = $cat_result->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
