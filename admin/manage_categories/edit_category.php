<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../auth/login.php");
    exit();
}
include '../../includes/config.php';

$id = $_GET['id'];

// Fetch all stores for the dropdown
$sql = "SELECT * FROM stores";
$stores_result = $conn->query($sql);
$stores = [];
if ($stores_result) {
    while ($row = $stores_result->fetch_assoc()) {
        $stores[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store_id = $_POST['store_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_url1 = $_POST['image_url1'];

    // Prepare and execute the SQL statement
    $sql = "UPDATE categories SET store_id=?, name=?, description=?, image_url1=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssi', $store_id, $name, $description, $image_url1, $id);

    if ($stmt->execute()) {
        header("location: ../manage_categories.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    $sql = "SELECT * FROM categories WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Category</h1>
        <form method="post" action="">
            <label for="store_id">Store:</label>
            <select id="store_id" name="store_id" required>
                <?php foreach ($stores as $store): ?>
                    <option value="<?php echo htmlspecialchars($store['id']); ?>" 
                        <?php if ($store['id'] == $category['store_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($store['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($category['description']); ?></textarea><br>

            <label for="image_url1">Image URL 1:</label>
            <input type="text" id="image_url1" name="image_url1" value="<?php echo htmlspecialchars($category['image_url1']); ?>"><br>

            <input type="submit" value="Update Category">
        </form>
    </div>
</body>
</html>
