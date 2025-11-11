<?php
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
include(__DIR__ . '/../config/db.php');


// session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<div class='container'><p>Product not found.</p></div>";
    include(__DIR__ . '/../includes/footer.php');
    exit;
}

?>

<div class="container">
  <h1><?php echo htmlspecialchars($product['name']); ?></h1>
  <img src="<?php echo $basePath; ?>/assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img" />
  <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
  <p>Price: $<?php echo number_format($product['price'], 2); ?></p>

  <form method="POST" action="<?php echo $basePath; ?>/pages/cart.php">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
    <label for="qty">Quantity:</label>
    <input type="number" name="quantity" id="qty" min="1" value="1" required />
    <button type="submit" class="btn">Add to Cart</button>
  </form>

  <h2>Reviews</h2>
  <?php
  // $reviews = $conn->query("SELECT r.rating, r.comment, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = $id ORDER BY r.created_at DESC");
  // if ($reviews->num_rows > 0) {
  //     while ($review = $reviews->fetch_assoc()) {
  //         echo "<div class='review'>";
  //         echo "<strong>" . htmlspecialchars($review['username']) . "</strong> rated: " . intval($review['rating']) . "/5";
  //         echo "<p>" . nl2br(htmlspecialchars($review['comment'])) . "</p>";
  //         echo "</div>";
  //     }
  // } else {
  //     echo "<p>No reviews yet.</p>";
  // }

  $reviews = null;

$stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $reviews = $stmt->get_result();
    }
}

if ($reviews && $reviews->num_rows > 0) {
    while ($row = $reviews->fetch_assoc()) {
        echo $row['comment'];
    }
} else {
    echo "No reviews yet.";
}

  ?>

  <?php if(isset($_SESSION['user_id'])): ?>
  <h3>Submit Review</h3>
  <form method="POST" action="<?php echo $basePath; ?>/pages/review_submit.php">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
    <label>Rating:</label>
    <select name="rating" required>
      <option value="">Select...</option>
      <?php for ($i=1; $i<=5; $i++): ?>
      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
      <?php endfor; ?>
    </select>
    <label>Comment:</label>
    <textarea name="comment" rows="4" required></textarea>
    <button type="submit" class="btn">Submit Review</button>
  </form>
  <?php else: ?>
  <p><a href="<?php echo $basePath; ?>/login.php">Log in</a> to submit a review.</p>
  <?php endif; ?>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
