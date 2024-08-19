<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'header.php';

$category_id = $_GET['id'];

// Fetch store details
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

// Fetch products
$products =  get_products_by_category_id($conn, $category_id);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Products</title>
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
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(25%, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .product-card h3 {
            margin: 10px 0;
        }
        .product-card p {
            color: #666;
            margin-bottom: 10px;
        }
        .product-card .price {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .actions {
            padding-top: 20px;
            width: 100%;
            display: flex;
            font-size: 16px;
            align-items: center;
            justify-content: space-between;
        }
        .actions a {
            width: 100%;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 10px;
            height: 40px;
            margin-top: -25px;
            padding: 5px;
            width: 80%;
            display: flex;
            align-items: center;
        }
        .like-form {
            margin-right: 4%;
            display: flex;
            align-items: center;
            width: 16%;            
            font-size: 25px;
            justify-content: space-between;
            text-decoration: none;
        }
        .like-form a i {
            margin-top: 20px;
            color: #ff4500;
        }
        .like-button {
            color: black;
            background-color: transparent;
            outline:none ;
            border: none;
            font-size: 25px;
            text-align:center ;
        }
        .like-button:disabled {
            color: red; /* Light gray color for disabled state */
        }
        .carousel-item:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($category['name']); ?> | <?php echo htmlspecialchars($store['name']); ?></h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image_url1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
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
    <script>
        //like func 
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
</body>
</html>