<?php
session_start();
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/navbar.php');
include(__DIR__ . '/config/db.php');

$error = '';
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;
$_SESSION['is_admin'] = ($role === 'admin') ? 1 : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // FIX: Changed SELECT to fetch the correct 'role' column
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // FIX: Changed bind_result to use $role
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            // FIX: Set is_admin = 1 if role is 'admin'
            $_SESSION['is_admin'] = ($role === 'admin') ? 1 : 0; 
            $stmt->close(); 
            $conn->close();
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    if (isset($stmt)) { $stmt->close(); }
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

<?php 
$conn->close(); 
include(__DIR__ . '/includes/footer.php'); 
?>