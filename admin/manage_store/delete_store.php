<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../../auth/login.php');
    exit();
}

$store_id = $_GET['id'];

// Delete store
$sql = "DELETE FROM stores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $store_id);

if ($stmt->execute()) {
    echo "Store deleted successfully! <a href='../manage_stores.php'>Back to Store Management</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
