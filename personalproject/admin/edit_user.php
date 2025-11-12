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

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);

    if ($stmt->execute()) {
        $success = "User updated successfully!";
    } else {
        $error = "Error updating user: " . $conn->error;
    }

    $stmt->close();
}
?>

<div class="container">
    <h1>Edit User</h1>

    <?php if ($success): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required />

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />

        <label>Role:</label>
        <select name="role">
            <option value="user" <?php if($user['role']=='user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
        </select>

        <button type="submit" class="btn">Update User</button>
    </form>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
