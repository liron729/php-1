<?php
session_start();
include('../config/db.php');
include('../includes/header.php');
include('../includes/navbar.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch cart from session (replace with your actual cart logic)
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<section class='content'><h2>Checkout</h2><p>Your cart is empty!</p></section>";
    include('../includes/footer.php');
    exit;
}

// Calculate total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert order
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'Pending')");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Optional: insert items into order_items table if exists
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($cart as $item) {
        if (isset($item['id'], $item['quantity'], $item['price'])) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Clear cart
unset($_SESSION['cart']);
?>

<section class="content">
    <h2>Checkout</h2>
    <p>Order placed successfully! Your order ID is <strong><?php echo $order_id; ?></strong>.</p>
    <p><a href="index.php">Continue shopping</a></p>
</section>

<?php include('../includes/footer.php'); ?>
