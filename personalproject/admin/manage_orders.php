<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$result = $conn->query("
    SELECT o.id AS order_id, u.username, o.status, GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    GROUP BY o.id
    ORDER BY o.id DESC
");
?>

<div class="container">
    <h1>Manage Orders</h1>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Products</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="5">No orders found.</td></tr>
        <?php else: ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['products']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <td>
                        <a class="btn" href="update_order.php?id=<?php echo $order['order_id']; ?>">Update Status</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
