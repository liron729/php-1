<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
// log_action($conn, $_SESSION['user_id'], 'Add to cart', 'Added product ID ' . $product_id . ' (qty ' . $quantity . ')');
// log_action($conn, $_SESSION['user_id'], 'Review', 'Reviewed product ID ' . $product_id . ' with rating ' . $rating);

$basePath = '/php-1/personalproject';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) die("<div class='container'><h2>Product not found!</h2></div>");

$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
$stmt->bind_param("i",$product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) die("<div class='container'><h2>Product not found!</h2></div>");

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_to_cart']) && isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $quantity = max(1, (int)($_POST['quantity'] ?? 1));

    $stmt = $conn->prepare("
        INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
    ");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();

    header("Location: {$basePath}/cart.php");
    exit;
}

$wishlist_msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['wishlist_action']) && isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii",$user_id,$product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows>0) {
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii",$user_id,$product_id);
        if ($stmt->execute()) $wishlist_msg = "Removed from wishlist.";
        $stmt->close();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?,?)");
        $stmt->bind_param("ii",$user_id,$product_id);
        if ($stmt->execute()) $wishlist_msg = "Added to wishlist!";
        $stmt->close();
    }
}

$review_error = $review_success = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) $review_error = "You must log in to review.";
    else {
        $user_id = (int)$_SESSION['user_id'];
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) $review_error = "Rating must be 1–5.";
        else {
            $stmt = $conn->prepare("SELECT id FROM reviews WHERE product_id=? AND user_id=? LIMIT 1");
            $stmt->bind_param("ii",$product_id,$user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows>0) $review_error = "You already reviewed this product.";
            $stmt->close();

            if (!$review_error) {
                $stmt = $conn->prepare("INSERT INTO reviews (product_id,user_id,rating,comment) VALUES (?,?,?,?)");
                $stmt->bind_param("iiis",$product_id,$user_id,$rating,$comment);
                if ($stmt->execute()) $review_success = "Review submitted!";
                else $review_error = "Failed to submit review: ".$stmt->error;
                $stmt->close();
            }
        }
    }
}

$stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at, u.username
    FROM reviews r
    LEFT JOIN users u ON r.user_id=u.id
    WHERE r.product_id=?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i",$product_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM reviews WHERE product_id=?");
$stmt->bind_param("i",$product_id);
$stmt->execute();
$rating_info = $stmt->get_result()->fetch_assoc();
$stmt->close();
$total_reviews = (int)($rating_info['total_reviews'] ?? 0);
$avg_rating = round($rating_info['avg_rating'] ?? 0,1);

?>

<div class="container">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="<?php echo $basePath; ?>/assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="product-img">
    <p>Price: $<?php echo number_format($product['price'],2); ?></p>

    <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" style="margin-top:15px;">
            <label>Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" style="width:60px;">
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
    <?php else: ?>
        <p><a href="<?php echo $basePath; ?>/login.php">Log in</a> to purchase this product.</p>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" style="margin-top:10px;">
            <button type="submit" name="wishlist_action" class="btn">
                <?php
                    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
                    $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
                    $stmt->execute();
                    $stmt->store_result();
                    echo ($stmt->num_rows>0) ? 'Remove from Wishlist' : 'Add to Wishlist';
                    $stmt->close();
                ?>
            </button>
        </form>
        <?php if($wishlist_msg): ?><p class="success"><?php echo htmlspecialchars($wishlist_msg); ?></p><?php endif; ?>
    <?php endif; ?>

    <div style="margin-top:10px;">
        <strong>Rating:</strong>
        <?php
        for($i=1;$i<=5;$i++){
            echo ($i<=$avg_rating)?'★':'☆';
        }
        ?>
        <span>(<?php echo $total_reviews;?> review<?php echo $total_reviews!=1?'s':'';?>)</span>
    </div>

    <h2>Reviews</h2>
    <?php if($review_success): ?><p class="success"><?php echo htmlspecialchars($review_success); ?></p><?php endif; ?>
    <?php if($review_error): ?><p class="error"><?php echo htmlspecialchars($review_error); ?></p><?php endif; ?>

    <?php if(empty($reviews)): ?>
        <p>No reviews yet. Be the first to review this product!</p>
    <?php else: ?>
        <?php foreach($reviews as $r): ?>
            <div class="review">
                <strong><?php echo htmlspecialchars($r['username'] ?? 'Deleted User'); ?></strong> — 
                <?php echo date("Y-m-d H:i", strtotime($r['created_at'])); ?><br>
                Rating: <?php for($i=1;$i<=5;$i++) echo ($i<=$r['rating'])?'★':'☆'; ?><br>
                <?php if(!empty($r['comment'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id'])): ?>
        <div style="margin-top:20px;">
            <h3>Leave a Review</h3>
            <form method="POST">
                <label>Rating:</label>
                <select name="rating" required>
                    <option value="">--</option>
                    <?php for($i=1;$i<=5;$i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <label>Comment (optional):</label>
                <textarea name="comment" rows="4" maxlength="2000"></textarea>
                <button type="submit" name="submit_review" class="btn">Submit Review</button>
            </form>
        </div>
    <?php else: ?>
        <p><a href="<?php echo $basePath; ?>/login.php">Log in</a> to leave a review.</p>
    <?php endif; ?>
</div>

<style>
.product-img { max-width:300px; margin-bottom:15px; }
.review { border-bottom:1px solid #ddd; padding:10px 0; }
.review p { margin:5px 0 0; }
.success { color:green; }
.error { color:red; }
.btn { background:#ff4b2b; color:white; padding:8px 12px; border:none; cursor:pointer; margin-top:5px;}
.btn:hover { background:#ff3a1a; }
</style>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
