// Product Data - Now fetched from PHP API
let products = {};
let cart = JSON.parse(localStorage.getItem('dlumineCart')) || [];

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
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('form-message');

// Initialize the application
document.addEventListener('DOMContentLoaded', function () {
    initApp();
});

async function initApp() {
    // Hide loading screen
    setTimeout(() => {
        loadingScreen.classList.add('loaded');
    }, 2000);

    // Set up navigation
    setupNavigation();

    // Initialize product data
    await initProducts();

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

// Fetch products from PHP API
async function fetchProducts(category = 'all') {
    try {
        const response = await fetch(`api/products.php?category=${category}`);
        return await response.json();
    } catch (error) {
        console.error('Error fetching products:', error);
        return [];
    }
}

// Initialize products
async function initProducts() {
    products = await fetchProducts('all');
    loadProducts('all');
}

// Load products to grid
async function loadProducts(category) {
    productsGrid.innerHTML = '<div class="loading">Loading products...</div>';
    
    let productsToShow = category === 'all' ? products : await fetchProducts(category);
    
    productsGrid.innerHTML = '';
    
    if (productsToShow.length === 0) {
        productsGrid.innerHTML = '<div class="no-products">No products found in this category.</div>';
        return;
    }

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
                <p class="product-price">₹${parseInt(product.price).toLocaleString('en-IN')}</p>
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
            const product = productsToShow.find(p => p.id == productId);
            if (product) {
                addToCart(product);
            }

            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Search functionality
function setupSearch() {
    const searchIcon = document.getElementById('search-icon');
    const searchContainer = document.getElementById('search-container');
    const searchInput = document.getElementById('search-input');
    const searchSubmit = document.getElementById('search-submit');

    searchIcon.addEventListener('click', function (event) {
        event.preventDefault();
        searchContainer.classList.toggle('search-bar-active');
        
        if (searchContainer.classList.contains('search-bar-active')) {
            searchInput.focus();
        }
    });

    searchSubmit.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            performSearch(event);
        }
    });

    async function performSearch(event) {
        if (event) event.preventDefault();
        
        const query = searchInput.value.trim();
        
        if (query.length > 0) {
            try {
                const response = await fetch(`api/products.php?search=${encodeURIComponent(query)}`);
                const searchResults = await response.json();
                
                // Update products grid with search results
                productsGrid.innerHTML = '';
                
                if (searchResults.length === 0) {
                    productsGrid.innerHTML = '<div class="no-products">No products found matching your search.</div>';
                    return;
                }
                
                searchResults.forEach((product, index) => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card fade-in';
                    
                    const badgeHTML = product.badge ? `<span class="product-badge">${product.badge}</span>` : '';
                    
                    productCard.innerHTML = `
                        <div class="product-image">
                            <img src="${product.image}" alt="${product.name}" class="product-img">
                            ${badgeHTML}
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <p class="product-category">${product.category}</p>
                            <p class="product-price">₹${parseInt(product.price).toLocaleString('en-IN')}</p>
                            <button class="add-to-cart" data-id="${product.id}">
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    `;
                    
                    productsGrid.appendChild(productCard);
                    
                    setTimeout(() => {
                        productCard.classList.add('animate');
                    }, index * 100);
                });
                
            } catch (error) {
                console.error('Search error:', error);
                showNotification('Error performing search. Please try again.', 'error');
            }
        } else {
            showNotification('Please enter a search term.', 'error');
        }
    }
}

// Contact Form Handler
function setupContactForm() {
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('contact_process.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showFormMessage(data.message, 'success');
                    contactForm.reset();
                } else {
                    showFormMessage(data.message, 'error');
                }
            } catch (error) {
                showFormMessage('An error occurred. Please try again.', 'error');
            } finally {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
}

function showFormMessage(message, type) {
    if (formMessage) {
        formMessage.innerHTML = `
            <div class="alert alert-${type}" style="color: ${type === 'success' ? 'green' : 'red'}; margin-bottom: 15px; padding: 10px; border-radius: 5px; background: ${type === 'success' ? '#f0fff0' : '#fff0f0'};">
                ${message}
            </div>
        `;
        
        // Scroll to message
        formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Cart functionality
function addToCart(product) {
    // Check if product is already in cart
    const existingItem = cart.find(item => item.id === product.id);

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
    localStorage.setItem('dlumineCart', JSON.stringify(cart));

    // Update UI
    updateCartCount();

    // Show confirmation with animation
    showNotification(`${product.name} added to cart!`, 'success');
}

function updateCartCount() {
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

        const price = parseInt(item.price);
        total += price * item.quantity;

        cartItem.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
            </div>
            <div class="cart-item-details">
                <h4 class="cart-item-name">${item.name}</h4>
                <p class="cart-item-price">₹${price.toLocaleString('en-IN')}</p>
                <div class="cart-item-quantity">
                    <button class="quantity-btn decrease" data-id="${item.id}">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${item.id}">
                    <button class="quantity-btn increase" data-id="${item.id}">+</button>
                </div>
            </div>
            <button class="remove-item" data-id="${item.id}">×</button>
        `;

        cartItems.appendChild(cartItem);

        setTimeout(() => {
            cartItem.classList.add('visible');
        }, 100);
    });

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

    localStorage.setItem('dlumineCart', JSON.stringify(cart));
    updateCartCount();
    updateCartDisplay();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('dlumineCart', JSON.stringify(cart));
    updateCartCount();
    updateCartDisplay();
}

function clearCart() {
    cart = [];
    localStorage.setItem('dlumineCart', JSON.stringify(cart));
    updateCartCount();
}

// Event Listeners
function setupEventListeners() {
    setupSearch();
    setupContactForm();

    // Category cards on home page
    categoryCards.forEach(card => {
        card.addEventListener('click', function () {
            const category = this.getAttribute('data-category');

            filterBtns.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-filter') === category) {
                    btn.classList.add('active');
                }
            });

            loadProducts(category);
            document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Filter buttons on products page
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const category = this.getAttribute('data-filter');

            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            loadProducts(category);
        });
    });

    // Cart functionality
    cartBtn.addEventListener('click', function () {
        cartModal.classList.add('active');
        updateCartDisplay();
    });

    closeCart.addEventListener('click', function () {
        cartModal.classList.remove('active');
    });

    window.addEventListener('click', function (e) {
        if (e.target === cartModal) {
            cartModal.classList.remove('active');
        }
    });

    checkoutBtn.addEventListener('click', function () {
        if (cart.length > 0) {
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
            showNotification('Your cart is empty. Add some items before checking out.', 'error');
        }
    });

    // Animate stats
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-count'));
        animateValue(stat, 0, target, 2000);
    });
}

// Navigation setup
function setupNavigation() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('.nav');

    // Smooth scrolling for navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#')) {
                const targetSection = document.querySelector(targetId);

                if (targetSection) {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });

                    if (nav.classList.contains('active')) {
                        nav.classList.remove('active');
                        mobileMenuBtn.classList.remove('active');
                    }
                }
            }
        });
    });

    // Mobile menu toggle
    mobileMenuBtn.addEventListener('click', function () {
        this.classList.toggle('active');
        nav.classList.toggle('active');
    });

    // Update active link on scroll
    window.addEventListener('scroll', function () {
        const scrollPosition = window.scrollY;
        const header = document.querySelector('.header');
        
        if (scrollPosition > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

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

// Cursor Effect
function initCursorEffect() {
    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';

        cursorFollower.style.left = e.clientX + 'px';
        cursorFollower.style.top = e.clientY + 'px';
    });

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

    const elementsToObserve = document.querySelectorAll('.fade-in, .section-title, .collab-text, .collab-visual, .about-text, .about-stats, .contact-form, .category-card, .product-card');

    elementsToObserve.forEach(element => {
        observer.observe(element);
    });
}

// Initialize cart
function initCart() {
    updateCartCount();
}

// Utility functions
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #4CAF50, #45a049)' : 'linear-gradient(135deg, #f44336, #d32f2f)'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 3000;
        transform: translateX(100%) rotate(10deg);
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-weight: 500;
        max-width: 300px;
        text-align: center;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(0) rotate(0deg)';
    }, 10);

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

// Add CSS for additional styles
const additionalStyles = `
    .loading { text-align: center; padding: 40px; font-size: 1.2rem; color: var(--text-light); }
    .no-products { text-align: center; padding: 40px; font-size: 1.2rem; color: var(--text-light); }
    .search-bar-active { width: 250px !important; opacity: 1 !important; }
    #search-container { position: relative; display: flex; gap: 10px; align-items: center; }
    #search-input { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 5px; flex: 1; }
    #search-submit { padding: 8px 15px; background: var(--primary-color); color: white; border: none; border-radius: 5px; cursor: pointer; }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// Enhanced cart functionality with authentication check
function proceedToCheckout() {
    if(cart.length === 0) {
        showNotification('Your cart is empty.', 'error');
        return;
    }
    
    // Check if user is logged in
    const isLoggedIn = document.querySelector('.user-dropdown') !== null;
    
    if(isLoggedIn) {
        window.location.href = 'checkout.php';
    } else {
        // Store cart in sessionStorage for retrieval after login
        sessionStorage.setItem('pendingCart', JSON.stringify(cart));
        window.location.href = 'auth/login.php?redirect=checkout';
    }
}

// Update checkout button in cart modal
function updateCartDisplay() {
    // ... existing code ...
    
    // Update checkout button
    const checkoutBtn = document.querySelector('.checkout-btn');
    if(checkoutBtn) {
        checkoutBtn.onclick = proceedToCheckout;
    }
}

// Check for pending cart after login
function checkPendingCart() {
    const pendingCart = sessionStorage.getItem('pendingCart');
    if(pendingCart && window.location.pathname.includes('checkout.php')) {
        // Restore cart from sessionStorage
        cart = JSON.parse(pendingCart);
        sessionStorage.removeItem('pendingCart');
        updateCartCount();
        updateCartDisplay();
    }
}

// Call this on page load
document.addEventListener('DOMContentLoaded', function() {
    checkPendingCart();
});
// Yeh code check karein
// document.querySelectorAll('.filter-btn').forEach(btn => {
//     btn.addEventListener('click', function() {
//         const filter = this.getAttribute('data-filter');
//         filterProducts(filter);
//     });
// });

// function filterProducts(category) {
//     const products = document.querySelectorAll('.product-card');
    
//     products.forEach(product => {
//         const productCategory = product.getAttribute('data-category');
        
//         if (category === 'all' || productCategory === category) {
//             product.style.display = 'block';
//         } else {
//             product.style.display = 'none';
//         }
//     });
// }

// Enhanced product loading with categories
let allProducts = [];
let currentCategory = 'all';
let displayedProducts = 0;
const productsPerLoad = 8;

// Fetch all products
async function fetchAllProducts() {
    try {
        const response = await fetch(`api/products.php?category=all`);
        allProducts = await response.json();
        return allProducts;
    } catch (error) {
        console.error('Error fetching products:', error);
        return [];
    }
}

// Load products to grid with category filtering
async function loadProducts(category = 'all', reset = true) {
    const productsGrid = document.getElementById('products-container');
    
    if (reset) {
        productsGrid.innerHTML = '<div class="loading-products"><div class="loading-spinner"></div><p>Loading products...</p></div>';
        displayedProducts = 0;
    }
    
    currentCategory = category;
    
    // If we haven't loaded all products yet, fetch them
    if (allProducts.length === 0) {
        allProducts = await fetchAllProducts();
    }
    
    // Filter products by category
    let productsToShow = [];
    if (category === 'all') {
        productsToShow = allProducts;
    } else {
        productsToShow = allProducts.filter(product => 
            product.category.toLowerCase() === category.toLowerCase()
        );
    }
    
    // Clear loading state
    if (reset) {
        productsGrid.innerHTML = '';
    }
    
    if (productsToShow.length === 0) {
        productsGrid.innerHTML = `
            <div class="no-products">
                <i class="fas fa-gem"></i>
                <h3>No Products Found</h3>
                <p>No products available in this category yet.</p>
                <button class="btn btn-primary" onclick="loadProducts('all')">View All Products</button>
            </div>
        `;
        document.getElementById('loadMoreBtn').style.display = 'none';
        return;
    }
    
    // Calculate how many products to show
    const startIndex = reset ? 0 : displayedProducts;
    const endIndex = Math.min(startIndex + productsPerLoad, productsToShow.length);
    const productsToDisplay = productsToShow.slice(startIndex, endIndex);
    
    // Display products
    productsToDisplay.forEach((product, index) => {
        const productCard = createProductCard(product, startIndex + index);
        productsGrid.appendChild(productCard);
    });
    
    displayedProducts = endIndex;
    
    // Show/hide load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (endIndex < productsToShow.length) {
        loadMoreBtn.style.display = 'inline-block';
        loadMoreBtn.onclick = () => loadProducts(category, false);
    } else {
        loadMoreBtn.style.display = 'none';
    }
    
    // Update active filter button
    updateActiveFilter(category);
}

// Create product card HTML
function createProductCard(product, index) {
    const productCard = document.createElement('div');
    productCard.className = 'product-card fade-in';
    productCard.setAttribute('data-category', product.category);

    const badgeHTML = product.badge ? `<span class="product-badge">${product.badge}</span>` : '';
    const price = parseFloat(product.price).toLocaleString('en-IN');

    productCard.innerHTML = `
        <div class="product-image">
            <img src="${product.image}" alt="${product.name}" class="product-img" loading="lazy">
            ${badgeHTML}
            <div class="product-category-tag">${product.category}</div>
        </div>
        <div class="product-info">
            <h3 class="product-name">${product.name}</h3>
            <p class="product-description">${product.description || 'Elegant jewelry piece'}</p>
            <p class="product-price">₹${price}</p>
            <button class="add-to-cart" data-id="${product.id}">
                <i class="fas fa-shopping-bag"></i>
                <span>Add to Cart</span>
            </button>
        </div>
    `;

    // Add staggered animation
    setTimeout(() => {
        productCard.classList.add('animate');
    }, index * 100);

    // Add event listener to add to cart button
    const addToCartBtn = productCard.querySelector('.add-to-cart');
    addToCartBtn.addEventListener('click', function() {
        addToCart(product);
        
        // Add click animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });

    return productCard;
}

// Update active filter button
function updateActiveFilter(category) {
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-filter') === category) {
            btn.classList.add('active');
        }
    });
}

// Initialize products
async function initProducts() {
    await loadProducts('all');
}

// Update event listeners for filter buttons
function setupEventListeners() {
    // ... existing code ...
    
    // Category cards on home page
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            loadProducts(category);
            document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Category links in category cards
    document.querySelectorAll('.category-link[data-category-filter]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const category = this.getAttribute('data-category-filter');
            loadProducts(category);
            document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Filter buttons on products page
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-filter');
            loadProducts(category);
        });
    });
    
    // ... rest of existing event listeners ...
}