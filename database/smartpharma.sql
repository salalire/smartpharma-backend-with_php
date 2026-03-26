CREATE DATABASE smartpharma;
USE smartpharma;


CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_products_owner ON products(owner_id);
CREATE INDEX idx_orders_user ON orders(user_id);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'owner') DEFAULT 'user',
    phone varchar(15) ,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    requires_prescription BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT DEFAULT 1,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE pharmacy_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,

    pharmacy_name VARCHAR(150),

    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),

    tin_number VARCHAR(50),
    license_image VARCHAR(255),

    region VARCHAR(100),
    city VARCHAR(100),
    sub_city VARCHAR(100),
    woreda VARCHAR(50),

    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);