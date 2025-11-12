<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Error updating order: " . $conn->error;
    }

    $stmt->close();
}
?>

<div class="container">
    <h1>Update Order Status</h1>

    <?php if ($success): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Status:</label>
        <select name="status">
            <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Processing" <?php if($order['status']=='Processing') echo 'selected'; ?>>Processing</option>
            <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>Completed</option>
            <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select>

        <button type="submit" class="btn">Update Status</button>
    </form>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
