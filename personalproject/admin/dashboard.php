<?php 
session_start(); 
include('../../config/db.php'); 
include('../../includes/functions.php'); // Required for isAdmin()
include('../../includes/header.php'); 
include('../../includes/navbar.php'); 

// FIX: Enforce admin access
if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}
?>

<h2>Admin Dashboard</h2>
<p>Manage products, orders, and users.</p>

<?php 
$conn->close(); 
include('../../includes/footer.php'); 
?>