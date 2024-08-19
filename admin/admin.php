<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch the number of visits, stores, categories, and products
$total_visits = get_total_visits($conn);
$total_stores = get_total_stores($conn);
$total_categories = get_total_categories($conn);
$total_products = get_total_products($conn);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .admin-dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .dashboard-stats {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .stat-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            flex: 1;
            min-width: 200px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stat-item:hover {
            transform: translateY(-5px);
        }
        .stat-item h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .stat-item p {
            color: #007bff;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .stat-item i {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .admin-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .admin-action {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .admin-action:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <h1>لوحة التحكم</h1>
        <div class="dashboard-stats">
            <div class="stat-item">
                <i class="fas fa-chart-line"></i>
                <h2>مجموع زيارات الموقع</h2>
                <p id="visits-count"><?php echo $total_visits; ?></p>
            </div>
            <div class="stat-item">
                <i class="fas fa-store"></i>
                <h2>عدد المتاجر</h2>
                <p id="stores-count"><?php echo $total_stores; ?></p>
            </div>
            <div class="stat-item">
                <i class="fas fa-list"></i>
                <h2>عدد الأصناف</h2>
                <p id="categories-count"><?php echo $total_categories; ?></p>
            </div>
            <div class="stat-item">
                <i class="fas fa-box"></i>
                <h2>عدد المنتجات</h2>
                <p id="products-count"><?php echo $total_products; ?></p>
            </div>
        </div>
        
        <div class="admin-actions">
            <a href="add_publication.php" class="admin-action">
                <i class="fas fa-plus"></i> إضافة منشور
            </a>
            <a href="add_product.php" class="admin-action">
                <i class="fas fa-plus"></i> إضافة منتج
            </a>
            <a href="add_store.php" class="admin-action">
                <i class="fas fa-plus"></i> إضافة متجر
            </a>
            <a href="add_category.php" class="admin-action">
                <i class="fas fa-plus"></i> إضافة صنف
            </a>
        </div>
    </div>

    <script>
        function animateCount(elementId, targetCount) {
            let count = 0;
            const element = document.getElementById(elementId);
            const duration = 2000; // Animation duration in milliseconds
            const interval = 50; // Update interval in milliseconds
            const steps = duration / interval;
            const increment = targetCount / steps;

            const timer = setInterval(() => {
                count += increment;
                if (count >= targetCount) {
                    clearInterval(timer);
                    count = targetCount;
                }
                element.textContent = Math.round(count);
            }, interval);
        }

        // Animate the counts when the page loads
        window.onload = function() {
            animateCount('visits-count', <?php echo $total_visits; ?>);
            animateCount('stores-count', <?php echo $total_stores; ?>);
            animateCount('categories-count', <?php echo $total_categories; ?>);
            animateCount('products-count', <?php echo $total_products; ?>);
        };
    </script>
</body>
</html>