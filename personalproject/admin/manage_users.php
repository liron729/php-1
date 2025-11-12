<?php
session_start();
include(__DIR__ . '/../config/db.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/navbar.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='container'><h2>Access denied</h2></div>";
    exit;
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container">
    <h1>Manage Users</h1>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a class="btn" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                    <a class="btn" href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
