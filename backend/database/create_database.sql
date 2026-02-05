-- EduAid Database Creation Script
-- Run this script in MySQL Workbench to create the database

-- Create database
CREATE DATABASE IF NOT EXISTS eduaid 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE eduaid;

-- Note: Tables will be created automatically by Laravel migrations
-- Run: php artisan migrate

-- After migrations, run: php artisan db:seed
-- This will create default roles and admin user
