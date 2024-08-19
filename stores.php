<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';


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
    <title>MStores</title>
    <link rel="stylesheet" href="css/stores.css">
</head>
<body>
    <menu>
        <h1 style="text-align: center;">المتاجر</h1>
        <div class="store_cards">
            <?php foreach ($stores as $store): ?>
                <div class="store_card" style="background-image:url(<?php echo htmlspecialchars($store['image_url1']); ?>);">
                    <a href="categories.php?id=<?php echo $store['id']; ?>">استكشف المتجر</a>
                </div>
            <?php endforeach; ?>
        </div>
    </menu>
</body>
</html>
