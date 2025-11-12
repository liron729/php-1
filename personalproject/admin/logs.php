<?php
session_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access Denied</h2></div>";
    include(__DIR__ . '/../includes/footer.php');
    exit;
}

$query = "
    SELECT l.id, u.username, l.action, l.details, l.created_at
    FROM logs l
    LEFT JOIN users u ON l.user_id = u.id
    ORDER BY l.created_at DESC
";
$result = $conn->query($query);
?>

<div class="container">
    <h1>System Logs</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:15px;">
            <thead style="background:#00b4d8; color:white;">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($log = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['id']); ?></td>
                        <td><?php echo htmlspecialchars($log['username'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><?php echo htmlspecialchars($log['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No logs found.</p>
    <?php endif; ?>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
