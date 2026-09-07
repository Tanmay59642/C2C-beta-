-- ============================================================================
-- CAMPUS2COMMUNITY (C2C) SOVEREIGN CIVIC PLATFORM
-- PRODUCTION DATABASE SCHEMA & INITIAL SEED DATA (schema.sql)
-- Optimized for phpMyAdmin, MySQL, MariaDB, and SQLite
-- ============================================================================

-- Create and Select Database for MySQL / phpMyAdmin
CREATE DATABASE IF NOT EXISTS `campus2community` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `campus2community`;

-- ----------------------------------------------------------------------------
-- 1. USERS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` VARCHAR(36) PRIMARY KEY,
    `aadhaar_number` VARCHAR(14) UNIQUE NOT NULL,
    `full_name` VARCHAR(120) NOT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `role` VARCHAR(20) NOT NULL,
    `email` VARCHAR(120) UNIQUE NOT NULL,
    `institution_name` VARCHAR(150) DEFAULT NULL,
    `department` VARCHAR(100) DEFAULT NULL,
    `phone_number` VARCHAR(15) DEFAULT NULL,
    `digilocker_verified` TINYINT(1) DEFAULT 1,
    `account_status` VARCHAR(20) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------------------------
-- 2. CIVIC TICKETS / PROBLEMS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `civic_tickets` (
    `ticket_id` VARCHAR(36) PRIMARY KEY,
    `ticket_code` VARCHAR(30) UNIQUE NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `priority_level` VARCHAR(20) NOT NULL,
    `sla_hours` INT DEFAULT 48,
    `district_name` VARCHAR(100) NOT NULL,
    `state_name` VARCHAR(100) DEFAULT 'Maharashtra',
    `geo_latitude` DECIMAL(10, 8) DEFAULT NULL,
    `geo_longitude` DECIMAL(11, 8) DEFAULT NULL,
    `affected_population` INT DEFAULT 5000,
    `problem_description` TEXT NOT NULL,
    `status` VARCHAR(30) DEFAULT 'pending',
    `reported_by_user_id` VARCHAR(36) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`reported_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
);

-- ----------------------------------------------------------------------------
-- 3. AI MATCHES TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_matches` (
    `match_id` VARCHAR(36) PRIMARY KEY,
    `ticket_id` VARCHAR(36) NOT NULL,
    `assigned_university` VARCHAR(150) NOT NULL,
    `department_lab` VARCHAR(100) NOT NULL,
    `faculty_mentor_name` VARCHAR(120) NOT NULL,
    `solution_architecture` TEXT NOT NULL,
    `match_confidence_score` DECIMAL(5, 2) DEFAULT 94.20,
    `match_status` VARCHAR(30) DEFAULT 'assigned',
    `matched_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `civic_tickets` (`ticket_id`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 4. ESCROW CONTRACTS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `escrow_contracts` (
    `escrow_id` VARCHAR(36) PRIMARY KEY,
    `ticket_id` VARCHAR(36) NOT NULL,
    `csr_sponsor_name` VARCHAR(150) NOT NULL,
    `committed_amount_inr` DECIMAL(12, 2) NOT NULL,
    `section_135_ref` VARCHAR(80) NOT NULL,
    `escrow_status` VARCHAR(30) DEFAULT 'locked',
    `disbursed_amount_inr` DECIMAL(12, 2) DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `civic_tickets` (`ticket_id`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 5. SENSOR TELEMETRY TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sensor_telemetry` (
    `telemetry_id` VARCHAR(36) PRIMARY KEY,
    `ticket_id` VARCHAR(36) NOT NULL,
    `node_identifier` VARCHAR(60) NOT NULL,
    `sensor_type` VARCHAR(50) NOT NULL,
    `last_reading_value` VARCHAR(80) NOT NULL,
    `ping_status` VARCHAR(20) DEFAULT 'healthy',
    `last_ping_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ticket_id`) REFERENCES `civic_tickets` (`ticket_id`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 6. ACADEMIC CREDITS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `academic_credits` (
    `credit_id` VARCHAR(36) PRIMARY KEY,
    `student_user_id` VARCHAR(36) NOT NULL,
    `ticket_id` VARCHAR(36) NOT NULL,
    `aicte_points_awarded` INT DEFAULT 45,
    `faculty_signoff_hash` VARCHAR(128) NOT NULL,
    `provisional_patent_id` VARCHAR(80) DEFAULT NULL,
    `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`ticket_id`) REFERENCES `civic_tickets` (`ticket_id`) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- SAMPLE SEED DATA INSERTS
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `users` (`user_id`, `aadhaar_number`, `full_name`, `password`, `role`, `email`, `institution_name`, `department`, `phone_number`, `digilocker_verified`, `account_status`)
VALUES 
('usr-001-student', '9022 6343 3612', 'Parnavi Janbhor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'parnavi.janbhor@vsit.edu.in', 'Vidyalankar Institute of Technology (VSIT)', 'AI & Data Science Lab', '+91 9022634336', 1, 'active'),
('usr-002-govt', '9137 0258 0712', 'Collectorate Officer Thane', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'govt', 'collector.thane@maharashtra.gov.in', 'Bhiwandi Municipal Council', 'District Administration Desk', '+91 9137025807', 1, 'active'),
('usr-003-csr', '8828 3594 0412', 'Tata CleanTech Escrow Manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'csr', 'csr.escrow@tata.com', 'Tata Trusts CSR Division', 'Section 135 Compliance Wing', '+91 8828359404', 1, 'active');

INSERT IGNORE INTO `civic_tickets` (`ticket_id`, `ticket_code`, `title`, `category`, `priority_level`, `sla_hours`, `district_name`, `state_name`, `geo_latitude`, `geo_longitude`, `affected_population`, `problem_description`, `status`, `reported_by_user_id`)
VALUES 
('tkt-001-arsenic', 'C2C-2026-W482', 'Severe Industrial Arsenic Runoff in Bhiwandi Rural', 'water', 'critical', 24, 'Bhiwandi Rural', 'Maharashtra', 19.28120000, 73.04820000, 8500, 'High concentration of industrial arsenic and turbidity observed after recent drainage overflow in rural wells.', 'pilot_active', 'usr-002-govt'),
('tkt-002-potholes', 'C2C-2026-R904', 'Highway Potholes Affecting Agricultural Produce Freight', 'roads', 'high', 48, 'Palghar Coastal Belt', 'Maharashtra', 19.69670000, 72.76990000, 12000, 'Heavy monsoon damage causing delays in fresh fish and agricultural produce supply chains to Mumbai markets.', 'ai_matched', 'usr-002-govt'),
('tkt-003-solar', 'C2C-2026-S110', 'Solar Microgrid Battery Optimization for Tribal Healthcare PHC', 'solar', 'medium', 72, 'Gadchiroli Tribal Block', 'Maharashtra', 20.18440000, 80.00280000, 3200, 'Continuous temperature telemetry required for primary healthcare storage in remote off-grid tribal belts.', 'pilot_active', 'usr-002-govt');

INSERT IGNORE INTO `ai_matches` (`match_id`, `ticket_id`, `assigned_university`, `department_lab`, `faculty_mentor_name`, `solution_architecture`, `match_confidence_score`, `match_status`)
VALUES 
('mtc-001', 'tkt-001-arsenic', 'Veermata Jijabai Technological Institute (VJTI)', 'Environmental & Chemical Engg Lab', 'Dr. S. Kulkarni', 'Solar-powered UV/Arsenic electro-flocculation unit paired with real-time sub-surface telemetry nodes reporting to district dashboard.', 98.40, 'active_pilot'),
('mtc-002', 'tkt-002-potholes', 'Vidyalankar Institute of Technology (VSIT)', 'Civil Infrastructure & AI Lab', 'Dr. P. Deshmukh', 'Recycled plastic-asphalt composite mix paired with IoT load sensor tiles for real-time traffic stress monitoring.', 94.20, 'assigned'),
('mtc-003', 'tkt-003-solar', 'IIT Bombay Research Labs', 'Energy Science & Microgrid Lab', 'Prof. A. Mehta', 'Edge-AI battery management system paired with cellular temperature telemetry for vaccine storage.', 96.80, 'active_pilot');

INSERT IGNORE INTO `escrow_contracts` (`escrow_id`, `ticket_id`, `csr_sponsor_name`, `committed_amount_inr`, `section_135_ref`, `escrow_status`, `disbursed_amount_inr`)
VALUES 
('esc-001', 'tkt-001-arsenic', 'Tata Trusts CleanTech Innovation Fund', 450000.00, 'CSR-SEC135-2026-881', 'partially_released', 250000.00),
('esc-002', 'tkt-002-potholes', 'Mahindra CSR Foundation', 280000.00, 'CSR-SEC135-2026-904', 'locked', 0.00),
('esc-003', 'tkt-003-solar', 'Reliance Foundation Rural Energy', 820000.00, 'CSR-SEC135-2026-110', 'partially_released', 400000.00);

INSERT IGNORE INTO `sensor_telemetry` (`telemetry_id`, `ticket_id`, `node_identifier`, `sensor_type`, `last_reading_value`, `ping_status`)
VALUES 
('tel-001', 'tkt-001-arsenic', 'NODE-BHIV-W482-01', 'Arsenic Electro-Cell / Turbidity', '0.012 mg/L (Safe)', 'healthy'),
('tel-002', 'tkt-001-arsenic', 'NODE-BHIV-W482-02', 'Sub-surface pH Sensor', '7.4 pH (Optimal)', 'healthy'),
('tel-003', 'tkt-003-solar', 'NODE-GAD-S110-01', 'Vaccine Storage Temp Sensor', '4.2 °C (Stable)', 'healthy');

INSERT IGNORE INTO `academic_credits` (`credit_id`, `student_user_id`, `ticket_id`, `aicte_points_awarded`, `faculty_signoff_hash`, `provisional_patent_id`)
VALUES 
('crd-001', 'usr-001-student', 'tkt-001-arsenic', 45, 'HASH_FACULTY_SIGNOFF_VJTI_VSIT_881', 'PROV-PATENT-C2C-2026-098');
