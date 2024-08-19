<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_url = $_POST['image_url'];
    $link = $_POST['link'];
    $name = $_POST['name'];

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO publications (image_url, link, name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $image_url, $link, $name);

    if ($stmt->execute()) {
        echo "تمت إضافة النشرة بنجاح! <a href='manage_pub.php'>إدارة النشرات</a>";
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
    <title>إضافة نشرة</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            color: #007BFF;
        }

        form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>إضافة نشرة</h1>
    <form method="POST" action="">
        <label for="image_url">رابط الصورة:</label>
        <input type="text" id="image_url" name="image_url" required>

        <label for="link">الرابط:</label>
        <input type="text" id="link" name="link" required>

        <label for="name">الاسم:</label>
        <input type="text" id="name" name="name" required>

        <button type="submit">إضافة النشرة</button>
    </form>
</body>
</html>
