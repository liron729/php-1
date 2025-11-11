<?php
session_start();
include(__DIR__ . '/../includes/functions.php');
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

// Admin check
if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all orders
$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<div class="container">
    <h2>Manage Orders</h2>
    <?php if ($result && $result->num_rows > 0): ?>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td>$<?php echo number_format($row['total'], 2); ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <!-- Update Status Form -->
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>

                    <!-- Delete Order Form -->
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_order" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
include(__DIR__ . '/../includes/footer.php');
?>
