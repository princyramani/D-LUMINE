-- Create database
CREATE DATABASE IF NOT EXISTS jewelry_shop;
USE jewelry_shop;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    badge VARCHAR(50),
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('earrings', 'Elegant drops, studs, and classy hoops'),
('rings', 'Statement pieces and delicate bands'),
('bracelets', 'Chains, cuffs, and charm bracelets'),
('necklaces', 'Pendants, chains, and layered looks');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, image, badge, stock_quantity) VALUES
('Luna Pearl Earrings', 'Beautiful pearl earrings with gold setting', 25000.00, 1, 'image/E1.jpeg', 'BestSeller', 10),
('Solar Gold Hoops Earrings', 'Elegant gold hoop earrings', 40000.00, 1, 'image/E2.jpeg', 'New', 15),
('Stardust Studs Earrings', 'Sparkling stud earrings with diamonds', 133000.00, 1, 'image/E3.jpeg', 'Limited Edition', 5),
('Solar Signet Ring', 'Classic signet ring in gold', 35000.00, 2, 'image/R1.jpeg', 'BestSeller', 8),
('Lunar Band Ring', 'Minimalist band ring', 95800.00, 2, 'image/R2.jpeg', 'BestSeller', 12),
('Stellar Stacker Ring', 'Stackable ring set', 89000.00, 2, 'image/R3.jpeg', 'New', 6),
('Solar System Bangle Bracelet', 'Intricate bangle bracelet', 64000.00, 3, 'image/B1.jpeg', 'BestSeller', 5),
('Lunar Phase Cuff Bracelet', 'Elegant cuff bracelet', 62200.00, 3, 'image/B2.jpeg', 'New', 7),
('Stardust Chain Bracelet', 'Delicate chain bracelet', 49800.00, 3, 'image/B3.jpeg', 'New', 9),
('Solar Pendant Necklace', 'Stunning pendant necklace', 65500.00, 4, 'image/N1.jpeg', 'BestSeller', 6),
('Lunar Phase Choker Necklace', 'Modern choker design', 52800.00, 4, 'image/N2.jpeg', 'New', 9),
('Stardust Lariat Necklace', 'Elegant lariat necklace', 79200.00, 4, 'image/N3.jpeg', 'BestSeller', 4);

-- Create admin user (password: admin123)
INSERT INTO users (name, email, password) VALUES 
('Admin User', 'admin@dlumine.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Add to your existing jewelry_shop database

-- Enhanced users table with more fields
ALTER TABLE users ADD COLUMN role ENUM('customer','admin') DEFAULT 'customer';
ALTER TABLE users ADD COLUMN email_verified BOOLEAN DEFAULT FALSE;
ALTER TABLE users ADD COLUMN verification_token VARCHAR(100);
ALTER TABLE users ADD COLUMN reset_token VARCHAR(100);
ALTER TABLE users ADD COLUMN reset_expires DATETIME;

-- Update admin user role
UPDATE users SET role = 'admin' WHERE email = 'admin@dlumine.com';

-- Enhanced orders table
ALTER TABLE orders ADD COLUMN customer_name VARCHAR(100);
ALTER TABLE orders ADD COLUMN customer_email VARCHAR(100);
ALTER TABLE orders ADD COLUMN customer_phone VARCHAR(20);
ALTER TABLE orders ADD COLUMN billing_address TEXT;

-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_method ENUM('cod','card','upi') DEFAULT 'cod',
    payment_status ENUM('pending','completed','failed','refunded') DEFAULT 'pending',
    amount DECIMAL(10,2),
    transaction_id VARCHAR(100),
    payment_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Cart table for logged-in users
CREATE TABLE user_carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Insert sample customers
INSERT INTO users (name, email, password, phone, address, role) VALUES 
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', '123 Main St, New York, NY', 'customer'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1987654321', '456 Oak Ave, Los Angeles, CA', 'customer');

-- Insert sample orders with customer info
INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, total_amount, status, shipping_address, billing_address, payment_method) VALUES
(2, 'John Doe', 'john@example.com', '+1234567890', 25000.00, 'delivered', '123 Main St, New York, NY', '123 Main St, New York, NY', 'cod'),
(3, 'Jane Smith', 'jane@example.com', '+1987654321', 40000.00, 'shipped', '456 Oak Ave, Los Angeles, CA', '456 Oak Ave, Los Angeles, CA', 'card');

-- Remove verification fields from users table
ALTER TABLE users 
DROP COLUMN email_verified,
DROP COLUMN verification_token,
DROP COLUMN reset_token,
DROP COLUMN reset_expires;

-- Insert sample customer with simple password
INSERT INTO users (name, email, password, phone, address, role) VALUES 
('Demo Customer', 'customer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', '123 Demo St, Demo City', 'customer');