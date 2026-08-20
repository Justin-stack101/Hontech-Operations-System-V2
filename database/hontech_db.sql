-- =======================================================
-- Hontech Auto Center Inc. - Database Schema (hontech_db)
-- XAMPP (Apache + MariaDB/MySQL + PHP)
-- =======================================================

CREATE DATABASE IF NOT EXISTS `hontech_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hontech_db`;

-- -------------------------------------------------------
-- 1. Roles & Permissions Table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `role_name`, `display_name`, `description`) VALUES
(1, 'super_admin', 'Super Administrator', 'Full administrative privileges, user management, and system settings.'),
(2, 'manager', 'Service Manager / Supervisor', 'Oversees service operations, job orders, scheduling, and technician dispatch.'),
(3, 'technician', 'Senior Mechanic / Technician', 'Executes vehicle repairs, logs inspection checklists, and updates job orders.'),
(4, 'marketing', 'Marketing & Content Editor', 'Manages public blog articles, customer notices, promotions, and FAQs.')
ON DUPLICATE KEY UPDATE `display_name`=VALUES(`display_name`);

-- -------------------------------------------------------
-- 2. Departments Table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `departments` (`id`, `name`, `description`) VALUES
(1, 'Executive Management', 'Strategic leadership and company operations.'),
(2, 'Mechanical & PMS Services', 'Engine overhaul, underchassis, brakes, and preventive maintenance.'),
(3, 'Electrical & Computer Diagnostics', 'Scanning, wiring, air-conditioning, and electronic module repairs.'),
(4, 'Body, Paint & Detailing', 'Collision repair, polyurethane baking paint, and ceramic coating.'),
(5, 'Customer Relations & Reception', 'Front desk, customer reception, estimation, and cashiering.'),
(6, 'Marketing & Public Relations', 'Digital content, client communications, and brand strategy.')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- -------------------------------------------------------
-- 3. Employees / Staff Users Table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_code` VARCHAR(20) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) DEFAULT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL,
    `department_id` INT DEFAULT NULL,
    `avatar_url` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default demo accounts (All passwords: password123)
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `email`, `phone`, `password_hash`, `role_id`, `department_id`, `status`) VALUES
(1, 'HON-ADM-001', 'Engr. Justin Honrado', 'admin@hontech.com', '+63 917 123 4567', '$2y$10$dVfRhqbUzp0S37hnDpZYf.FmqovzW6dTY9R9lZFUb3qrtMeO7ksiy', 1, 1, 'active'),
(2, 'HON-MGR-002', 'Marco Santos (Service Mgr)', 'manager@hontech.com', '+63 918 234 5678', '$2y$10$dVfRhqbUzp0S37hnDpZYf.FmqovzW6dTY9R9lZFUb3qrtMeO7ksiy', 2, 2, 'active'),
(3, 'HON-TECH-003', 'Danilo Reyes (Lead Tech)', 'technician@hontech.com', '+63 919 345 6789', '$2y$10$dVfRhqbUzp0S37hnDpZYf.FmqovzW6dTY9R9lZFUb3qrtMeO7ksiy', 3, 2, 'active'),
(4, 'HON-MKT-004', 'Elena Cruz (Marketing Editor)', 'marketing@hontech.com', '+63 920 456 7890', '$2y$10$dVfRhqbUzp0S37hnDpZYf.FmqovzW6dTY9R9lZFUb3qrtMeO7ksiy', 4, 6, 'active')
ON DUPLICATE KEY UPDATE `email`=VALUES(`email`);

-- -------------------------------------------------------
-- 4. Customer Inquiries Table (Contact Form)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `service_type` VARCHAR(100) DEFAULT 'General Inquiry',
    `message` TEXT NOT NULL,
    `status` ENUM('pending', 'contacted', 'resolved') DEFAULT 'pending',
    `admin_notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `inquiries` (`name`, `email`, `phone`, `service_type`, `message`, `status`) VALUES
('Juan Dela Cruz', 'juan.delacruz@example.com', '+63 912 345 6789', 'Periodic Maintenance Service', 'Inquiring about 40,000 km PMS package for 2021 Toyota Vios.', 'pending'),
('Maria Clara', 'maria.clara@example.com', '+63 922 876 5432', 'Air Conditioning Service', 'AC is blowing warm air when idling in traffic. Need diagnosis.', 'contacted');

-- -------------------------------------------------------
-- 5. Service Bookings & Cost Estimator Leads
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_reference` VARCHAR(20) NOT NULL UNIQUE,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(30) NOT NULL,
    `vehicle_make` VARCHAR(50) DEFAULT NULL,
    `vehicle_model` VARCHAR(100) NOT NULL,
    `plate_number` VARCHAR(20) DEFAULT NULL,
    `selected_services` TEXT NOT NULL,
    `estimated_cost` DECIMAL(10,2) DEFAULT 0.00,
    `preferred_date` DATE NOT NULL,
    `preferred_time` VARCHAR(20) DEFAULT 'Morning (8AM-12PM)',
    `additional_notes` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `bookings` (`booking_reference`, `customer_name`, `customer_email`, `customer_phone`, `vehicle_make`, `vehicle_model`, `plate_number`, `selected_services`, `estimated_cost`, `preferred_date`, `preferred_time`, `status`) VALUES
('HON-BK-2026-001', 'Roberto Gomez', 'roberto.g@example.com', '+63 917 555 1234', 'Honda', 'Civic RS Turbo 2022', 'NBT-8921', 'Full Synthetic Oil Change, Brake Inspection, Computer Scan', 6500.00, CURDATE() + INTERVAL 2 DAY, 'Morning (8AM-12PM)', 'confirmed'),
('HON-BK-2026-002', 'Patricia Tan', 'ptan.sample@example.com', '+63 928 666 4321', 'Mitsubishi', 'Montero Sport 2020', 'DAZ-4319', 'Underchassis Bushing Replacement, Wheel Alignment', 9800.00, CURDATE() + INTERVAL 3 DAY, 'Afternoon (1PM-5PM)', 'pending');

-- -------------------------------------------------------
-- 6. Blog Categories Table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Maintenance Tips', 'maintenance-tips', 'Practical car care and preventive maintenance advice from our casa-trained mechanics.'),
(2, 'Company News', 'company-news', 'Announcements, shop expansions, events, and milestones at Hontech Auto Center.'),
(3, 'Promotions & Packages', 'promotions-packages', 'Special seasonal discount packages, fleet service discounts, and promos.'),
(4, 'Automotive Guides', 'automotive-guides', 'In-depth mechanical guides on engine care, electrical diagnostics, and body repairs.')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- -------------------------------------------------------
-- 7. Blog Posts Table
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `author_id` INT DEFAULT 1,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `excerpt` TEXT NOT NULL,
    `content` LONGTEXT NOT NULL,
    `featured_image` VARCHAR(255) DEFAULT 'images/service-image.png',
    `views_count` INT DEFAULT 0,
    `status` ENUM('draft', 'published') DEFAULT 'published',
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`author_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `posts` (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `views_count`, `status`, `published_at`) VALUES
(1, 1, 1, '5 Telltale Signs Your Car Brakes Need Immediate Inspection', '5-telltale-signs-car-brakes-need-immediate-inspection', 'Don\'t wait for a dangerous squeal or spongy pedal. Learn the key warning signs of brake rotor wear and pad deterioration.', '<p>Your vehicle\'s brake system is the single most important safety mechanism protecting you and your passengers on Metro Manila roads. Over time, friction wear degrades brake pads and heat causes rotor warping.</p><h3>1. High-Pitched Squealing or Screeching</h3><p>Most modern brake pads are fitted with an audible acoustic wear indicator—a small metallic tab that makes a sharp squeal when the friction material drops below 3mm.</p><h3>2. Spongy or Soft Brake Pedal</h3><p>If your foot presses down further than usual or feels spongy, moisture or air bubbles may have entered the hydraulic brake lines, or there may be a master cylinder fluid leak.</p><h3>3. Vibration Under Braking</h3><p>A pulsating brake pedal or steering wheel shake during high-speed deceleration usually signals uneven rotor wear or warped brake discs caused by thermal shock.</p><h3>4. Vehicle Pulling to One Side</h3><p>A stuck caliper slide pin or uneven pad wear will pull your car toward one side during braking, requiring immediate shop balancing.</p><h3>Schedule a Complete Brake System Check at Hontech</h3><p>At Hontech Auto Center, our CASA-trained technicians measure rotor thickness with digital calipers, inspect caliper piston boots, and test brake fluid moisture levels to guarantee 100% road safety.</p>', 'images/service-image.png', 142, 'published', NOW() - INTERVAL 5 DAY),

(2, 2, 4, 'Hontech Auto Center Expands Service Bays to 1,200 SQM in Metro Manila', 'hontech-auto-center-expands-service-bays-1200-sqm', 'To serve our growing community of vehicle owners, Hontech has upgraded its service footprint with state-of-the-art hydraulic lifters and computer diagnostics.', '<p>Since our establishment in 2020, Hontech Auto Center Inc. has remained committed to a singular standard: delivering dealership-grade, CASA-like automotive care at transparent and fair prices.</p><p>We are thrilled to announce the completion of our expanded 1,200-square-meter workshop facility in Metro Manila. This upgrade includes:</p><ul><li>6 dedicated heavy hydraulic lifters for swift underchassis and suspension overhauls.</li><li>An advanced, climate-controlled polyurethane baking paint booth.</li><li>OEM-level electronic diagnostic scanners capable of multi-brand ECU coding and sensor calibrations.</li><li>A comfortable, air-conditioned customer lounge with high-speed Wi-Fi and complimentary refreshments.</li></ul><p>We invite you to visit our facility and experience the Hontech standard for your next PMS or repair!</p>', 'images/hero-bg.png', 285, 'published', NOW() - INTERVAL 12 DAY),

(3, 4, 3, 'Why Regular PMS (Preventive Maintenance) Saves Tens of Thousands in Future Repairs', 'why-regular-pms-saves-tens-of-thousands-future-repairs', 'Routine oil changes, filter renewals, and fluid checks are small investments that prevent catastrophic engine and transmission failures.', '<p>Many car owners delay Preventive Maintenance Service (PMS) to save short-term money, unaware that oil degradation and clogged filters exponentially accelerate friction wear on moving internal engine components.</p><h3>Engine Oil Breakdown and Sludge Formation</h3><p>Thermal cycling degrades engine oil additives over 5,000 to 10,000 kilometers. When oil loses its lubricating film strength, metal-on-metal contact scores cylinder walls and creates internal sludge that chokes oil passages.</p><h3>Coolant pH and Radiator Corrosion</h3><p>Coolant becomes acidic as it ages, causing internal electrolysis that eats through water pumps and radiator cores—leading to engine overheating and costly head gasket warping.</p><h3>The Hontech Advantage</h3><p>Hontech provides comprehensive 50-point PMS checklists matching official manufacturer standards at a fraction of dealership labor markups.</p>', 'images/values-bg.png', 97, 'published', NOW() - INTERVAL 18 DAY)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- -------------------------------------------------------
-- 8. Job Orders Table (Technician Workflow)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `job_order_no` VARCHAR(25) NOT NULL UNIQUE,
    `booking_id` INT DEFAULT NULL,
    `technician_id` INT DEFAULT NULL,
    `vehicle_info` VARCHAR(150) NOT NULL,
    `work_description` TEXT NOT NULL,
    `status` ENUM('assigned', 'inspecting', 'parts_waiting', 'in_progress', 'completed') DEFAULT 'assigned',
    `inspection_notes` TEXT DEFAULT NULL,
    `parts_used` TEXT DEFAULT NULL,
    `started_at` DATETIME DEFAULT NULL,
    `completed_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`technician_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `job_orders` (`job_order_no`, `booking_id`, `technician_id`, `vehicle_info`, `work_description`, `status`, `inspection_notes`) VALUES
('JO-2026-001', 1, 3, '2022 Honda Civic RS Turbo (NBT-8921)', 'PMS 40K, Brake pad replacement, Electronic diagnostic scan', 'in_progress', 'Brake pad front left at 2.5mm. Replaced with OEM ceramic pads. Engine oil drained cleanly.');

-- -------------------------------------------------------
-- 9. Audit Logs Table (Activity & Security Trail)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `audit_logs` (`employee_id`, `action`, `description`, `ip_address`) VALUES
(1, 'SYSTEM_INIT', 'Database schema initialized with default roles, departments, and seed data.', '127.0.0.1');
