<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: manage_products.php");
exit;
?>
