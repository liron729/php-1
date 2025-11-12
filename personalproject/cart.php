<?php
session_start();
include(__DIR__.'/includes/header.php');
include(__DIR__.'/includes/navbar.php');
include(__DIR__.'/config/db.php');

$basePath = '/php-1/personalproject';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD']==='POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    if(isset($_POST['add_to_cart']) || isset($_POST['update_quantity'])) {
        $quantity = max(1,(int)($_POST['quantity']??1));
        $stmt = $conn->prepare("
            INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
        ");
        $stmt->bind_param("iii",$user_id,$product_id,$quantity);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['remove'])) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii",$user_id,$product_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image, c.quantity
    FROM cart c
    JOIN products p ON c.product_id=p.id
    WHERE c.user_id=?
");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container">
<h1>Your Cart</h1>

<?php if(empty($cart_items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Product</th><th>Image</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $total=0;
        foreach($cart_items as $item):
            $subtotal = $item['price']*$item['quantity'];
            $total+=$subtotal;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><img src="<?php echo $basePath; ?>/assets/images/<?php echo htmlspecialchars($item['image']); ?>" style="max-width:60px;"></td>
            <td>$<?php echo number_format($item['price'],2); ?></td>
            <td>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width:60px;">
                    <button type="submit" name="update_quantity" class="btn" style="background:#00b4d8;">Update</button>
                </form>
            </td>
            <td>$<?php echo number_format($subtotal,2); ?></td>
            <td>
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="remove" class="btn" style="background:#ff3a1a;">Remove</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" style="text-align:right;"><strong>Total:</strong></td>
            <td colspan="2"><strong>$<?php echo number_format($total,2); ?></strong></td>
        </tr>
        </tbody>
    </table>

    <a href="<?php echo $basePath; ?>/pages/checkout.php" class="btn" style="margin-top:15px;background:#00b4d8;">Proceed to Checkout</a>
<?php endif; ?>
</div>

<?php include(__DIR__.'/includes/footer.php'); ?>
