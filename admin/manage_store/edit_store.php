<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

$store_id = $_GET['id'];

// Fetch store details
$sql = "SELECT * FROM stores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $store_id);
$stmt->execute();
$store = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_name = $_POST['store_name'];
    $store_description = $_POST['store_description'];
    $store_cover = $_POST['store_cover'];

    $sql = "UPDATE stores SET name = ?, description = ?, image_url1 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $store_name, $store_description, $store_cover, $store_id);

    if ($stmt->execute()) {
        echo "Store updated successfully! <a href='../manage_stores.php?id=$store_id'>View Store</a>";
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
    <title>Edit Store</title>
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
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Edit Store</h1>
    <form method="POST" action="">
        <label for="store_name">Store Name:</label>
        <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($store['name']); ?>" required><br>

        <label for="store_description">Store Description:</label>
        <textarea id="store_description" name="store_description" required><?php echo htmlspecialchars($store['description']); ?></textarea><br>

        <label for="store_cover">Store Cover:</label>
        <input type="text" id="store_cover" name="store_cover" value="<?php echo htmlspecialchars($store['image_url1']); ?>" required><br>

        <button type="submit">Update Store</button>
    </form>
</body>
</html>
