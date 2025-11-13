<?php
// session_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
include(__DIR__ . '/../config/config.php');
include(__DIR__ . '/../config/db.php');
// log_action($conn, $_SESSION['user_id'], 'Wishlist update', 'Modified wishlist for product ID ' . $product_id);



if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT p.id, p.name, p.price, p.image FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="container">
    <h1>Your Wishlist</h1>
    <?php if ($result->num_rows === 0): ?>
        <p>Your wishlist is empty.</p>
    <?php else: ?>
        <div class="product-grid">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-cardd">
                    <img src="<?php echo htmlspecialchars($basePath); ?>/assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <a href="<?php echo htmlspecialchars($basePath); ?>/pages/product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$result->free();
$stmt->close();
$conn->close();
include(__DIR__ . '/../includes/footer.php');
?>