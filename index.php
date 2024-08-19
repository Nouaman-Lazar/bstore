<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'header.php';


// Handle like button submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Update the like count in the database
    $sql = "UPDATE products SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();

    // Fetch the updated like count
    $sql = "SELECT likes FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Return the updated like count as JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'likes' => $result['likes']]);
    
    $stmt->close();
    exit();
}

// Fetch new products, stores, and categories
$new_products = get_new_products($conn);
$new_stores = get_new_stores($conn);
$new_categories = get_new_categories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Your Website Title</title>
    <style>

    </style>
</head>
<body>
    <section id="new-stores" class="carousel-section">
        <div class="container">
            <h2>المتاجر</h2>
            <div class="carousel-container">
                <button class="carousel-button next">&lt;</button>
                <div class="carousel">
                    <?php foreach ($new_stores as $store): ?>
                        <div class="carousel-item store-item">
                            <img src="<?php echo htmlspecialchars($store['image_url1']); ?>" alt="<?php echo htmlspecialchars($store['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($store['name']); ?></h3>
                                <a href="categories.php?id=<?php echo $store['id']; ?>"> استكشف المتجر</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-button prev">&gt;</button>
            </div>
        </div>
    </section>

    <main id="main-content">
        
    <section id="new-categories" class="carousel-section">
            <div class="container">
                <h2>منتجات مضافة حديثا</h2>
                <div class="carousel-container">
                    <button class="carousel-button prev">&gt;</button>
                    <div class="carousel">
                        <?php foreach ($new_categories as $category): ?>
                            <div class="carousel-item category-item">
                                <img src="<?php echo htmlspecialchars($category['image_url1']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                                <div class="item-info">
                                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                    <a href="products.php?id=<?php echo $category['id']; ?>"> استكشف القسم</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-button next">&lt;</button>
                </div>
            </div>
        </section>

        <section id="new-products" class="carousel-section">
            <div class="container">
                <div class="title"><h2>منتجات مضافة حديثا</h2><a href="all_product.php">رؤية المزيد >></a></div>
                <div class="carousel-container">
                    <button class="carousel-button prev">&gt;</button>
                    <div class="carousel">
                        <?php foreach ($new_products as $product): ?>
                            <div class="carousel-item product-item">
                                <img src="<?php echo htmlspecialchars($product['image_url1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="item-info">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="price"><?php echo htmlspecialchars($product['price']); ?></p>
                                </div>
                                <div class="actions">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" target="_blank" style="background-color:<?php echo htmlspecialchars($product['affiliate_link_color']); ?>;" class="buy-button">مشاهدة تفاصيل المنتج</a>
                                    <form method="POST" action="" class="like-form">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                        <button type="submit" class="like-button" data-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa-solid fa-heart"></i></button>
                                        <a href="<?php echo htmlspecialchars($product['affiliate_link']); ?>"><i class="fas fa-shopping-bag"></i></a>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-button next">&lt;</button>
                </div>
            </div>
        </section>

    </main>
    <script src="js/script.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like button click
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = this;
            var button = form.querySelector('.like-button');
            var likeCountElement = button.querySelector('.like-count');
            var productId = button.getAttribute('data-id');

            // Disable button to prevent multiple clicks
            button.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // Sending to the same page
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Check if the response is JSON
                    if (xhr.getResponseHeader('Content-Type') === 'application/json') {
                        var data = JSON.parse(xhr.responseText);

                        if (data.success) {
                            // Change button color to pink and update like count
                            button.classList.add('liked');
                            button.style.color = 'pink'; // Apply pink color
                            likeCountElement.textContent = data.likes;
                        } else {
                            console.error('Failed to like the product.');
                        }
                    } else {
                        console.error('Response is not JSON');
                    }
                } else {
                    console.error('Failed to send request');
                }

                // Re-enable button after processing
                button.disabled = true;
            };
            xhr.send('product_id=' + encodeURIComponent(productId));
        });
    });

    // Handle buy button click
    document.querySelectorAll('.buy-button').forEach(button => {
        button.addEventListener('click', function() {
            // Change button color to green when clicked
            button.classList.add('liked');
        });
    });
});

    </script>

    <footer id="main-footer">
        <div class="container">
            <p>&copy; 2023 Your Website Name. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
