

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutri Bite - Healthy Traditional Foods</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Welcome, <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'User'; ?>!</h1>


    \
    <header>
        <h1>Nutri Bite</h1>
        <div class="container">
        <nav>
            
            <ul>
                
                <li><a href="#home">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#contact">Contact</a></li>
                
                <?php if (!isset($_SESSION["user_id"])): ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Signup</a></li>
                <?php else: ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>

                
            </ul>
        </nav>
        </div>
    </header>
    
    
    
    <section id="home">
        <h2>Welcome to Nutri Bite</h2>
        <p>Discover the goodness of traditional healthy foods, made with love and authenticity.</p>
        <img src="all (1).webp" alt="all">
    </section>
    
    <section id="products">
        <h2>Our Healthy Foods</h2>
        <div class="products-container">
            <div class="product">
                <img src="ellu.jpg" alt="Sesame Laddu">
                
                <h3>Sesame Laddu</h3>
                <p>Rich in iron and energy-boosting.</p>
                
            </div>
            <div class="product">
                <img src="groundnut-laddoo.jpg" alt="Peanut Laddu">
                <h3>Peanut Laddu</h3>
                <p>High in protein and good fats.</p>
                
            </div>
            <div class="product">
                <img src="Besan.jpg" alt="Gram Flour Laddu">
                <h3>Gram Flour Laddu</h3>
                <p>Delicious and nutritious sweet treat.</p>
                
            </div>
            <div class="product">
                <img src="dry-fruits-laddu.jpg" alt="Dry Fruit Laddu">
                <h3>Dry Fruit Laddu</h3>
                <p>Packed with vitamins and minerals.</p>
                
            </div>
            <div class="product">
                <img src="holge.jpeg" alt="Peanut Poli">
                <h3>Peanut Poli</h3>
                <p>Sweet flatbread made with peanuts.</p>
                
            </div>
            <div class="product">
                <img src="Dates.jpg" alt="Dates Laddu">
                <h3>Dates Laddu</h3>
                <p>Natural sweetness with high fiber.</p>
                
            </div>
            <div class="product">
                <img src="coconut.jpg" alt="Coconut Laddu">
                <h3>Coconut Laddu</h3>
                <p>Rich in healthy fats and flavor.</p>
                
            </div>
            <div class="product">
                <img src="7cup.jpeg" alt="7 Cup Burfi">
                <h3>7 Cup Burfi</h3>
                <p>Classic Indian sweet with a rich taste.</p>
                
            </div>
            <div class="product">
                <img src="rava.jpg" alt="Semolina Laddu">
                <h3>Semolina Laddu</h3>
                <p>Soft and flavorful with cardamom aroma.</p>
                
            </div>
            <div class="product">
                <img src="chikki.jpeg" alt="Peanut Brittle">
                <h3>Peanut Brittle</h3>
                <p>Crunchy and caramelized goodness.</p>
                
            </div>
        </div>
    </section>
    
    <section id="contact">
        <h2>Contact Us</h2>
        <a href="customer.html">Fill Details</a>
        <p>Email: support@nutribite.com</p>
        <p>Phone: +123 456 7890</p>
    </section>
    
    <footer>
        <p>&copy; 2025 Nutri Bite. All Rights Reserved.</p>
    </footer>
</body>
</html>

