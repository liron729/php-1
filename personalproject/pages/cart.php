<?php
session_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
include(__DIR__ . '/../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

$cart = $_SESSION['cart'] ?? [];

?>

<div class="container">
  <h1>Your Cart</h1>
  <?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $total_price = 0;
      foreach ($cart as $pid => $qty) {
          $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
          $stmt->bind_param("i", $pid);
          $stmt->execute();
          $stmt->bind_result($name, $price);
          $stmt->fetch();
          $stmt->close();
          $line_total = $price * $qty;
          $total_price += $line_total;
          echo "<tr>";
          echo "<td>" . htmlspecialchars($name) . "</td>";
          echo "<td>" . intval($qty) . "</td>";
          echo "<td>$" . number_format($price, 2) . "</td>";
          echo "<td>$" . number_format($line_total, 2) . "</td>";
          echo "</tr>";
      }
      ?>
      <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td><strong>$<?php echo number_format($total_price, 2); ?></strong></td>
      </tr>
      </tbody>
    </table>
    <form method="POST" action="checkout.php">
    <button type="submit" class="btn">Checkout</button>
</form>

  <?php endif; ?>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
