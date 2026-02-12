-- Cytti Homes Database Schema
-- Run this script to create the tables inside Clever Cloud database

-- IMPORTANT:
-- Clever Cloud does not allow CREATE DATABASE on DEV plan.
-- So we remove CREATE DATABASE and USE cytti_db.

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Properties table
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    amenities JSON,
    type VARCHAR(50),
    rating DECIMAL(3,2) DEFAULT 0.00,
    image VARCHAR(255),
    images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    rating DECIMAL(3,2) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('mpesa', 'card', 'bank') NOT NULL,
    transaction_id VARCHAR(255) UNIQUE,
    merchant_request_id VARCHAR(255),
    checkout_request_id VARCHAR(255),
    mpesa_receipt_number VARCHAR(255),
    phone_number VARCHAR(20),
    status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    response_code VARCHAR(10),
    response_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Insert sample properties
INSERT INTO properties (title, location, price, description, amenities, type, rating, image, images) VALUES
('Luxury Apartment in Nairobi CBD', 'Nairobi, Kenya', 15000.00, 'Modern 2-bedroom apartment with stunning city views, fully furnished and equipped with all amenities.', '["WiFi", "Pool", "Gym", "Parking"]', 'apartment', 4.80, 'photo/H1.jpeg', '["photo/H1.jpeg", "photo/T1.png"]'),
('Cozy House in Karen', 'Karen, Nairobi', 25000.00, 'Spacious 3-bedroom house with a beautiful garden, perfect for families or groups.', '["Garden", "Parking", "WiFi"]', 'house', 4.50, 'photo/B2.jpeg', '["photo/B2.jpeg", "photo/H2.jpeg"]'),
('Elegant Villa in Westlands', 'Westlands, Nairobi', 35000.00, 'Luxurious 4-bedroom villa with private pool and modern interiors.', '["Pool", "Gym", "WiFi", "Parking"]', 'villa', 4.90, 'photo/T1.png', '["photo/T1.png", "photo/H1.jpeg"]'),
('Stylish Loft in Kilimani', 'Kilimani, Nairobi', 12000.00, 'Contemporary loft space ideal for professionals, with rooftop access.', '["WiFi", "Gym"]', 'loft', 4.20, 'photo/H2.jpeg', '["photo/H2.jpeg", "photo/B2.jpeg"]'),
('Penthouse Suite in Parklands', 'Parklands, Nairobi', 40000.00, 'Exclusive penthouse with panoramic views and premium amenities.', '["Pool", "Gym", "WiFi", "Parking"]', 'penthouse', 5.00, 'photo/H1.jpeg', '["photo/H1.jpeg", "photo/T1.png"]'),
('Charming Cottage in Limuru', 'Limuru, Kenya', 18000.00, 'Quaint cottage surrounded by nature, perfect for a peaceful retreat.', '["Garden", "WiFi"]', 'house', 4.30, 'photo/B2.jpeg', '["photo/B2.jpeg", "photo/H2.jpeg"]');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_properties_type ON properties(type);
CREATE INDEX idx_properties_location ON properties(location);
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_property_id ON bookings(property_id);
CREATE INDEX idx_reviews_property_id ON reviews(property_id);
