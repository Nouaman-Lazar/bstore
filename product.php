<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'header.php' ;


// Handle like request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    // Update like count
    $sql = "UPDATE products SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        // Fetch updated like count
        $sql = "SELECT likes FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'likes' => $result['likes']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    exit();
}

// Fetch product details
$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

// Update view count
$sql = "UPDATE products SET views = views + 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$stmt->close();
?>
<?php
// ... (Keep the PHP code at the top unchanged)
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product | <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            
        }
        .slider {
            display: none;
        }
        .product-details {
            max-width: 1200px;
            margin: 0px auto;
            background-color: #fff;
            padding: 20px;
            padding-top: 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-top: 10px;
            color: #333;
            text-align: center;
        }
        .product-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }
        .swiper {
            width: 100%;
            max-width: 600px;
            height: 400px;
            margin-bottom: 20px;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            border-radius: 8px;
        }
        .product-text {
            flex: 1;
            min-width: 300px;
            padding: 0 20px;
        }
        .price {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }
        .views {
            background-color: #f0f0f0;
            color: #333;
        }
        .like-button {
            background-color: #ff4081;
            color: white;
        }
        .like-button.clicked {
            background-color: #e91e63;
        }
        .buy-button {
            display: block;
            text-align: center;
            padding: 15px 30px;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            transition: opacity 0.3s ease;
        }
        .buy-button:hover {
            opacity: 0.9;
        }
        .share-button {
            background-color: #3b5998;
            color: white;
        }
        button:disabled {
            color:red;
        }
    </style>
</head>
<body>
    <div class="product-details">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="product-info">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($product['image_url1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <?php if ($product['image_url2']): ?>
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($product['image_url2']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    <?php endif; ?>
                    <?php if ($product['image_url3']): ?>
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($product['image_url3']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="product-text">
                <h2>المواصفات:</h2>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p class="price">الثمن: <?php echo htmlspecialchars(number_format($product['price'], 2)); ?> ريال</p>
                <div class="button-group">
                    <button class="button views"><i class="fa-solid fa-eye"></i> <span class="views-count"><?php echo htmlspecialchars($product['views']); ?></span></button>
                    <button class="button like-button" id="likeButton" data-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa-solid fa-heart"></i> <span id="likeCount"><?php echo htmlspecialchars($product['likes']); ?></span></button>
                    <button class="button share-button" id="shareButton"><i class="fa-solid fa-share-alt"></i> مشاركة</button>
                </div>
                <?php if ($product['affiliate_link']): ?>
                    <a href="<?php echo htmlspecialchars($product['affiliate_link']); ?>" target="_blank" style="background-color:<?php echo htmlspecialchars($product['affiliate_link_color']); ?>;" class="buy-button"><?php echo htmlspecialchars($product['affiliate_link_name']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        document.addEventListener('DOMContentLoaded', function() {
            const likeButton = document.getElementById('likeButton');
            const likeCount = document.getElementById('likeCount');
            const shareButton = document.getElementById('shareButton');

            likeButton.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                this.disabled = true;

                fetch('product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + encodeURIComponent(productId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCount.textContent = data.likes;
                        likeButton.classList.add('clicked');
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    likeButton.disabled = true;
                });
            });

            shareButton.addEventListener('click', function() {
                if (navigator.share) {
                    navigator.share({
                        title: '<?php echo htmlspecialchars($product['name']); ?>',
                        text: '<?php echo htmlspecialchars($product['description']); ?>',
                        url: window.location.href
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    })
                    .catch(console.error);
                } else {
                    // Fallback for browsers that don't support Web Share API
                    alert('Share feature is not supported on this browser. You can copy the URL to share.');
                }
            });
        });
    </script>
</body>
</html>