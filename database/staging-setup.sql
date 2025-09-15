-- FOODLY API - Staging Database Setup
-- Execute this script to create staging database and user

-- Create staging database
CREATE DATABASE IF NOT EXISTS staging_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create staging user (optional, for better security)
-- CREATE USER 'staging_user'@'localhost' IDENTIFIED BY 'staging_password_2025';
-- GRANT ALL PRIVILEGES ON staging_db.* TO 'staging_user'@'localhost';

-- Or if using root user, just grant access to staging_db
GRANT ALL PRIVILEGES ON staging_db.* TO 'root'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show databases to confirm
SHOW DATABASES LIKE 'staging_db';

-- Use the staging database
USE staging_db;

-- Show current database
SELECT DATABASE() as current_database;