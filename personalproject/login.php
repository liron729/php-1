<?php
session_start();
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/navbar.php');
include(__DIR__ . '/config/db.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $is_admin);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $is_admin;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>

<div class="container">
  <h2>Login</h2>
  <?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required />
    <label>Password:</label>
    <input type="password" name="password" required />
    <button type="submit" class="btn">Login</button>
  </form>
</div>

<?php include(__DIR__ . '/includes/footer.php'); ?>
