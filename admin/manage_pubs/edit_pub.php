<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get the publication ID from the URL
$pub_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pub_id > 0) {
    // Fetch the publication details
    $publication = get_publication_by_id($conn, $pub_id);

    if (!$publication) {
        echo "Publication not found.";
        exit();
    }
} else {
    echo "Invalid publication ID.";
    exit();
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_url = $_POST['image_url'];
    $link = $_POST['link'];
    $name = $_POST['name'];

    $sql = "UPDATE publications SET image_url = ?, link = ?, name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $image_url, $link, $name, $pub_id);

    if ($stmt->execute()) {
        echo "<script>alert('Publication updated successfully!'); window.location.href='manage_pub.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Publication</title>
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
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

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Edit Publication</h1>
    <form method="POST" action="">
        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($publication['image_url']); ?>" required>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($publication['link']); ?>" required>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($publication['name']); ?>" required>

        <button type="submit">Update Publication</button>
    </form>
</body>

</html>
