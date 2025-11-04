<?php
session_start();
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/navbar.php');
include(__DIR__ . '/config/db.php');

$error = '';
$success = '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // Explicitly set role for new user
            // FIX: Updated INSERT to include 'email' and 'role'
            $insert = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $username, $email, $hashed_password, $role);
            
            if ($insert->execute()) {
                $success = "Registration successful! You may now <a href='login.php'>login</a>.";
            } else {
                $error = "Failed to register user: " . $conn->error;
            }
            $insert->close(); 
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
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required />
    <label>Email:</label> 
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
    <label>Password:</label>
    <input type="password" name="password" required />
    <label>Confirm Password:</label>
    <input type="password" name="password_confirm" required />
    <button type="submit" class="btn">Register</button>
  </form>
  <?php endif; ?>
</div>

<?php 
$conn->close(); 
include(__DIR__ . '/includes/footer.php'); 
?>