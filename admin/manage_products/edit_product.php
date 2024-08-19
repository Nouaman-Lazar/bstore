<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../auth/login.php");
    exit();
}
include '../../includes/config.php';

// Fetch all categories for the dropdown
$categories = [];
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];
    $affiliate_link = $_POST['affiliate_link'];

    // Prepare and execute the SQL statement
    $sql = "UPDATE products SET category_id=?, name=?, description=?, image_url1=?, image_url2=?, image_url3=?, affiliate_link=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssssi', $category_id, $name, $description, $image_url1, $image_url2, $image_url3, $affiliate_link, $id);

    if ($stmt->execute()) {
        echo "<p>Product updated successfully</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    $sql = "SELECT * FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
        select, input[type="text"], textarea {
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
        <h1>Edit Product</h1>
        <form method="post" action="">
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php if ($category['id'] == $product['category_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

            <label for="image_url1">Image URL 1:</label>
            <input type="text" id="image_url1" name="image_url1" value="<?php echo htmlspecialchars($product['image_url1']); ?>"><br>

            <label for="image_url2">Image URL 2:</label>
            <input type="text" id="image_url2" name="image_url2" value="<?php echo htmlspecialchars($product['image_url2']); ?>"><br>

            <label for="image_url3">Image URL 3:</label>
            <input type="text" id="image_url3" name="image_url3" value="<?php echo htmlspecialchars($product['image_url3']); ?>"><br>

            <label for="affiliate_link">Affiliate Link:</label>
            <input type="text" id="affiliate_link" name="affiliate_link" value="<?php echo htmlspecialchars($product['affiliate_link']); ?>"><br>

            <input type="submit" value="Update Product">
        </form>
    </div>
</body>
</html>
