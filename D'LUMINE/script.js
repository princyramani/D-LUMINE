// Product Data with Unique Images
const products = {
    //EARRINGS
    earrings: [
        {
            id: 1,
            name: "Luna Pearl Earrings",
            price: "₹25,000",
            category: "earrings",
            image: "image/E1.jpeg",
            badge: "BestSeller"
        },
        {
            id: 2,
            name: "Solar Gold Hoops Earrings",
            price: "₹40,000",
            category: "earrings",
            image: "image/E2.jpeg",
            badge: "New"
        },
        {
            id: 3,
            name: "Stardust Studs Earrings",
            price: "₹1,33,000",
            category: "earrings",
            image: "image/E3.jpeg",
            badge: "Limited Addition"
        },
        {
            id: 4,
            name: "Celestial Drops Earrings",
            price: "₹45,269",
            category: "earrings",
            image: "image/E4.jpeg",
            badge: "Limited"
        },
        {
            id: 5,
            name: "Moonstone Climbers Earrings",
            price: "₹76,850",
            category: "earrings",
            image: "image/E5.jpeg",
            badge: "Limited"
        },
        {
            id: 6,
            name: "Aurora Threaders Earrings",
            price: "₹69,890",
            category: "earrings",
            image: "image/E6.jpeg",
            badge: "Limited"
        },
        {
            id: 7,
            name: "Aura Of Missoma Earrings",
            price: "₹25,250",
            category: "earrings",
            image: "image/E7.jpeg",
            badge: "Limited"
        },
        {
            id: 8,
            name: "Classy Studs Earrings",
            price: "₹80,000",
            category: "earrings",
            image: "image/E8.jpeg",
            badge: "BestSeller"
        },
        {
            id: 9,
            name: "Luxury & Classy Earrings",
            price: "₹25,999",
            category: "earrings",
            image: "image/E6.jpeg",
            badge: "BestSeller"
        }
    ],
    rings: [
        //RINGS
        {
            id: 10,
            name: "Solar Signet Ring",
            price: "₹35,000",
            category: "rings",
            image: "image/R1.jpeg",
            badge: "BestSeller"
        },
        {
            id: 11,
            name: "Lunar Band Ring",
            price: "₹95,800",
            category: "rings",
            image: "image/R2.jpeg",
            badge: "BestSeller"
        },
        {
            id: 12,
            name: "Stellar Stacker Ring",
            price: "₹89,000",
            category: "rings",
            image: "image/R3.jpeg",
            badge: "New"
        },
        {
            id: 13,
            name: "Celestial Solitaire Ring",
            price: "₹52,000",
            category: "rings",
            image: "image/R4.jpeg",
            badge: "Limited"
        },
        {
            id: 14,
            name: "Orbit Statement Ring",
            price: "₹68,000",
            category: "rings",
            image: "image/R5.jpeg",
            badge: "Limited"
        },
        {
            id: 15,
            name: "Nova Knuckle Ring",
            price: "₹76,000",
            category: "rings",
            image: "image/R6.jpeg",
            badge: "New"
        },
        {
            id: 16,
            name: "Solar Signet Ring",
            price: "₹65,000",
            category: "rings",
            image: "image/R7.jpeg",
            badge: "BestSeller"
        },
        {
            id: 17,
            name: "Solar Signet Ring",
            price: "₹49,000",
            category: "rings",
            image: "image/R8.jpeg",
            badge: "BestSeller"
        },
        {
            id: 18,
            name: "Solar Signet Ring",
            price: "₹58,000",
            category: "rings",
            image: "image/R9.jpeg",
            badge: "BestSeller"
        }
    ],
    bracelets: [
        //BRACELETS
        {
            id: 19,
            name: "Solar System Bangle Bracelet",
            price: "₹64,000",
            category: "bracelets",
            image: "image/B1.jpeg",
            badge: "BestSeller"
        },
        {
            id: 20,
            name: "Lunar Phase Cuff Bracelet",
            price: "₹62,200",
            category: "bracelets",
            image: "image/B2.jpeg",
            badge: "New"
        },
        {
            id: 21,
            name: "Stardust Chain Bracelet",
            price: "₹49,800",
            category: "bracelets",
            image: "image/B3.jpeg",
            badge: "New"
        },
        {
            id: 22,
            name: "Celestial Charm Bracelet",
            price: "₹77,500",
            category: "bracelets",
            image: "image/B4.jpeg",
            badge: "BestSeller"
        },
        {
            id: 23,
            name: "Orbit Link Bracelet",
            price: "₹56,200",
            category: "bracelets",
            image: "image/B5.jpeg",
            badge: "Limited"
        },
        {
            id: 24,
            name: "Nova Tennis Bracelet",
            price: "₹98,800",
            category: "bracelets",
            image: "image/B6.jpeg",
            badge: "New"
        },
        {
            id: 25,
            name: "Matinee Bracelet",
            price: "₹97,800",
            category: "bracelets",
            image: "image/B7.jpeg",
            badge: "Limited"
        },
        {
            id: 26,
            name: "Londan's Heart Bracelet",
            price: "₹93,800",
            category: "bracelets",
            image: "image/B8.jpeg",
            badge: "BestSeller"
        },
        {
            id: 27,
            name: "Missoma's Special Bracelet",
            price: "₹93,800",
            category: "bracelets",
            image: "image/B9.jpeg",
            badge: "BestSeller"
        }

    ],
    necklaces: [
        //Necklace
        {
            id: 28,
            name: "Solar Pendant Necklace",
            price: "₹65,500",
            category: "necklaces",
            image: "image/N1.jpeg",
            badge: "BestSeller"
        },
        {
            id: 29,
            name: "Lunar Phase Choker Necklace",
            price: "₹52,800",
            category: "necklaces",
            image: "image/N2.jpeg",
            badge: "New"
        },
        {
            id: 30,
            name: "Stardust Lariat Necklace",
            price: "₹79,200",
            category: "necklaces",
            image: "image/N3.jpeg",
            badge: "BestSeller"
        },
        {
            id: 31,
            name: "Celestial Drop Necklace",
            price: "₹86,500",
            category: "necklaces",
            image: "image/N4.jpeg",
            badge: "Limited"
        },
        {
            id: 32,
            name: "Orbit Statement Piece Necklace",
            price: "₹96,200",
            category: "necklaces",
            image: "image/N5.jpeg",
            badge: "Limited"
        },
        {
            id: 33,
            name: "Nova Collar Necklace",
            price: "₹69,500",
            category: "necklaces",
            image: "image/N6.jpeg",
            badge: "New"
        },
        {
            id: 34,
            name: "Aura Necklace",
            price: "₹69,500",
            category: "necklaces",
            image: "image/N7.jpeg",
            badge: "New"
        },
        {
            id: 35,
            name: "Princess Necklace",
            price: "₹69,500",
            category: "necklaces",
            image: "image/N8.jpeg",
            badge: "New"
        },
        {
            id: 36,
            name: "Classy & Marvelous Necklace",
            price: "₹69,500",
            category: "necklaces",
            image: "image/N9.jpeg",
            badge: "New"
        }
    ]
};

// Cart functionality
let cart = JSON.parse(localStorage.getItem('missomaCart')) || [];

// DOM Elements
const navLinks = document.querySelectorAll('.nav-link');
const categoryCards = document.querySelectorAll('.category-card');
const filterBtns = document.querySelectorAll('.filter-btn');
const productsGrid = document.querySelector('.products-grid');
const cartCount = document.querySelector('.cart-count');
const cartBtn = document.querySelector('.cart-btn');
const closeCart = document.querySelector('.close-cart');
const cartModal = document.querySelector('.cart-modal');
const checkoutBtn = document.querySelector('.checkout-btn');
const backToTopBtn = document.querySelector('.back-to-top');
const loadingScreen = document.querySelector('.loading-screen');
const cursor = document.querySelector('.cursor');
const cursorFollower = document.querySelector('.cursor-follower');

// Initialize the application
document.addEventListener('DOMContentLoaded', function () {
    initApp();
});

function initApp() {
    // Hide loading screen
    setTimeout(() => {
        loadingScreen.classList.add('loaded');
    }, 2000);

    // Set up navigation
    setupNavigation();

    // Initialize product data
    initProducts();

    // Set up event listeners
    setupEventListeners();

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize cursor effect
    initCursorEffect();

    // Initialize cart
    initCart();

    // Initialize parallax effect
    initParallax();
}

// Loading Screen
function hideLoadingScreen() {
    loadingScreen.style.opacity = '0';
    setTimeout(() => {
        loadingScreen.style.display = 'none';
    }, 1000);
}

// Cursor Effect
function initCursorEffect() {
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';

        cursorFollower.style.left = e.clientX + 'px';
        cursorFollower.style.top = e.clientY + 'px';
    });

    // Add hover effects for interactive elements
    const interactiveElements = document.querySelectorAll('button, a, .product-card, .category-card, .icon-btn');

    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            cursor.style.transform = 'scale(1.5)';
            cursorFollower.style.transform = 'scale(1.2)';
            cursorFollower.style.opacity = '0.7';
        });

        element.addEventListener('mouseleave', () => {
            cursor.style.transform = 'scale(1)';
            cursorFollower.style.transform = 'scale(1)';
            cursorFollower.style.opacity = '1';
        });
    });
}

// Parallax Effect
function initParallax() {
    const jewels = document.querySelectorAll('.jewel');

    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;

        jewels.forEach(jewel => {
            const speed = jewel.getAttribute('data-speed');
            jewel.style.transform = `translateY(${rate * speed}px)`;
        });
    });
}

// Navigation
function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('.nav');

    // Smooth scrolling for navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                // Update active link
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                // Scroll to section
                window.scrollTo({
                    top: targetSection.offsetTop - 80,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                if (nav.classList.contains('active')) {
                    nav.classList.remove('active');
                    mobileMenuBtn.classList.remove('active');
                }
            }
        });
    });
    // searchbar active
    document.addEventListener('DOMContentLoaded', function () {
        const searchIcon = document.getElementById('search-icon');
        const searchContainer = document.getElementById('search-container');
        const searchInput = document.getElementById('search-input');
        const searchSubmit = document.getElementById('search-submit');

        // 1. Toggle the visibility of the search bar
        searchIcon.addEventListener('click', function (event) {
            event.preventDefault(); // Stop the default link behavior

            // Toggle the 'search-bar-active' class to show/hide the container
            searchContainer.classList.toggle('search-bar-active');

            // If it's now active, focus on the input field
            if (searchContainer.classList.contains('search-bar-active')) {
                searchInput.focus();
            }
        });

        // 2. Handle the actual search submission (when the user presses Enter or clicks 'Search')

        // For button click
        searchSubmit.addEventListener('click', performSearch);

        // For 'Enter' key press inside the input
        searchInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                performSearch(event);
            }
        });

        function performSearch(event) {
            // Prevent form submission if this was part of a form
            if (event) event.preventDefault();

            const query = searchInput.value.trim();

            if (query.length > 0) {
                console.log("Searching for:", query);

                // --- * YOUR ACTIVE SEARCH CODE GOES HERE * ---

                // Option A: Redirect to a search results page
                // window.location.href = /search-results.html?q=${encodeURIComponent(query)};

                // Option B: Run a JavaScript function to filter products on the current page
                // filterProducts(query); 

                // ------------------------------------------------

            } else {
                // Optional: Alert the user or provide feedback for an empty search
                alert("Please enter a search term.");
            }
        }
    });


    // Mobile menu toggle
    mobileMenuBtn.addEventListener('click', function () {
        this.classList.toggle('active');
        nav.classList.toggle('active');
    });

    // Update active link on scroll
    window.addEventListener('scroll', function () {
        const scrollPosition = window.scrollY;

        // Update header appearance
        const header = document.querySelector('.header');
        if (scrollPosition > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Update active navigation link
        const sections = document.querySelectorAll('section');
        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.clientHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });

        // Show/hide back to top button
        if (scrollPosition > 500) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });

    // Back to top functionality
    backToTopBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Products
function initProducts() {
    loadProducts('all');
}

function loadProducts(category) {
    productsGrid.innerHTML = '';

    let productsToShow = [];

    if (category === 'all') {
        // Combine all products
        Object.values(products).forEach(categoryProducts => {
            productsToShow = productsToShow.concat(categoryProducts);
        });
    } else {
        productsToShow = products[category] || [];
    }

    // Create product cards
    productsToShow.forEach((product, index) => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card fade-in';
        productCard.setAttribute('data-category', product.category);

        const badgeHTML = product.badge ? `<span class="product-badge">${product.badge}</span>` : '';

        productCard.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}" class="product-img">
                ${badgeHTML}
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-category">${product.category}</p>
                <p class="product-price">${product.price}</p>
                <button class="add-to-cart" data-id="${product.id}">
                    <span>Add to Cart</span>
                </button>
            </div>
        `;

        productsGrid.appendChild(productCard);

        // Add staggered animation
        setTimeout(() => {
            productCard.classList.add('animate');
        }, index * 100);
    });

    // Add event listeners to "Add to Cart" buttons
    const addToCartBtns = document.querySelectorAll('.add-to-cart');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = parseInt(this.getAttribute('data-id'));
            addToCart(productId);

            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Event Listeners
function setupEventListeners() {
    // Category cards on home page
    categoryCards.forEach(card => {
        card.addEventListener('click', function () {
            const category = this.getAttribute('data-category');

            // Set the active filter button
            filterBtns.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-filter') === category) {
                    btn.classList.add('active');
                }
            });

            loadProducts(category);

            // Scroll to products section
            document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Filter buttons on products page
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const category = this.getAttribute('data-filter');

            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            loadProducts(category);
        });
    });
    const buttons =
    document.querySelectorAll(".category-btn");
    

    // Cart functionality
    cartBtn.addEventListener('click', function () {
        cartModal.classList.add('active');
        updateCartDisplay();
    });

    closeCart.addEventListener('click', function () {
        cartModal.classList.remove('active');
    });

    // Close modals when clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === cartModal) {
            cartModal.classList.remove('active');
        }
    });

    // Checkout button
    checkoutBtn.addEventListener('click', function () {
        if (cart.length > 0) {
            // Add checkout animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);

            setTimeout(() => {
                alert('Thank you for your purchase! Your order has been placed.');
                clearCart();
                cartModal.classList.remove('active');
            }, 500);
        } else {
            alert('Your cart is empty. Add some items before checking out.');
        }
    });

    // Form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Add submit animation
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                submitBtn.style.transform = 'scale(1)';
            }, 150);

            setTimeout(() => {
                alert('Form submitted successfully!');
                this.reset();
            }, 500);
        });
    });

    function toggleSidebar() {
        document.querySelector(".sidebar").classList.toggle("active");
    }

    // Animate stats
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-count'));
        animateValue(stat, 0, target, 2000);
    });
}

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');

                // Add specific animations based on element type
                if (entry.target.classList.contains('section-title')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('collab-text') || entry.target.classList.contains('collab-visual')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('about-text') || entry.target.classList.contains('about-stats')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('contact-form')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('category-card')) {
                    entry.target.classList.add('animate');
                }
                if (entry.target.classList.contains('product-card')) {
                    entry.target.classList.add('animate');
                }
            }
        });
    }, observerOptions);

    // Observe all elements that need animation
    const elementsToObserve = document.querySelectorAll('.fade-in, .section-title, .collab-text, .collab-visual, .about-text, .about-stats, .contact-form, .category-card, .product-card');

    elementsToObserve.forEach(element => {
        observer.observe(element);
    });
}

// Cart functionality
function initCart() {
    updateCartCount();
}

function addToCart(productId) {
    // Find the product in any category
    let product = null;
    for (const category in products) {
        product = products[category].find(p => p.id === productId);
        if (product) break;
    }

    if (!product) return;

    // Check if product is already in cart
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: 1
        });
    }

    // Save to localStorage
    localStorage.setItem('missomaCart', JSON.stringify(cart));

    // Update UI
    updateCartCount();

    // Show confirmation with animation
    showNotification(`${product.name} added to cart!`);
}

function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = totalItems;

    // Add bounce animation
    cartCount.style.transform = 'scale(1.3)';
    setTimeout(() => {
        cartCount.style.transform = 'scale(1)';
    }, 300);
}

function updateCartDisplay() {
    const cartItems = document.querySelector('.cart-items');
    const totalPrice = document.querySelector('.total-price');

    cartItems.innerHTML = '';

    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        totalPrice.textContent = '₹0';
        return;
    }

    let total = 0;

    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item fade-in';

        // Extract numeric price from string (remove ₹ and commas)
        const price = parseInt(item.price.replace(/[₹,]/g, ''));
        total += price * item.quantity;

        cartItem.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
            </div>
            <div class="cart-item-details">
                <h4 class="cart-item-name">${item.name}</h4>
                <p class="cart-item-price">${item.price}</p>
                <div class="cart-item-quantity">
                    <button class="quantity-btn decrease" data-id="${item.id}">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${item.id}">
                    <button class="quantity-btn increase" data-id="${item.id}">+</button>
                </div>
            </div>
            <button class="remove-item" data-id="${item.id}">×</button>
        `;

        cartItems.appendChild(cartItem);

        // Animate cart item entry
        setTimeout(() => {
            cartItem.classList.add('visible');
        }, 100);
    });

    // Format total with Indian numbering system
    totalPrice.textContent = '₹' + total.toLocaleString('en-IN');

    // Add event listeners for quantity changes and removal
    const decreaseBtns = document.querySelectorAll('.decrease');
    const increaseBtns = document.querySelectorAll('.increase');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const removeBtns = document.querySelectorAll('.remove-item');

    decreaseBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = parseInt(this.getAttribute('data-id'));
            updateQuantity(productId, -1);
        });
    });

    increaseBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = parseInt(this.getAttribute('data-id'));
            updateQuantity(productId, 1);
        });
    });

    quantityInputs.forEach(input => {
        input.addEventListener('change', function () {
            const productId = parseInt(this.getAttribute('data-id'));
            const newQuantity = parseInt(this.value);

            if (newQuantity < 1) {
                this.value = 1;
                updateQuantity(productId, 0, 1);
            } else {
                updateQuantity(productId, 0, newQuantity);
            }
        });
    });

    removeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = parseInt(this.getAttribute('data-id'));
            removeFromCart(productId);
        });
    });
}

function updateQuantity(productId, change, setQuantity = null) {
    const item = cart.find(item => item.id === productId);

    if (!item) return;

    if (setQuantity !== null) {
        item.quantity = setQuantity;
    } else {
        item.quantity += change;

        if (item.quantity < 1) {
            removeFromCart(productId);
            return;
        }
    }

    // Save to localStorage
    localStorage.setItem('missomaCart', JSON.stringify(cart));

    // Update UI
    updateCartCount();
    updateCartDisplay();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);

    // Save to localStorage
    localStorage.setItem('missomaCart', JSON.stringify(cart));

    // Update UI
    updateCartCount();
    updateCartDisplay();
}

function clearCart() {
    cart = [];
    localStorage.setItem('missomaCart', JSON.stringify(cart));
    updateCartCount();
}

// Utility functions
function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;

    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        z-index: 3000;
        transform: translateX(100%) rotate(10deg);
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-weight: 500;
        max-width: 300px;
        text-align: center;
    `;

    // Add to page
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0) rotate(0deg)';
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%) rotate(10deg)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Add some interactive hover effects
document.addEventListener('DOMContentLoaded', function () {
    // Add shimmer effect to product images on hover
    const productImages = document.querySelectorAll('.product-img');
    productImages.forEach(img => {
        img.addEventListener('mouseenter', function () {
            this.style.filter = 'brightness(1.1) saturate(1.2)';
        });

        img.addEventListener('mouseleave', function () {
            this.style.filter = 'brightness(1) saturate(1)';
        });
    });

    // Add pulse animation to category links
    const categoryLinks = document.querySelectorAll('.category-link');
    categoryLinks.forEach(link => {
        link.addEventListener('mouseenter', function () {
            this.style.animation = 'pulse 1s infinite';
        });

        link.addEventListener('mouseleave', function () {
            this.style.animation = 'none';
        });
    });
});

// Add CSS for pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);