<?php
session_start();
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/navbar.php');
include(__DIR__ . '/config/db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed_password);
            if ($insert->execute()) {
                $success = "Registration successful! You may now <a href='login.php'>login</a>.";
            } else {
                $error = "Failed to register user.";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container">
  <h2>Register</h2>
  <?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
  <?php else: ?>
  <form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required />
    <label>Password:</label>
    <input type="password" name="password" required />
    <label>Confirm Password:</label>
    <input type="password" name="password_confirm" required />
    <button type="submit" class="btn">Register</button>
  </form>
  <?php endif; ?>
</div>

<?php include(__DIR__ . '/includes/footer.php'); ?>
