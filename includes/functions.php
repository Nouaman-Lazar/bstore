<?php
    include ("config.php");
    function get_total_visits($conn) {
        $sql = "SELECT SUM(visits) AS total_visits FROM visits";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_visits'];
    }
    
    function get_total_stores($conn) {
        $sql = "SELECT COUNT(*) AS total_stores FROM stores";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_stores'];
    }
    
    function get_total_categories($conn) {
        $sql = "SELECT COUNT(*) AS total_categories FROM categories";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_categories'];
    }
    
    function get_total_products($conn) {
        $sql = "SELECT COUNT(*) AS total_products FROM products";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_products'];
    }
    function get_categories_by_store_id($conn, $store_id) {
        $sql = "SELECT * FROM categories WHERE store_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $store_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    function get_products_by_category_id($conn, $category_id) {
        $sql = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    function get_all_stores($conn) {
        $sql = "SELECT * FROM stores";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    function get_all_categories($conn) {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $result = $conn->query($sql);
    
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }
    
    function get_store_by_id($store_id, $conn) {
        $query = "SELECT * FROM stores WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $store_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    function get_new_products($conn, $limit = 24) {
        $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        return $products;
    }
    function get_new_stores($conn, $limit = 6) {
        $sql = "SELECT * FROM stores ORDER BY created_at DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $stores = [];
        while ($row = $result->fetch_assoc()) {
            $stores[] = $row;
        }
        $stmt->close();
        return $stores;
    }
    function get_new_categories($conn, $limit = 12) {
        $sql = "SELECT * FROM categories ORDER BY created_at DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        $stmt->close();
        return $categories;
    }
    function get_all_publications($conn) {
        $sql = "SELECT * FROM publications";
        $result = $conn->query($sql);
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }    
    function get_publication_by_id($conn, $pub_id) {
        $sql = "SELECT * FROM publications WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $pub_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }         
    function increaseProductsViews($product_id) {
        global $conn;
        $sql = "UPDATE products SET views = views + 1 WHERE id = '$product_id'";
        $result = $conn->query($sql);
        return $result;
    };
    function likeProduct($product_id) {
        global $conn;
        $sql = "UPDATE products SET likes = likes + 1 WHERE id = '$product_id'";
        $result = $conn->query($sql);
        return $result;
    };
    function get_recent_activities($conn) {
        // Implement this function to fetch recent activities from the database
        // Return an array of activities with 'description' and 'timestamp' keys
        return [
            ['description' => 'New product added', 'timestamp' => '2023-04-15 10:30:00'],
            ['description' => 'User registration', 'timestamp' => '2023-04-15 09:45:00'],
            ['description' => 'Order placed', 'timestamp' => '2023-04-14 18:20:00'],
        ];
    }
    
    function get_top_selling_products($conn) {
        // Implement this function to fetch top selling products from the database
        // Return an array of products with 'name' and 'sales' keys
        return [
            ['name' => 'Product A', 'sales' => 150],
            ['name' => 'Product B', 'sales' => 120],
            ['name' => 'Product C', 'sales' => 100],
        ];
    }
?>