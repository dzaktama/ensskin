-- Database: ensskin_db
-- Jalankan script ini di phpMyAdmin atau MySQL client Anda

CREATE DATABASE IF NOT EXISTS ensskin_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ensskin_db;

-- Tabel Users (untuk sistem membership)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Products (katalog produk dan treatment)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category ENUM('facial', 'body', 'laser', 'skincare') NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'product-placeholder.jpg',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Promos (informasi promo aktif)
CREATE TABLE promos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    discount_percentage INT,
    banner_image VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Bookings (reservasi treatment)
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    treatment_type VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Transactions (riwayat pembelian)
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    booking_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    transaction_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Settings (pengaturan website)
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data admin default
INSERT INTO users (fullname, email, phone, password, role) VALUES 
('Admin Ensskin', 'admin@ensskin.com', '082112568941', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Password default: password123 (WAJIB DIGANTI!)

-- Insert sample products
INSERT INTO products (name, category, description, price, image) VALUES 
('Facial Brightening', 'facial', 'Treatment wajah untuk mencerahkan dan meratakan warna kulit', 250000.00, 'facial-brightening.jpg'),
('Acne Treatment', 'facial', 'Perawatan khusus untuk mengatasi jerawat dan bekasnya', 300000.00, 'acne-treatment.jpg'),
('Body Whitening', 'body', 'Perawatan pemutihan seluruh tubuh dengan bahan alami', 500000.00, 'body-whitening.jpg'),
('Laser Hair Removal', 'laser', 'Penghilangan bulu permanen dengan teknologi laser', 800000.00, 'laser-hair.jpg'),
('Serum Vitamin C', 'skincare', 'Serum pencerah wajah dengan kandungan Vitamin C tinggi', 150000.00, 'serum-vitc.jpg');

-- Insert sample promo
INSERT INTO promos (title, description, discount_percentage, banner_image, start_date, end_date) VALUES 
('Promo Tahun Baru 2025', 'Diskon hingga 30% untuk semua treatment facial!', 30, 'promo-january.jpg', '2025-01-01', '2025-01-31');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES 
('clinic_name', 'Ensskin Beauty Glow'),
('clinic_address', 'Jl. Keindahan No. 123, Jakarta Selatan'),
('clinic_phone', '082112568941'),
('clinic_email', 'info@ensskin.com'),
('opening_hours', 'Senin - Sabtu: 09:00 - 20:00'),
('instagram_url', 'https://instagram.com/ensskin'),
('whatsapp_number', '6282112568941');