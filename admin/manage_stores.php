<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch all stores
$sql = "SELECT * FROM stores";
$result = $conn->query($sql);
$stores = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stores[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stores</title>
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
        .store-management {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Manage Stores</h1>
    <div class="store-management">
        <a href="add_store.php" class="btn">Add New Store</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores as $store): ?>
                    <tr>
                        <td><?php echo $store['id']; ?></td>
                        <td><?php echo htmlspecialchars($store['name']); ?></td>
                        <td><?php echo htmlspecialchars($store['description']); ?></td>
                        <td>
                            <a href="../categories.php?id=<?php echo $store['id']; ?>">View</a>
                            <a href="manage_store/edit_store.php?id=<?php echo $store['id']; ?>">Edit</a> |
                            <a href="manage_store/delete_store.php?id=<?php echo $store['id']; ?>" onclick="return confirm('Are you sure you want to delete this store?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
