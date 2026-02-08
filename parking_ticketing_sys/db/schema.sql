-- Database schema for Parking Ticketing System
-- MySQL

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guard', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    owner_id INT,
    model VARCHAR(100),
    color VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE parking_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE parking_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT,
    slot_number VARCHAR(10) NOT NULL,
    status ENUM('available', 'occupied') DEFAULT 'available',
    UNIQUE (area_id, slot_number),
    FOREIGN KEY (area_id) REFERENCES parking_areas (id) ON DELETE CASCADE
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT,
    slot_id INT,
    checkin_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    checkout_time TIMESTAMP NULL,
    status ENUM('active', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles (id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES parking_slots (id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO
    users (name, email, password, role)
VALUES (
        'Admin User',
        'admin@company.com',
        '$2y$10$examplehashedpassword',
        'admin'
    ),
    (
        'Guard User',
        'guard@company.com',
        '$2y$10$examplehashedpassword',
        'guard'
    ),
    (
        'Employee User',
        'employee@company.com',
        '$2y$10$examplehashedpassword',
        'employee'
    );

INSERT INTO
    parking_areas (name, location)
VALUES (
        'Main Building',
        'Front Entrance'
    ),
    ('Annex', 'Side Street');

INSERT INTO
    parking_slots (area_id, slot_number)
VALUES (1, 'A1'),
    (1, 'A2'),
    (1, 'A3'),
    (1, 'A4'),
    (1, 'A5'),
    (2, 'B1'),
    (2, 'B2'),
    (2, 'B3'),
    (2, 'B4'),
    (2, 'B5');