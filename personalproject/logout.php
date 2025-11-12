<?php
session_start();
session_destroy();
header("Location: index.php");
exit;
// log_action($conn, $_SESSION['user_id'], 'Logout', 'User logged out');
?>