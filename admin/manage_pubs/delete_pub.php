<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../../auth/login.php');
    exit();
}

// Get the publication ID from the URL
$pub_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pub_id > 0) {
    // Prepare and execute the delete statement
    $sql = "DELETE FROM publications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pub_id);

    if ($stmt->execute()) {
        echo "<script>alert('Publication deleted successfully!'); window.location.href='../manage_pub.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid publication ID.'); window.location.href='manage_pub.php';</script>";
    exit();
}
?>
