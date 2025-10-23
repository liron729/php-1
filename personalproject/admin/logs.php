<?php
session_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit;
}

$logs = $conn->query("SELECT * FROM admin_logs ORDER BY created_at DESC LIMIT 50");
?>

<div class="container">
  <h1>Admin Logs</h1>
  <table>
    <thead>
      <tr>
        <th>Timestamp</th>
        <th>User ID</th>
        <th>Action</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($log = $logs->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($log['created_at']); ?></td>
        <td><?php echo intval($log['user_id']); ?></td>
        <td><?php echo htmlspecialchars($log['action']); ?></td>
        <td><?php echo htmlspecialchars($log['details']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
