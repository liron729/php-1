<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
// function log_action($conn, $user_id, $action, $details = null) {
//     $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
//     $stmt->bind_param("iss", $user_id, $action, $details);
//     $stmt->execute();
//     $stmt->close();
// }

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?>
