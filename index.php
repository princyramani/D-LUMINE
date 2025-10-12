<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'LUMINE Says! - Fine Jewelry & Gemstones</title>
    <link rel="stylesheet" href="style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loading-content">
            <div class="loading-logo">D'LUMINE</div>
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
        </div>
    </div>

    <!-- Cursor Effect -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>

    <!-- Header -->
    <!-- Header -->
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="#home"><img src="image/d'luminelogo.jpg" alt="D'LUMINE Logo" />D'LUMINE</a>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="#home" class="nav-link active">Home</a></li>
                        <li><a href="#products" class="nav-link">Shop</a></li>
                        <li><a href="#collaboration" class="nav-link">Collaborations</a></li>
                        <li><a href="#about" class="nav-link">About</a></li>
                        <li><a href="#contact" class="nav-link">Contact</a></li>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="admin/index.php" class="nav-link">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="header-actions">
                    <button class="icon-btn search-btn" id="search-icon">
                        <i class="fas fa-search"></i>
                    </button>
                    <div id="search-container" class="search-bar-hidden">
                        <input type="text" id="search-input" placeholder="Search products...">
                        <button id="search-submit">Search</button>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- User is logged in -->
                        <div class="user-dropdown">
                            <button class="icon-btn user-btn">
                                <i class="fas fa-user"></i>
                            </button>
                            <div class="dropdown-menu">
                                <p>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                                <a href="user/orders.php">My Orders</a>
                                <a href="auth/logout.php">Logout</a>
                                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                                    <a href="admin/index.php">Admin Panel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- User is not logged in -->
                        <div class="auth-buttons">
                            <a href="auth/login.php" class="btn-auth-small">Login</a>
                            <a href="auth/register.php" class="btn-auth-small primary">Sign Up</a>
                        </div>
                    <?php endif; ?>

                    <button class="icon-btn cart-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count">0</span>
                    </button>
                </div>
                <button class="mobile-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">D'LUMINE <span>Says!</span></h1>
                <p class="hero-subtitle">Dress your dreams in gemstone gleams</p>
                <a href="#products" class="btn btn-primary">
                    <span>Shop The Collection</span>
                    <div class="btn-shine"></div>
                </a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="floating-jewelry">
                <div class="jewel jewel-1" data-speed="0.09">
                    <img src="https://i.pinimg.com/1200x/a4/a8/8b/a4a88b6cb64b5e3cce4dc66167573971.jpg" alt="Jewelry">
                </div>
                <div class="jewel jewel-2" data-speed="0.03">
                    <img src="https://i.pinimg.com/1200x/50/38/ad/5038ad270bf5d3cf36625dc3b54f0000.jpg" alt="Jewelry">
                </div>
                <div class="jewel jewel-3" data-speed="0.07">
                    <img src="https://i.pinimg.com/736x/2d/79/47/2d7947a37f50a10957ed8e04e74e7736.jpg" alt="Jewelry">
                </div>
                <div class="jewel jewel-4" data-speed="0.03">
                    <img src="https://i.pinimg.com/1200x/25/ad/cc/25adcc1dceda30446a2405cda2e2fde3.jpg" alt="Jewelry">
                </div>
            </div>
            <div class="particles-container">
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <div class="particle"></div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="scroll-indicator">
            <div class="scroll-line"></div>
            <span>Scroll</span>
        </div>
    </section>

    <!-- Categories Section -->
    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Our Collections</h2>
            <div class="categories-grid">
                <?php
                include_once 'config/database.php';
                include_once 'models/Category.php';
                include_once 'models/Product.php';

                $database = new Database();
                $db = $database->getConnection();
                $category = new Category($db);
                $product = new Product($db);

                $categories = $category->read();

                $categoryImages = [
                    1 => 'https://i.pinimg.com/1200x/a4/a8/8b/a4a88b6cb64b5e3cce4dc66167573971.jpg',
                    2 => 'https://i.pinimg.com/1200x/50/38/ad/5038ad270bf5d3cf36625dc3b54f0000.jpg',
                    3 => 'https://i.pinimg.com/1200x/25/ad/cc/25adcc1dceda30446a2405cda2e2fde3.jpg',
                    4 => 'https://i.pinimg.com/736x/2d/79/47/2d7947a37f50a10957ed8e04e74e7736.jpg'
                ];

                while ($row = $categories->fetch(PDO::FETCH_ASSOC)):
                    // Get products count for this category
                    $products_count = $product->readByCategory($row['id'])->rowCount();
                    ?>
                    <div class="category-card" data-category="<?php echo $row['name']; ?>">
                        <div class="category-image">
                            <img src="<?php echo $categoryImages[$row['id']]; ?>"
                                alt="<?php echo $row['name']; ?> Collection" class="category-img">
                            <div class="category-overlay"></div>
                            <div class="shine"></div>
                            <div class="products-count">
                                <span><?php echo $products_count; ?> Products</span>
                            </div>
                        </div>
                        <div class="category-content">
                            <h3><?php echo ucfirst($row['name']); ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <a href="#products" class="category-link"
                                data-category-filter="<?php echo $row['name']; ?>">Explore Collection</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    </section>

    <!-- Featured Products -->
    <section id="products" class="featured-products">
        <div class="container">
            <h2 class="section-title">Featured Pieces</h2>
            <div class="products-filter">
                <button class="filter-btn active" data-filter="all">All Products</button>
                <?php
                // Reset categories pointer
                $categories = $category->read();
                while ($cat = $categories->fetch(PDO::FETCH_ASSOC)):
                    $products_in_category = $product->readByCategory($cat['id'])->rowCount();
                    if ($products_in_category > 0):
                        ?>
                        <button class="filter-btn" data-filter="<?php echo $cat['name']; ?>">
                            <?php echo ucfirst($cat['name']); ?> (<?php echo $products_in_category; ?>)
                        </button>
                    <?php
                    endif;
                endwhile;
                ?>
            </div>
            <div class="products-grid" id="products-container">
                <!-- Products will be loaded via JavaScript -->
                <div class="loading-products">
                    <div class="loading-spinner"></div>
                    <p>Loading products...</p>
                </div>
            </div>
            <div class="text-center">
                <a href="#products" class="btn btn-outline" id="loadMoreBtn" style="display: none;">
                    <span>Load More Products</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Collaboration Section -->
    <section id="collaboration" class="collaboration">
        <h2 class="section-title">In Collaborations With</h2>
        <div class="container">
            <div class="collab-content">
                <div class="collab-text">
                    <h3 class="collab-name">Kendall Jenner</h3>
                    <p>Discover our exclusive collection curated in partnership with supermodel Kendall Jenner. Each
                        piece reflects her unique style and our shared commitment to sustainable luxury.</p>
                </div>
                <div class="collab-visual">
                    <div class="collab-image">
                        <img src="image/kendalljenner.jpg" alt="Kendall Jenner wearing D'LUMINE" class="collab-img">
                        <div class="collab-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collaboration">
        <div class="container">
            <div class="collab-content">
                <div class="collab-text">
                    <h3 class="collab-name">Gigi Hadid</h3>
                    <p>Discover our exclusive collection curated in partnership with supermodel Gigi Hadid. Each piece
                        reflects her unique style and our shared commitment to sustainable luxury.</p>
                </div>
                <div class="collab-visual">
                    <div class="collab-image">
                        <img src="image/gigihadid.jpg" alt="Gigi Hadid wearing D'LUMINE" class="collab-img">
                        <div class="collab-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collaboration">
        <div class="container">
            <div class="collab-content">
                <div class="collab-text">
                    <h3 class="collab-name">Bella Hadid</h3>
                    <p>Discover our exclusive collection curated in partnership with supermodel Bella Hadid. Each piece
                        reflects her unique style and our shared commitment to sustainable luxury.</p>
                </div>
                <div class="collab-visual">
                    <div class="collab-image">
                        <img src="image/bellahadid.webp" alt="Bella Hadid wearing D'LUMINE" class="collab-img">
                        <div class="collab-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="collaboration">
        <div class="container">
            <div class="collab-content">
                <div class="collab-text">
                    <h3 class="collab-name">Emma Watson</h3>
                    <p>Discover our exclusive collection curated in partnership with actress Emma Watson. Each piece
                        reflects her unique style and our shared commitment to sustainable luxury.</p>
                </div>
                <div class="collab-visual">
                    <div class="collab-image">
                        <img src="image/emmawatson.webp" alt="Emma Watson wearing D'LUMINE" class="collab-img">
                        <div class="collab-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title">Our Story</h2>
                    <p>The story of D'LUMINE! </p>
                    <p>Which shows the story of three friends dreaming of running their own business in one of the
                        world's best countries, <b><i>AUSTRALIA.</i></b></p>
                    <p>Each collection is thoughtfully designed in our London studio and responsibly crafted using
                        recycled precious metals and ethically sourced gemstones.</p>
                    <a href="#" class="btn btn-outline">
                        <span>Learn More</span>
                    </a>
                </div>
                <div class="about-stats">
                    <div class="stat">
                        <span class="stat-number" data-count="17">0</span>
                        <span class="stat-label">Years of Excellence</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-count="74">0</span>
                        <span class="stat-label">Countries</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-count="100">0</span>
                        <span class="stat-label">% Recycled Materials</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>

            <div id="form-message"></div>

            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <h3>Customer Care</h3>
                        <p>+91 8780738150</p>
                        <p>princyramani09@gmail.com</p>
                    </div>
                    <div class="contact-item">
                        <h3>Press & Collaborations</h3>
                        <p>press@dlumine.com</p>
                        <p>collaborations@dlumine.com</p>
                    </div>
                    <div class="contact-item">
                        <h3>Store Locations</h3>
                        <p><b>• London <br>• New York <br>• Paris <br>• Tokyo <br>• Australia <br>• Switzerland</b></p>
                    </div>
                </div>

                <!-- Functional Contact Form -->
                <form class="contact-form" id="contactForm" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span>Send Message</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><b>D'LUMINE!</b></h3>
                    <p><b>MADE WITH LOVE AND CARE.</b></p>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" aria-label="Pinterest">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="#products">All Products</a></li>
                        <li><a href="#products">Earrings</a></li>
                        <li><a href="#products">Rings</a></li>
                        <li><a href="#products">Bracelets</a></li>
                        <li><a href="#products">Necklaces</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#contact">Contact Us</a></li>
                        <li><a href="#">Shipping & Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Care Instructions</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>About</h4>
                    <ul>
                        <li><a href="#about">Our Story</a></li>
                        <li><a href="#">Sustainability</a></li>
                        <li><a href="#collaboration">Collaborations</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> D'LUMINE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Shopping Cart Modal -->
    <div class="cart-modal">
        <div class="cart-modal-content">
            <div class="cart-header">
                <h3>Your Cart</h3>
                <button class="close-cart">&times;</button>
            </div>
            <div class="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="total-price">₹0</span>
                </div>
                <button class="btn btn-primary checkout-btn">
                    <span>Checkout</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="script.js"></script>
</body>

</html>