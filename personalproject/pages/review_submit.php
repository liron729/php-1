<?php
session_start();
include(__DIR__ . '/../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        header("Location: product.php?id=$product_id");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
    $stmt->execute();
    $stmt->close();

    header("Location: product.php?id=$product_id");
} else {
    header("Location: ../login.php");
}
?>
