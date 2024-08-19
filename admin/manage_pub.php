<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

$publications = get_all_publications($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Publications</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #0056b3;
        }

        .add-publication {
            display: block;
            margin-bottom: 20px;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            border-radius: 5px;
            width: 100px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: #0056b3;
        }

        .actions .delete {
            background-color: #dc3545;
        }

        .actions .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Publications</h1>
        <a href="manage_pubs/add_pub.php" class="add-publication">Add New Publication</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Link</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($publications as $publication): ?>
            <tr>
                <td><?php echo $publication['id']; ?></td>
                <td><img src="<?php echo htmlspecialchars($publication['image_url']); ?>" alt="Image"></td>
                <td><a href="<?php echo htmlspecialchars($publication['link']); ?>" target="_blank">View Link</a></td>
                <td class="actions">
                    <a href="manage_pubs/edit_pub.php?id=<?php echo $publication['id']; ?>">Edit</a>
                    <a href="manage_pubs/delete_pub.php?id=<?php echo $publication['id']; ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
