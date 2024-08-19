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
$stores = get_all_stores($conn);

// Check if there are any stores available
if (empty($stores)) {
    die("No stores available. Please add a store first.");
}

// Fetch all categories for the selected store (default to the first store)
$store_id = isset($_POST['store_id']) ? $_POST['store_id'] : $stores[0]['id'];
$categories = get_categories_by_store_id($conn, $store_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $category_id = $_POST['category_id'];
    $store_id = $_POST['store_id'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];
    $affiliate_link = $_POST['affiliate_link'];
    $affiliate_link_color = $_POST['affiliate_link_color'];
    $affiliate_link_name = $_POST['affiliate_link_name'];

    // Insert product into the database
    $sql = "INSERT INTO products (name, description, price, category_id, store_id, image_url1, image_url2, image_url3, affiliate_link, affiliate_link_color, affiliate_link_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdisssssss', $product_name, $product_description, $product_price, $category_id, $store_id, $image_url1, $image_url2, $image_url3, $affiliate_link, $affiliate_link_color, $affiliate_link_name);

    if ($stmt->execute()) {
        echo "Product added successfully! <a href='manage_products.php'>Back to Product Management</a>";
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
    <title>Add Product</title>
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
        input[type="text"], input[type="number"], textarea, input[type="url"], input[type="color"], select {
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
    <script>
        function fetchCategories(storeId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_categories.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('category-select').innerHTML = xhr.responseText;
                }
            };
            xhr.send('store_id=' + storeId);
        }
    </script>
</head>
<body>
    <h1>Add Product</h1>
    <form method="POST" action="">
        <label for="store_id">Store:</label>
        <select id="store_id" name="store_id" onchange="fetchCategories(this.value)">
            <?php foreach ($stores as $store): ?>
                <option value="<?php echo $store['id']; ?>" <?php if ($store['id'] == $store_id) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($store['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="category_id">Category:</label>
        <select id="category-select" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="product_description">Product Description:</label>
        <textarea id="product_description" name="product_description" required></textarea>

        <label for="product_price">Product Price:</label>
        <input type="number" id="product_price" name="product_price" step="0.01" required>

        <label for="image_url1">Image URL 1:</label>
        <input type="url" id="image_url1" name="image_url1">

        <label for="image_url2">Image URL 2:</label>
        <input type="url" id="image_url2" name="image_url2">

        <label for="image_url3">Image URL 3:</label>
        <input type="url" id="image_url3" name="image_url3">

        <label for="affiliate_link">Affiliate Link:</label>
        <input type="url" id="affiliate_link" name="affiliate_link">

        <label for="affiliate_link_color">Affiliate Link Color:</label>
        <input type="color" id="affiliate_link_color" name="affiliate_link_color">

        <label for="affiliate_link_name">Affiliate Link Name:</label>
        <input type="text" id="affiliate_link_name" name="affiliate_link_name">

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
