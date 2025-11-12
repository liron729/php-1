<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include(__DIR__ . '/../config/db.php');
$user_id = $_SESSION['user_id'] ?? null;

$cart_count = 0;
if ($user_id) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cart_count = (int)($res['total_items'] ?? 0);
    $stmt->close();
}
?>
<style>
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
}
.container {
    flex: 1;
}
footer {
    background: #111;
    color: #f1f1f1;
    text-align: center;
    padding: 20px 0;
    border-top: 1px solid #222;
    position: relative;
    font-size: 14px;
}
footer .stripe {
    height: 5px;
    background: #ff4b2b;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
}
footer a {
    color: #00b4d8;
    text-decoration: none;
    margin: 0 8px;
    transition: color 0.3s;
}
footer a:hover {
    color: #ff4b2b;
}
</style>

<footer>
    <div class="stripe"></div>
    <p>
        <?php if ($user_id): ?>
            Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            <?php if ($cart_count > 0): ?>
                | Cart: <?php echo $cart_count; ?> item<?php echo $cart_count > 1 ? 's' : ''; ?>
            <?php endif; ?>
        <?php else: ?>
            Welcome, Guest!
        <?php endif; ?>
    </p>
    <p>
        <a href="/php-1/personalproject/pages/privacy.php">Privacy Policy</a> |
        <a href="/php-1/personalproject/pages/terms.php">Terms of Service</a> |
        <a href="/php-1/personalproject/pages/contact.php">Contact</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            | <a href="/php-1/personalproject/cart.php">Cart</a>
            | <a href="/php-1/personalproject/pages/wishlist.php">Wishlist</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            | <a href="/php-1/personalproject/admin/dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
    </p>
    <p>&copy; <?php echo date("Y"); ?> <strong>Audiverse</strong>. All rights reserved.</p>
</footer>
</body>
</html>
