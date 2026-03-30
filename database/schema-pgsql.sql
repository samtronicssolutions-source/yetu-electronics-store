-- Yetu Database Schema for PostgreSQL
-- Use this if you choose PostgreSQL on Render

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INTEGER REFERENCES categories(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    image VARCHAR(255),
    stock INTEGER DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
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

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER,
    price DECIMAL(10,2)
);

-- Create admin_users table
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
INSERT INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@yetu.com')
ON CONFLICT (username) DO NOTHING;

-- Insert main categories
INSERT INTO categories (name, parent_id) VALUES 
('Audio', NULL),
('TV', NULL),
('Accessories', NULL),
('Utensils', NULL)
ON CONFLICT (name) WHERE parent_id IS NULL DO NOTHING;

-- Insert subcategories for Utensils
INSERT INTO categories (name, parent_id) 
SELECT 'Knives', id FROM categories WHERE name='Utensils'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Cookware', id FROM categories WHERE name='Utensils'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Cutlery', id FROM categories WHERE name='Utensils'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Kitchen Tools', id FROM categories WHERE name='Utensils'
ON CONFLICT DO NOTHING;

-- Insert subcategories for Audio
INSERT INTO categories (name, parent_id) 
SELECT 'Headphones', id FROM categories WHERE name='Audio'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Speakers', id FROM categories WHERE name='Audio'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Earbuds', id FROM categories WHERE name='Audio'
ON CONFLICT DO NOTHING;

INSERT INTO categories (name, parent_id) 
SELECT 'Microphones', id FROM categories WHERE name='Audio'
ON CONFLICT DO NOTHING;
