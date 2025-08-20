-- Create house_bookings table manually
-- This is for PAYMENT TO ACCESS LANDLORD INFORMATION only
-- NOT for actual room booking like hotels

CREATE TABLE `house_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `house_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `reference` varchar(255) DEFAULT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `payment_url` text DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'TZS',
  `price` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  -- REMOVED: is_checked_in, is_checked_out, is_cancelled, from_date, to_date, checked_in_at, checked_out_at
  -- These are NOT needed for house information access
  `paid_at` timestamp NULL DEFAULT NULL,
  `receipt_url` varchar(255) DEFAULT NULL,
  `receipt_filename` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `house_bookings_customer_id_index` (`customer_id`),
  KEY `house_bookings_house_id_index` (`house_id`),
  KEY `house_bookings_is_paid_index` (`is_paid`),
  CONSTRAINT `house_bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `house_bookings_house_id_foreign` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add booking_price column to houses table if it doesn't exist
-- This is for the fee charged for information access
ALTER TABLE `houses` ADD COLUMN IF NOT EXISTS `booking_price` decimal(10,2) DEFAULT 1000.00 AFTER `price`; 