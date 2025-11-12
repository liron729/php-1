<?php
session_start();
include(__DIR__.'/../includes/header.php');
include(__DIR__.'/../includes/navbar.php');
include(__DIR__.'/../config/db.php');
include(__DIR__.'/../includes/functions.php');
// log_action($conn, $user_id, 'Checkout', 'Placed order #' . $order_id . ' total $' . number_format($total_price, 2));

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    die("<div class='container'><h2>Your cart is empty.</h2></div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_price = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart_items));

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("id", $user_id, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // log_action($conn, $user_id, "Checkout", "Placed order #$order_id totaling $$total_price");

    echo "<div class='container'><h2>Order placed successfully!</h2>
          <p>Your order #$order_id has been created.</p></div>";
    include(__DIR__.'/../includes/footer.php');
    exit;
}
?>

<div class="container">
    <h1>Checkout</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; foreach ($cart_items as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <form method="POST" style="margin-top:15px;">
        <button type="submit" class="btn" style="background:#00b4d8;">Place Order</button>
    </form>
</div>

<?php include(__DIR__.'/../includes/footer.php'); ?>
