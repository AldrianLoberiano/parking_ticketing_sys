-- Parking Ticketing System Database Schema
-- Database: parking_ticketing

CREATE DATABASE IF NOT EXISTS parking_ticketing;

USE parking_ticketing;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guard', 'employee') NOT NULL DEFAULT 'employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Parking areas table
CREATE TABLE IF NOT EXISTS parking_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Parking slots table
CREATE TABLE IF NOT EXISTS parking_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT NOT NULL,
    slot_number VARCHAR(10) NOT NULL,
    status ENUM(
        'available',
        'occupied',
        'maintenance'
    ) DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (area_id) REFERENCES parking_areas (id) ON DELETE CASCADE,
    UNIQUE KEY unique_slot (area_id, slot_number)
);

-- Vehicles table
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    owner_id INT NOT NULL,
    model VARCHAR(100),
    color VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Tickets table
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    slot_id INT NOT NULL,
    checkin_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    checkout_time TIMESTAMP NULL,
    status ENUM('active', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles (id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES parking_slots (id) ON DELETE CASCADE
);

-- Insert default admin user
INSERT INTO
    users (name, email, password, role)
VALUES (
        'Admin User',
        'admin@parking.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin'
    ),
    (
        'Guard User',
        'guard@parking.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'guard'
    ),
    (
        'Employee User',
        'employee@parking.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'employee'
    )
ON DUPLICATE KEY UPDATE
    email = email;

-- Insert sample parking areas
INSERT INTO
    parking_areas (name, location)
VALUES (
        'Main Building',
        'Front of main entrance'
    ),
    ('Underground', 'Level B1'),
    (
        'East Wing',
        'East side parking'
    )
ON DUPLICATE KEY UPDATE
    name = name;

-- Insert sample parking slots
INSERT INTO
    parking_slots (area_id, slot_number, status)
VALUES (1, 'A01', 'available'),
    (1, 'A02', 'available'),
    (1, 'A03', 'available'),
    (2, 'B01', 'available'),
    (2, 'B02', 'available'),
    (3, 'C01', 'available'),
    (3, 'C02', 'available')
ON DUPLICATE KEY UPDATE
    slot_number = slot_number;