<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// تحقق من تسجيل دخول المسؤول
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_name = $_POST['store_name'];
    $store_description = $_POST['store_description'];
    $image_url1 = $_POST['image_url1'];
    $image_url2 = $_POST['image_url2'];
    $image_url3 = $_POST['image_url3'];

    // إدخال المتجر في قاعدة البيانات
    $sql = "INSERT INTO stores (name, description, image_url1, image_url2, image_url3) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $store_name, $store_description, $image_url1, $image_url2, $image_url3);
    
    if ($stmt->execute()) {
        // إذا كنت لا ترغب في إنشاء صفحة المتجر، يمكنك إزالة هذا الجزء.
        echo "Store added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة متجر</title>
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
        input[type="text"], textarea {
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
    <h1>إضافة متجر جديد</h1>
    <form method="POST" action="add_store.php">
        <label for="store_name">اسم المتجر:</label>
        <input type="text" id="store_name" name="store_name" required><br>

        <label for="store_description">وصف المتجر:</label>
        <textarea id="store_description" name="store_description" required></textarea><br>

        <label for="image_url1">رابط الصورة 1:</label>
        <input type="text" id="image_url1" name="image_url1"><br>

        <label for="image_url2">رابط الصورة 2:</label>
        <input type="text" id="image_url2" name="image_url2"><br>

        <label for="image_url3">رابط الصورة 3:</label>
        <input type="text" id="image_url3" name="image_url3"><br>

        <button type="submit">إضافة المتجر</button>
    </form>
</body>
</html>
