<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch all categories with store names and photo URLs
$sql = "SELECT c.*, s.name AS store_name 
        FROM categories c
        LEFT JOIN stores s ON c.store_id = s.id";
$result = $conn->query($sql);
$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $category_id);
    if ($stmt->execute()) {
        header('Location: manage_categories.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        .category-list {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .category-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .category-list th, .category-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .category-list th {
            background-color: #f4f4f4;
        }
        .category-list img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .category-list a {
            text-decoration: none;
            color: #007bff;
        }
        .category-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Manage Categories</h1>
    <div class="category-list">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Store Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['id']); ?></td>
                        <td>
                            <?php if (!empty($category['image_url1'])): ?>
                                <img src="<?php echo htmlspecialchars($category['image_url1']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <?php else: ?>
                                No Photo
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td><?php echo htmlspecialchars($category['store_name']); ?></td>
                        <td>
                            <a href="manage_categories/edit_category.php?id=<?php echo htmlspecialchars($category['id']); ?>">Edit</a> |
                            <a href="manage_categories.php?delete=<?php echo htmlspecialchars($category['id']); ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
