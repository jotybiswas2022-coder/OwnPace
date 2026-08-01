-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 04:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flexipay_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `bank_code` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `website`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Joty Biswas', 'joty-biswas', NULL, NULL, NULL, 1, '2026-07-22 05:30:54', '2026-07-22 05:30:54');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `channel` varchar(255) NOT NULL,
  `audience` varchar(255) NOT NULL DEFAULT 'all',
  `recipient_filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recipient_filters`)),
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_logs`
--

CREATE TABLE `campaign_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `channel` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Espresso', '2026-07-22 05:31:04', '2026-07-22 05:31:04');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address_line1` text NOT NULL,
  `address_line2` text DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Nigeria',
  `postal_code` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_addresses`
--

INSERT INTO `delivery_addresses` (`id`, `user_id`, `label`, `recipient_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `country`, `postal_code`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Website for joty-biswas.free.nf', 'Joty Biswas', '01403107510', '22/2, Kabi Nazrul Sarak, west Tootpara', NULL, 'Khulna', 'Bangladesh', 'Nigeria', NULL, 0, '2026-07-22 05:47:33', '2026-07-22 05:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_trackings`
--

CREATE TABLE `delivery_trackings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `location` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `tracked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_requests`
--

CREATE TABLE `exchange_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `current_product_id` bigint(20) UNSIGNED NOT NULL,
  `requested_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'What technologies do you work with?', 'weeret', 'Indicator', 0, 1, '2026-07-25 08:06:09', '2026-07-25 08:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `installment_payments`
--

CREATE TABLE `installment_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `installment_number` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `installment_payments`
--

INSERT INTO `installment_payments` (`id`, `order_id`, `installment_number`, `amount`, `due_date`, `paid_date`, `status`, `paid_amount`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 914.67, '2026-08-25', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(2, 3, 2, 914.67, '2026-09-24', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(3, 3, 3, 914.67, '2026-10-24', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(4, 3, 4, 914.67, '2026-11-23', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(5, 3, 5, 914.67, '2026-12-23', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(6, 3, 6, 914.67, '2027-01-22', NULL, 'pending', 0.00, NULL, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `installment_plans`
--

CREATE TABLE `installment_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `installment_plans`
--

INSERT INTO `installment_plans` (`id`, `name`, `type`, `duration`, `duration_days`, `interest_rate`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, '4 Weeks', 'weekly', 4, 28, 5.00, NULL, 1, 4, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(2, '8 Weeks', 'weekly', 8, 56, 5.00, NULL, 1, 8, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(3, '12 Weeks', 'weekly', 12, 84, 5.00, NULL, 1, 12, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(4, '16 Weeks', 'weekly', 16, 112, 10.00, NULL, 1, 16, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(5, '20 Weeks', 'weekly', 20, 140, 10.00, NULL, 1, 20, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(6, '24 Weeks', 'weekly', 24, 168, 10.00, NULL, 1, 24, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(7, '28 Weeks', 'weekly', 28, 196, 15.00, NULL, 1, 28, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(8, '32 Weeks', 'weekly', 32, 224, 15.00, NULL, 1, 32, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(9, '36 Weeks', 'weekly', 36, 252, 15.00, NULL, 1, 36, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(10, '40 Weeks', 'weekly', 40, 280, 15.00, NULL, 1, 40, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(11, '1 Month', 'monthly', 1, 30, 8.00, NULL, 1, 101, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(12, '2 Months', 'monthly', 2, 60, 8.00, NULL, 1, 102, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(13, '3 Months', 'monthly', 3, 90, 8.00, NULL, 1, 103, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(14, '4 Months', 'monthly', 4, 120, 12.00, NULL, 1, 104, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(15, '5 Months', 'monthly', 5, 150, 12.00, NULL, 1, 105, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(16, '6 Months', 'monthly', 6, 180, 12.00, NULL, 1, 106, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(17, '7 Months', 'monthly', 7, 210, 18.00, NULL, 1, 107, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(18, '8 Months', 'monthly', 8, 240, 18.00, NULL, 1, 108, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(19, '9 Months', 'monthly', 9, 270, 18.00, NULL, 1, 109, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(20, '10 Months', 'monthly', 10, 300, 22.00, NULL, 1, 110, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(21, '11 Months', 'monthly', 11, 330, 22.00, NULL, 1, 111, '2026-07-22 05:25:08', '2026-07-22 05:25:08'),
(22, '12 Months', 'monthly', 12, 360, 22.00, NULL, 1, 112, '2026-07-22 05:25:08', '2026-07-22 05:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_settings`
--

CREATE TABLE `insurance_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Insurance Fee',
  `rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `insurance_settings`
--

INSERT INTO `insurance_settings` (`id`, `name`, `rate`, `type`, `is_enabled`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Insurance Fee', 10.00, 'percentage', 1, '10% insurance fee on total order amount', '2026-07-22 05:42:43', '2026-07-22 05:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_01_31_155048_create_settings_table', 1),
(2, '2026_07_01_000031_update_settings_table', 2),
(3, '2026_02_25_145317_create_sliders_table', 3),
(4, '2026_01_21_173017_create_categories_table', 4),
(5, '2026_07_01_000004_create_brands_table', 5),
(6, '2026_07_01_000005_create_suppliers_table', 6),
(7, '2026_07_01_000001_create_roles_table', 7),
(8, '2026_07_01_000002_create_role_permissions_table', 8),
(9, '2026_07_01_000007_create_product_images_table', 9),
(10, '2026_07_01_000008_create_installment_plans_table', 10),
(11, '2026_07_01_000009_create_orders_table', 11),
(12, '2026_07_01_000010_create_order_items_table', 12),
(13, '2026_07_01_000011_create_installment_payments_table', 13),
(14, '2026_07_01_000012_create_payment_transactions_table', 14),
(15, '2026_07_01_000013_create_wallets_table', 15),
(16, '2026_07_01_000014_create_wallet_transactions_table', 16),
(17, '2026_07_01_000015_create_delivery_addresses_table', 17),
(18, '2026_07_01_000016_create_delivery_trackings_table', 18),
(19, '2026_07_01_000017_create_product_fees_table', 19),
(20, '2026_07_01_000019_create_plan_change_requests_table', 20),
(21, '2026_07_01_000020_create_product_requests_table', 21),
(22, '2026_07_01_000021_create_exchange_requests_table', 22),
(23, '2026_07_01_000022_create_notifications_table', 23),
(24, '2026_07_01_000023_create_user_verifications_table', 24),
(25, '2026_07_01_000024_create_saved_cards_table', 25),
(26, '2026_07_01_000025_create_bank_accounts_table', 26),
(27, '2026_07_01_000026_create_reviews_table', 27),
(28, '2026_07_01_000027_create_faqs_table', 28),
(29, '2026_07_01_000028_create_terms_and_conditions_table', 29),
(30, '2026_07_01_000029_create_campaigns_table', 30),
(31, '2026_07_01_000030_create_wishlists_table', 31),
(32, '0001_01_01_000001_create_cache_table', 32),
(33, '2026_07_01_000032_create_product_installment_plan_table', 33),
(34, '2026_07_01_000018_create_insurance_settings_table', 34),
(35, '2026_07_26_000001_update_verification_status_to_approved', 35),
(36, '2026_07_26_000002_create_promo_codes_table', 36),
(37, '2026_07_26_000003_add_promo_to_orders_table', 37);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `installment_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `base_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `insurance_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `interest_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(255) NOT NULL DEFAULT 'full',
  `has_insurance` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_status` varchar(255) NOT NULL DEFAULT 'pending',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `delivery_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `cancellation_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `promo_code_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `installment_plan_id`, `status`, `total_amount`, `base_amount`, `shipping_fee`, `insurance_fee`, `interest_amount`, `discount_amount`, `grand_total`, `paid_amount`, `remaining_amount`, `payment_type`, `has_insurance`, `delivery_status`, `delivered_at`, `delivery_address_id`, `cancellation_reason`, `cancellation_fee`, `notes`, `created_at`, `updated_at`, `deleted_at`, `promo_code_id`) VALUES
(1, 'ORD-PUATMXXCMV', 1, NULL, 'completed', 200.00, 200.00, 5000.00, 0.00, 0.00, 0.00, 5200.00, 0.00, 5200.00, 'full', 0, 'pending', NULL, 1, NULL, 0.00, NULL, '2026-07-22 05:49:44', '2026-07-25 08:55:11', NULL, NULL),
(2, 'ORD-22GWWSOP55', 1, NULL, 'cancelled', 400.00, 400.00, 5000.00, 0.00, 0.00, 0.00, 5400.00, 0.00, 5400.00, 'full', 0, 'pending', NULL, 1, NULL, 540.00, NULL, '2026-07-25 10:47:06', '2026-07-26 08:39:02', NULL, NULL),
(3, 'ORD-JNTBMSE9BM', 1, 16, 'pending', 400.00, 400.00, 5000.00, 40.00, 48.00, 0.00, 5488.00, 0.00, 5488.00, 'installment', 1, 'pending', NULL, 1, NULL, 0.00, NULL, '2026-07-26 08:30:48', '2026-07-26 08:30:48', NULL, NULL),
(4, 'ORD-KFQCBDJURJ', 1, NULL, 'processing', 200.00, 200.00, 5000.00, 20.00, 0.00, 0.00, 5220.00, 0.00, 5220.00, 'full', 1, 'pending', NULL, 1, NULL, 0.00, NULL, '2026-07-26 11:20:23', '2026-07-26 11:20:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Dark Algo 5', 200.00, 1, 200.00, '2026-07-22 05:49:44', '2026-07-22 05:49:44'),
(2, 2, 1, 'Dark Algo 5', 200.00, 2, 400.00, '2026-07-25 10:47:06', '2026-07-25 10:47:06'),
(3, 3, 1, 'Dark Algo 5', 200.00, 2, 400.00, '2026-07-26 08:30:48', '2026-07-26 08:30:48'),
(4, 4, 1, 'Dark Algo 5', 200.00, 1, 200.00, '2026-07-26 11:20:23', '2026-07-26 11:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `installment_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_reference` varchar(255) NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `gateway_reference` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'NGN',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `type` varchar(255) NOT NULL DEFAULT 'payment',
  `gateway_response` text DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `user_id`, `order_id`, `installment_payment_id`, `transaction_reference`, `gateway`, `gateway_reference`, `amount`, `fee`, `currency`, `status`, `type`, `gateway_response`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'TXN-WNTKBYCEND33MYY', 'paystack', NULL, 5200.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-22 05:52:25', '2026-07-22 05:52:25'),
(2, 1, 1, NULL, 'FULL-BPA2Z0JU1CMN', 'paystack', NULL, 5200.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-22 05:52:44', '2026-07-22 05:52:44'),
(3, 1, 1, NULL, 'TXN-UCFCGXSFUW1S9SW', 'paystack', NULL, 5200.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-22 05:52:47', '2026-07-22 05:52:47'),
(4, 1, 2, NULL, 'TXN-JGO8TKUQCQJDHHD', 'paystack', NULL, 5400.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-25 10:47:06', '2026-07-25 10:47:06'),
(5, 1, 3, NULL, 'TXN-U39A8ZH20F4BGGA', 'paystack', NULL, 5488.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-26 08:30:52', '2026-07-26 08:30:52'),
(6, 1, 3, NULL, 'FULL-LFRBDDJIY1PW', 'paystack', NULL, 5488.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-26 08:34:00', '2026-07-26 08:34:00'),
(7, 1, NULL, NULL, 'WAL-TGWCWWJSIUQA', 'paystack', NULL, 5000.00, 0.00, 'NGN', 'pending', 'wallet_funding', NULL, NULL, '2026-07-26 11:12:54', '2026-07-26 11:12:54'),
(8, 1, NULL, NULL, 'WAL-2KIXC2SO2WHJ', 'paystack', NULL, 1000.00, 0.00, 'NGN', 'pending', 'wallet_funding', NULL, NULL, '2026-07-26 11:13:05', '2026-07-26 11:13:05'),
(9, 1, 4, NULL, 'TXN-WSV9NSL5HYLTAGH', 'flutterwave', NULL, 5220.00, 0.00, 'NGN', 'pending', 'payment', NULL, NULL, '2026-07-26 11:20:23', '2026-07-26 11:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `plan_change_requests`
--

CREATE TABLE `plan_change_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `current_plan_id` bigint(20) UNSIGNED NOT NULL,
  `requested_plan_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `base_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `insurance_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `interest_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `specifications` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `category_id`, `brand_id`, `supplier_id`, `price`, `base_price`, `shipping_fee`, `insurance_fee`, `interest_rate`, `stock_quantity`, `sku`, `thumbnail`, `status`, `featured`, `sort_order`, `specifications`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dark Algo 5', 'dark-algo-5', 'try6trb fhgtrghy', NULL, 1, 1, 1, 200.00, 250.00, 10.00, 200.00, 5.00, 10, NULL, NULL, 'active', 0, 0, NULL, '2026-07-22 05:33:54', '2026-07-22 05:36:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_fees`
--

CREATE TABLE `product_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) NOT NULL DEFAULT 'fixed',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_fees`
--

INSERT INTO `product_fees` (`id`, `name`, `slug`, `amount`, `type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Delivery Fee', 'delivery_fee', 120.00, 'fixed', 'Standard delivery fee', 1, '2026-07-22 05:25:13', '2026-07-26 11:23:28'),
(2, 'Insurance Fee', 'insurance_fee', 10.00, 'percentage', '10% insurance on total order', 1, '2026-07-22 05:25:13', '2026-07-22 05:25:13'),
(3, 'Default Charge', 'default_charge', 2000.00, 'fixed', 'Default processing charge', 1, '2026-07-22 05:25:13', '2026-07-22 05:25:13'),
(4, 'Retrieval Fee', 'retrieval_fee', 3000.00, 'fixed', 'Product retrieval fee for cancellations', 1, '2026-07-22 05:25:13', '2026-07-22 05:25:13'),
(5, 'Cancellation Fee', 'cancellation_fee', 10.00, 'percentage', '10% cancellation charge', 1, '2026-07-22 05:25:13', '2026-07-22 05:25:13');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/images/3rw8rPw8mdE9D6v1Xz6tPfqFHKClVVYo41EwA7hS.png', 1, 0, '2026-07-22 05:33:54', '2026-07-22 05:33:54'),
(2, 1, 'products/images/Dx334xOk0fjoqZQgxS0VkNNfikrWaPk5FogUBky2.png', 0, 1, '2026-07-22 05:36:07', '2026-07-22 05:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_installment_plan`
--

CREATE TABLE `product_installment_plan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `installment_plan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_requests`
--

CREATE TABLE `product_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expected_price` decimal(15,2) DEFAULT NULL,
  `brand_preference` varchar(255) DEFAULT NULL,
  `category_preference` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(15,2) NOT NULL,
  `min_order_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_uses` int(10) UNSIGNED DEFAULT NULL,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super_admin', NULL, '2026-07-22 05:25:01', '2026-07-22 05:25:01'),
(2, 'Admin', 'admin', NULL, '2026-07-22 05:25:01', '2026-07-22 05:25:01'),
(3, 'User', 'user', NULL, '2026-07-22 05:25:01', '2026-07-22 05:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_cards`
--

CREATE TABLE `saved_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `card_number_last4` varchar(255) NOT NULL,
  `card_holder_name` varchar(255) NOT NULL,
  `expiry_month` varchar(255) NOT NULL,
  `expiry_year` varchar(255) NOT NULL,
  `card_brand` varchar(255) NOT NULL,
  `gateway_reference` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5JfFl1Bb7NyOAr7ZlqkypzPcg5Ynod26rQAcntvO', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWtMN1Y2V091d0ZYVDVhN3NLVkJiVVA0STkwVXY5S202WjdReW1ldyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMToiaHR0cDovL2xvY2FsaG9zdC9hZG1pbi9vcmRlcnMvNCI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ub3JkZXJzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1785094822),
('PVZD3U7US6TefhOa0FI1hfXMc55s44D5cfiOZoAu', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVE5qYUpWOXE3bEluR0d2ZlVWMnluMlFzUjBBTTBMSHRuUk1ZNE9hbCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxNjoiaHR0cDovL2xvY2FsaG9zdCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1785162259);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `store_name` varchar(255) NOT NULL DEFAULT 'OwnPace',
  `store_description` text DEFAULT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'NGN',
  `currency_symbol` varchar(255) NOT NULL DEFAULT '₦',
  `default_interest_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `cancellation_fee_percentage` decimal(5,2) NOT NULL DEFAULT 10.00,
  `delivery_threshold_percentage` decimal(5,2) NOT NULL DEFAULT 70.00,
  `insurance_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `primary_color` varchar(255) NOT NULL DEFAULT '#2563EB',
  `accent_color` varchar(255) NOT NULL DEFAULT '#22C55E',
  `logo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `timezone` varchar(255) NOT NULL DEFAULT 'Africa/Lagos',
  `default_gateway` varchar(255) DEFAULT NULL,
  `gateway_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_config`)),
  `smtp_settings` text DEFAULT NULL,
  `sms_settings` text DEFAULT NULL,
  `registration_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `guest_checkout` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slider1` varchar(255) DEFAULT NULL,
  `slider2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `api_endpoint` varchar(255) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `api_secret` varchar(255) DEFAULT NULL,
  `meta_data` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `address`, `api_endpoint`, `api_key`, `api_secret`, `meta_data`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ertertret', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-22 05:31:42', '2026-07-22 05:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_conditions`
--

CREATE TABLE `terms_and_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_and_conditions`
--

INSERT INTO `terms_and_conditions` (`id`, `title`, `content`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'General Terms & Conditions', 'Welcome to OwnPace. By using our platform, you agree to these terms...', 'general', 1, '2026-07-22 05:25:18', '2026-07-22 05:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `nid_number` varchar(255) DEFAULT NULL,
  `nid_image` text DEFAULT NULL,
  `identity_verification` varchar(255) NOT NULL DEFAULT 'unverified',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 3,
  `payment_card_verification` varchar(255) NOT NULL DEFAULT 'unverified',
  `bank_account_verification` varchar(255) NOT NULL DEFAULT 'unverified',
  `delivery_address_verification` varchar(255) NOT NULL DEFAULT 'unverified',
  `store_terms_acceptance` varchar(255) NOT NULL DEFAULT 'unverified',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `nid_number`, `nid_image`, `identity_verification`, `email_verified_at`, `is_admin`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `payment_card_verification`, `bank_account_verification`, `delivery_address_verification`, `store_terms_acceptance`, `is_active`, `is_suspended`, `suspended_at`, `deleted_at`) VALUES
(1, 'Joty Prokash Biswas', 'jotybiswas0199@gmail.com', '+8801403107510', NULL, NULL, NULL, 'approved', NULL, 1, '$2y$12$29SMCPFlFRZCJCIlzsN/k.9X9al50balOv7tCBiZpNnb89v0vZnea', 'coh7efUQTky66Z4ynp94Kb6xsFaXp4xvvf3xRYGF1uLAEOwI1m4ygH1fnqQw', '2026-07-22 05:28:17', '2026-07-26 10:00:21', 3, 'unverified', 'unverified', 'unverified', 'unverified', 1, 0, '2026-07-26 08:47:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `document_path` text DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_verifications`
--

INSERT INTO `user_verifications` (`id`, `user_id`, `type`, `document_path`, `document_number`, `status`, `rejection_reason`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'identity_card', 'verifications/NTkpu869lNQXKMf28Smoq8TjZkLy7CpE1PfaYMpF.png', '7817130938', 'approved', NULL, '2026-07-26 10:00:21', '2026-07-26 09:59:49', '2026-07-26 10:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_deposited` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_withdrawn` decimal(15,2) NOT NULL DEFAULT 0.00,
  `cashback_earned` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `total_deposited`, `total_withdrawn`, `cashback_earned`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 0.00, 0.00, 0.00, 'active', '2026-07-22 05:39:10', '2026-07-22 05:39:10');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_before` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(15,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_logs`
--
ALTER TABLE `campaign_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_logs_campaign_id_foreign` (`campaign_id`),
  ADD KEY `campaign_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_addresses_user_id_index` (`user_id`);

--
-- Indexes for table `delivery_trackings`
--
ALTER TABLE `delivery_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_trackings_order_id_foreign` (`order_id`);

--
-- Indexes for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exchange_requests_user_id_foreign` (`user_id`),
  ADD KEY `exchange_requests_order_id_foreign` (`order_id`),
  ADD KEY `exchange_requests_current_product_id_foreign` (`current_product_id`),
  ADD KEY `exchange_requests_requested_product_id_foreign` (`requested_product_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installment_payments`
--
ALTER TABLE `installment_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installment_payments_order_id_installment_number_index` (`order_id`,`installment_number`);

--
-- Indexes for table `installment_plans`
--
ALTER TABLE `installment_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insurance_settings`
--
ALTER TABLE `insurance_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_installment_plan_id_foreign` (`installment_plan_id`),
  ADD KEY `orders_promo_code_id_foreign` (`promo_code_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_transactions_transaction_reference_unique` (`transaction_reference`),
  ADD KEY `payment_transactions_user_id_foreign` (`user_id`),
  ADD KEY `payment_transactions_order_id_foreign` (`order_id`),
  ADD KEY `payment_transactions_installment_payment_id_foreign` (`installment_payment_id`);

--
-- Indexes for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_change_requests_user_id_foreign` (`user_id`),
  ADD KEY `plan_change_requests_order_id_foreign` (`order_id`),
  ADD KEY `plan_change_requests_current_plan_id_foreign` (`current_plan_id`),
  ADD KEY `plan_change_requests_requested_plan_id_foreign` (`requested_plan_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_fees`
--
ALTER TABLE `product_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_fees_slug_unique` (`slug`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_installment_plan`
--
ALTER TABLE `product_installment_plan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_installment_plan_product_id_installment_plan_id_unique` (`product_id`,`installment_plan_id`),
  ADD KEY `product_installment_plan_installment_plan_id_foreign` (`installment_plan_id`);

--
-- Indexes for table `product_requests`
--
ALTER TABLE `product_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_codes_code_unique` (`code`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_order_id_reviewable_id_reviewable_type_unique` (`user_id`,`order_id`,`reviewable_id`,`reviewable_type`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_id_permission_unique` (`role_id`,`permission`);

--
-- Indexes for table `saved_cards`
--
ALTER TABLE `saved_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saved_cards_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_verifications_user_id_type_unique` (`user_id`,`type`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_user_id_unique` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  ADD KEY `wallet_transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_logs`
--
ALTER TABLE `campaign_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_trackings`
--
ALTER TABLE `delivery_trackings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `installment_payments`
--
ALTER TABLE `installment_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `installment_plans`
--
ALTER TABLE `installment_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `insurance_settings`
--
ALTER TABLE `insurance_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_fees`
--
ALTER TABLE `product_fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_installment_plan`
--
ALTER TABLE `product_installment_plan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_requests`
--
ALTER TABLE `product_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_cards`
--
ALTER TABLE `saved_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaign_logs`
--
ALTER TABLE `campaign_logs`
  ADD CONSTRAINT `campaign_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `delivery_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_trackings`
--
ALTER TABLE `delivery_trackings`
  ADD CONSTRAINT `delivery_trackings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD CONSTRAINT `exchange_requests_current_product_id_foreign` FOREIGN KEY (`current_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exchange_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exchange_requests_requested_product_id_foreign` FOREIGN KEY (`requested_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exchange_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `installment_payments`
--
ALTER TABLE `installment_payments`
  ADD CONSTRAINT `installment_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_installment_plan_id_foreign` FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_promo_code_id_foreign` FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_installment_payment_id_foreign` FOREIGN KEY (`installment_payment_id`) REFERENCES `installment_payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_change_requests`
--
ALTER TABLE `plan_change_requests`
  ADD CONSTRAINT `plan_change_requests_current_plan_id_foreign` FOREIGN KEY (`current_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_change_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_change_requests_requested_plan_id_foreign` FOREIGN KEY (`requested_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_change_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_installment_plan`
--
ALTER TABLE `product_installment_plan`
  ADD CONSTRAINT `product_installment_plan_installment_plan_id_foreign` FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_installment_plan_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_requests`
--
ALTER TABLE `product_requests`
  ADD CONSTRAINT `product_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_cards`
--
ALTER TABLE `saved_cards`
  ADD CONSTRAINT `saved_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD CONSTRAINT `user_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
