<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// تحقق من تسجيل دخول المسؤول
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// جلب المتاجر لاستخدامها في القائمة المنسدلة
$sql = "SELECT id, name FROM stores";
$result = $conn->query($sql);
$stores = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stores[] = $row;
    }
}

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'];
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];

    // إضافة الفئة إلى قاعدة البيانات
    $sql = "INSERT INTO categories (store_id, name, description, image_url1, image_url2, image_url3) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssss', $store_id, $category_name, $category_description, $image_url1, $image_url2, $image_url3);
    
    if ($stmt->execute()) {
        $category_id = $stmt->insert_id; // الحصول على معرف الفئة الذي تم إدخاله

        // هنا يمكن تنفيذ أي إجراءات إضافية مثل إعادة التوجيه أو عرض رسالة نجاح
        echo "تم إضافة الفئة بنجاح!";
    } else {
        echo "خطأ: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فئة جديدة</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
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
        select, input[type="text"], textarea {
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
    <h1>إضافة فئة جديدة</h1>
    <form method="POST" action="add_category.php">
        <label for="store_id">اختر المتجر:</label>
        <select id="store_id" name="store_id" required>
            <option value="">-- اختر متجرًا --</option>
            <?php foreach ($stores as $store): ?>
                <option value="<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="category_name">اسم الفئة:</label>
        <input type="text" id="category_name" name="category_name" required><br>

        <label for="category_description">وصف الفئة:</label>
        <textarea id="category_description" name="category_description" required></textarea><br>

        <label for="image_url1">رابط الصورة 1:</label>
        <input type="text" id="image_url1" name="image_url1"><br>

        <label for="image_url2">رابط الصورة 2:</label>
        <input type="text" id="image_url2" name="image_url2"><br>

        <label for="image_url3">رابط الصورة 3:</label>
        <input type="text" id="image_url3" name="image_url3"><br>

        <button type="submit">إضافة الفئة</button>
    </form>
</body>
</html>
