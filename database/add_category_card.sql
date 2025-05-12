-- Add category_card column to products table if it doesn't exist
ALTER TABLE products ADD COLUMN IF NOT EXISTS category_card VARCHAR(50) DEFAULT NULL; 