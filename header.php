<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Fetch all stores and categories
$stores = get_all_stores($conn);
$categories = get_all_categories($conn);
$publications = get_all_publications($conn); // Assuming you have a function that retrieves all publications

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موقعك الإلكتروني</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff4500;
            --secondary-color: #2c3e50;
            --text-color: #333;
            --background-color: #ecf0f1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            outline: none;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
        }

        .contin {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        header {
            z-index: 99999;
            background-color: var(--primary-color);
            color: #fff;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo img {
            height: 40px;
            margin-left: 10px;
        }

        nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style-type: none;
            display: flex;
        }

        nav ul li {
            position: relative;
            margin-left: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            font-weight: 900;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            font-size: 1.1em;
        }

        nav ul li ul {
            display: none;
            position: absolute;
            background-color: var(--secondary-color);
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            right: 0;
            z-index: 9999;
        }

        nav ul li:hover ul {
            display: block;
            z-index: 99999999;
        }

        nav ul li ul li {
            width: 100%;
            padding: 0.5rem;
        }

        nav ul li ul li a {
            padding: 0.5rem;
            display: block;
        }

        .search-form {
            display: flex;
            align-items: center;
            border: 2px solid black;
            border-radius: 5px;
        }
        .search-form:active {
            border: white 4px solid;
        }

        .search-form input[type="text"] {
            padding: 0.5rem;
            background-color: transparent;
            width: 400px;
            border: none;
            border-radius: 0 4px 4px 0;
            height: 30px;
        }
        .search-form input[type="text"]::placeholder {
            color: white;
            font-size: 15px;
        }
        .search-form button {
            background-color: transparent;
            color: white;
            border: none;
            height: 30px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 4px 0 0 4px;
        }

        .sidebar {
            background-color: var(--secondary-color);
            color: #fff;
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            right: -250px;
            transition: right 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar-content {
            padding: 2rem;
        }

        .sidebar h2 {
            margin-bottom: 1rem;
        }

        .sidebar ul {
            list-style-type: none;
            z-index: 999;
        }

        .sidebar ul li {
            margin-bottom: 0.5rem;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        .toggle-sidebar {
            z-index: 1001;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            display: none;
        }

        .social-icons {
            display: flex;
            justify-content: center;
        }

        .social-icons a {
            color: #fff;
            font-size: 1.5rem;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: var(--secondary-color);
        }

        main {
            padding: 2rem 0;
        }

        /* Slider styles */
        .slider {
            position: relative;
            overflow: hidden;
            top: 50px;
            height: 400px;
            margin-bottom: 50px;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slider-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
        }

        .slider-nav button {
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
        }

        .slider-nav button.active {
            background-color: #fff;
        }

        @media screen and (max-width: 768px) {
            nav ul {
                display: none;
            }
            .social-icons {
            display: none;
            }

            .search-form {
                display: none;
            }

            .toggle-sidebar {
                display: flex;
                color:black;
                z-index: 999999;
            }

            .sidebar {
                right: -250px;
            }

            .sidebar.active {
                right: 0;
            }
            .slider {
            height: 300px;
            border-radius: 10px;
            }
            .search-form {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            padding: 0.5rem;
            z-index: 1000;
            }

            .search-form.active {
                display: flex;
            }

            .search-form input[type="text"] {
                width: 100%;
            }
            .search-form input[type="text"]::placeholder {
                color: black;
            }
            .search-form button {
                color: black;
            }
            .toggle-search {
                display: block;
                background: none;
                border: none;
                color: black;
                font-size: 1.2rem;
                cursor: pointer;
            }
        }

    @media screen and (min-width: 769px) {
        .toggle-search {
            display: none;
        }
            }
    </style>
</head>
<body>
    <header>
        <div class="contin">
            <nav>
                <button class="toggle-sidebar"><i class="fas fa-bars"></i></button>
                <div class="logo">
                    hmizat 
                </div>
                <button class="toggle-search"><i class="fas fa-search"></i></button>
                <form id="search-form" class="search-form" action="search.php" method="get">
                    <input type="text" name="query" placeholder="بحث...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <ul>
                    <li>
                        <a class="home" href="index.php">الرائيسي</a>
                    </li>
                    <li>
                        <a href="#">المتاجر</a>
                        <ul>
                            <?php foreach ($stores as $store): ?>
                                <li><a href="categories.php?id=<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#">الفئات</a>
                        <ul>
                            <?php foreach ($categories as $category): ?>
                                <li><a href="products.php?id=<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
                <div class="social-icons">
                    <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </nav>
        </div>
    </header>
    <div class="sidebar">
        <div class="sidebar-content">
            <h2>القائمة</h2>
            <ul>
                <ul>
                    <li>
                        <a class="home" href="index.php">الرائيسي</a>
                    </li>
                    <li>
                        <a href="#">المتاجر</a>
                        <ul>
                            <?php foreach ($stores as $store): ?>
                                <li><a href="categories.php?id=<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
                <li><a href="#">من نحن</a></li>
                <li><a href="#">اتصل بنا</a></li>
            </ul>
            <div class="social-icons">
                <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <main>
        <div class="contin">
        <?php if (!empty($publications)): ?>
    <div class="slider">
        <?php foreach ($publications as $index => $publication): ?>
            <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                <a href="<?php echo htmlspecialchars($publication['link']); ?>" target="_blank">
                    <img src=<?php echo htmlspecialchars($publication['image_url']); ?>" alt="Publication Image">
                </a>
            </div>
            <?php endforeach; ?>
            <div class="slider-nav">
                <?php for ($i = 0; $i < count($publications); $i++): ?>
                    <button class="<?php echo $i === 0 ? 'active' : ''; ?>"></button>
                <?php endfor; ?>
            </div>
    </div>
        <?php endif; ?>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const toggleSearch = document.querySelector('.toggle-search');
            const searchForm = document.querySelector('.search-form');

            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            toggleSearch.addEventListener('click', function() {
                searchForm.classList.toggle('active');
            });

            // Slider functionality
            const slides = document.querySelectorAll('.slide');
            const navButtons = document.querySelectorAll('.slider-nav button');
            let currentSlide = 0;

            function showSlide(index) {
                slides[currentSlide].classList.remove('active');
                navButtons[currentSlide].classList.remove('active');
                slides[index].classList.add('active');
                navButtons[index].classList.add('active');
                currentSlide = index;
            }

            function nextSlide() {
                let nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex);
            }

            navButtons.forEach((button, index) => {
                button.addEventListener('click', () => showSlide(index));
            });

            setInterval(nextSlide, 5000); // Change slide every 5 seconds
        });
    </script>
</body>
</html>