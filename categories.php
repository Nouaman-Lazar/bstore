<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$store_id = $_GET['id'];

// Fetch store details
$sql = "SELECT * FROM stores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $store_id);
$stmt->execute();
$store = $stmt->get_result()->fetch_assoc();

// Fetch categories
$categories = get_categories_by_store_id($conn, $store_id);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bstore | <?php echo htmlspecialchars($store['name']); ?></title>
    <link rel="stylesheet" href="css/category.css">
</head>
<body>
    <menu>
        <h1 style="text-align:center;"><?php echo htmlspecialchars($store['name']); ?></h1>
        <h2 style="border-bottom: 6px solid orangered;">الأصناف</h2>
        <div class="store-details">
                <?php foreach ($categories as $category): ?>
                    <div class="category_card">
                    <img src="<?php echo htmlspecialchars($category['image_url1']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?>">
                            <div class="category-info">
                                <h1><?php echo htmlspecialchars($category['name']); ?></h1>
                                <p><?php echo htmlspecialchars($category['description']); ?></p>
                            </div>
                            <a href="products.php?id=<?php echo $category['id']; ?>">
                                <span>استكشف الصنف</span> <i class="fas fa-arrow-left"></i>
                            </a>
                    </div>
                <?php endforeach;  ?>
        </div>
    </menu>
</body>
</html>