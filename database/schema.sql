-- Yetu Database Schema
-- Compatible with PostgreSQL and MySQL

-- Create tables (MySQL version)
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE,
    customer_name VARCHAR(255),
    customer_phone VARCHAR(20),
    customer_email VARCHAR(255),
    total_amount DECIMAL(10,2),
    payment_status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50),
    mpesa_transaction_id VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123 - hashed with bcrypt)
INSERT INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@yetu.com')
ON DUPLICATE KEY UPDATE username=username;

-- Insert main categories
INSERT INTO categories (name, parent_id) VALUES 
('Audio', NULL),
('TV', NULL),
('Accessories', NULL),
('Utensils', NULL)
ON DUPLICATE KEY UPDATE name=name;

-- Insert subcategories for Utensils
INSERT INTO categories (name, parent_id) VALUES 
('Knives', (SELECT id FROM categories WHERE name='Utensils')),
('Cookware', (SELECT id FROM categories WHERE name='Utensils')),
('Cutlery', (SELECT id FROM categories WHERE name='Utensils')),
('Kitchen Tools', (SELECT id FROM categories WHERE name='Utensils'))
ON DUPLICATE KEY UPDATE name=name;

-- Insert subcategories for Audio
INSERT INTO categories (name, parent_id) VALUES 
('Headphones', (SELECT id FROM categories WHERE name='Audio')),
('Speakers', (SELECT id FROM categories WHERE name='Audio')),
('Earbuds', (SELECT id FROM categories WHERE name='Audio')),
('Microphones', (SELECT id FROM categories WHERE name='Audio'))
ON DUPLICATE KEY UPDATE name=name;
