-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 08:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stmitrust2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_page_content`
--

CREATE TABLE `about_page_content` (
  `id` int(11) NOT NULL DEFAULT 1,
  `who_we_are_title` varchar(255) DEFAULT 'Soka Toto Muda Initiative Trust',
  `who_we_are_content` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `page_header_title` varchar(255) DEFAULT 'About Us',
  `page_header_subtitle` text DEFAULT NULL,
  `header_bg_image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `who_we_are_image` varchar(500) DEFAULT NULL,
  `core_values_image` varchar(500) DEFAULT NULL,
  `our_programs_image` varchar(500) DEFAULT NULL,
  `history_image` varchar(500) DEFAULT NULL,
  `mission_vision_image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_page_content`
--

INSERT INTO `about_page_content` (`id`, `who_we_are_title`, `who_we_are_content`, `mission`, `vision`, `page_header_title`, `page_header_subtitle`, `header_bg_image`, `status`, `created_at`, `updated_at`, `who_we_are_image`, `core_values_image`, `our_programs_image`, `history_image`, `mission_vision_image`) VALUES
(1, 'Soka Toto Muda Initiative Trust', 'We are a Christian founded, non-profit making organization. We reach out to vulnerable and talented children through Sports (SOKA TOTO), Creative Arts (MUDA), Mentorship, Discipleship and Outreaches, Life skills, empowerment and psycho-Social Support to Young Mothers.\r\n\r\nWe believe that by touching the heart of a child, we have impacted the community at large. Moreover, by supporting the young mothers, their children will have wide range of opportunities when they grow up because of empowerment.\r\n\r\nWe therefore ensure that no child is left out/behind by exposing them to various activities which suitably fits their abilities. This will enable them become more disciplined, God fearing, cultivate critical thinking, problem solving and finally be holistically self reliant citizens in the society.', 'To holistically transform our children through talent exploration so that they are excellent, independent decision-makers and resourceful people in society.', 'To empower children with opportunities to explore their talents, receive support with dignity and grow into confident, independent individuals.', 'About Us today', 'Discover who we are, what we stand for, and how we\'re making a difference in the lives of children and young mothers.', 'images/history/1777024873_header.jpg', 'active', '2026-04-22 19:03:26', '2026-04-24 13:13:57', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`id`, `admin_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 06:22:09'),
(2, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 06:31:10'),
(3, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 06:32:19'),
(4, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 06:42:50'),
(5, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 07:29:53'),
(6, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 08:21:19'),
(7, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 09:21:20'),
(8, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 14:34:09'),
(9, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 14:36:26'),
(10, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 14:50:37'),
(11, 1, 'login', 'Admin logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 15:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('super_admin','admin','editor','viewer') DEFAULT 'editor',
  `profile_image` varchar(500) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `profile_image`, `last_login`, `last_ip`, `status`, `created_at`, `updated_at`) VALUES
(2, 'admin', '$2y$10$cM1wm8Ztfn4Gq0vucsM10umxFUoyy1OkFG2JTQQE7RM3KZHYOKesa', 'admin@stmitrust.org', 'System Administrator', 'super_admin', NULL, '2026-04-24 18:29:20', '::1', 'active', '2026-04-24 15:21:11', '2026-04-24 15:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `description`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'General', 'general', 'General blog posts', 1, 'active', '2026-04-22 18:20:59', NULL),
(2, 'News', 'news', 'Latest news and updates', 2, 'active', '2026-04-22 18:20:59', NULL),
(3, 'Events', 'events', 'Event announcements and recaps', 3, 'active', '2026-04-22 18:20:59', NULL),
(4, 'Success Stories', 'success-stories', 'Impact stories from our work', 4, 'active', '2026-04-22 18:20:59', NULL),
(5, 'Impact 1', 'impact-1', 'Organisational impact stories', 0, 'active', '2026-04-22 18:33:59', '2026-04-24 14:38:25');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved','spam') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_page_settings`
--

CREATE TABLE `blog_page_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `header_title` varchar(255) DEFAULT 'Our Blog',
  `header_subtitle` text DEFAULT NULL,
  `header_image` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_page_settings`
--

INSERT INTO `blog_page_settings` (`id`, `header_title`, `header_subtitle`, `header_image`, `updated_at`) VALUES
(1, 'Our Blog', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `tags` varchar(500) DEFAULT NULL,
  `author` varchar(255) DEFAULT 'STMI Team',
  `author_email` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'draft',
  `featured` tinyint(1) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `header_image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `category`, `tags`, `author`, `author_email`, `views`, `status`, `featured`, `published_at`, `created_at`, `updated_at`, `meta_description`, `meta_keywords`, `header_image`) VALUES
(1, 'New school opens in rural region', 'new-school-opens-in-rural-region', 'The facility includes 6 classrooms, a library, a playground, and clean water access.</p><p>This is a significant milestone in our mission to ensure every child has access to quality education. We invite you to join us in celebrating this achievement and to continue supporting our work.</p>', 'With community support,++++++++++++++++++++++++we inaugurated a primary school serving 300 children in the rural areas of Machakos County.', 'images/blog/1777029459_69eb51530437b.jpg', 'News', NULL, 'STMI Team', NULL, 30, 'published', 1, '2026-04-21 20:27:49', '2026-04-21 17:27:49', '2026-04-24 14:13:59', '', '', 'images/blog/1777032044_header_69eb5b6c479a7.png'),
(8, 'shfjgkhljk', 'shfjgkhljk', 'retrytuyihojl;kl', 'tyrftugiyoupio', 'images/blog/1777032114_69eb5bb252f67.jpeg', 'Events', NULL, 'stdfygjhkjlk', NULL, 4, 'published', 1, '2026-04-24 15:01:54', '2026-04-24 12:01:54', '2026-04-24 14:06:16', '', '', 'images/blog/1777033669_header_69eb61c5002b7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_values`
--

CREATE TABLE `core_values` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT 'fas fa-heart',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `core_values`
--

INSERT INTO `core_values` (`id`, `title`, `description`, `icon`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Compassion', 'We approach every child and family with genuine care, empathy, and understanding, recognizing their unique circumstances and needs.', 'fas fa-heart', 1, 'active', '2026-04-22 19:03:26', NULL),
(2, 'Dignity', 'We believe in treating every individual with respect and honor, empowering them to make choices and maintain their self-worth.', 'fas fa-hand-holding-heart', 2, 'active', '2026-04-22 19:03:26', NULL),
(4, 'Collaboration', 'We work alongside families, communities, and partners to create sustainable solutions and lasting change.', 'fas fa-hands-helping', 4, 'active', '2026-04-22 19:03:26', NULL),
(5, 'Sustainability', 'We build programs that are environmentally, socially, and economically sustainable for long-term community benefit.', 'fas fa-tree', 5, 'active', '2026-04-22 19:03:26', NULL),
(9, 'Excellence', 'We strive for the highest quality in our programs.', 'fas fa-star', 3, 'active', '2026-04-24 12:56:18', NULL),
(12, 'Empowerment', 'We equip children and young mothers with tools and skills.', 'fas fa-fist-raised', 6, 'active', '2026-04-24 12:56:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `event_type` enum('upcoming','ongoing','past') DEFAULT 'upcoming',
  `registration_link` varchar(500) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_date`, `end_date`, `start_time`, `end_time`, `location`, `venue`, `event_type`, `registration_link`, `contact_email`, `contact_phone`, `image_path`, `featured`, `status`, `created_at`, `updated_at`) VALUES
(2, 'sports', 'just do it', '2026-04-23', '2026-04-30', '08:00:00', '16:00:00', 'Kabiria, Nairobi', 'alpha glory', 'upcoming', 'https://www.shofco.org/get-involved/', '', '', 'images/events/1776878407_event_69e9034768258.png', 1, 'active', '2026-04-22 20:04:40', '2026-04-22 20:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `year` varchar(10) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homepage_settings`
--

CREATE TABLE `homepage_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `know_us_image` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_settings`
--

INSERT INTO `homepage_settings` (`id`, `know_us_image`, `updated_at`) VALUES
(1, 'images/homepage/1776885459_knowus.jpeg', '2026-04-22 22:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `impact_stats`
--

CREATE TABLE `impact_stats` (
  `id` int(11) NOT NULL,
  `impacted_lives` int(11) DEFAULT 0,
  `year` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `impact_stats`
--

INSERT INTO `impact_stats` (`id`, `impacted_lives`, `year`) VALUES
(1, 50000, '2025');

-- --------------------------------------------------------

--
-- Table structure for table `moments_images`
--

CREATE TABLE `moments_images` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moments_videos`
--

CREATE TABLE `moments_videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `duration` int(11) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moments_videos`
--

INSERT INTO `moments_videos` (`id`, `title`, `description`, `file_path`, `duration`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'stmi explainer', 'Just play it', 'videos/gallery/1776869800_69e8e1a8e31af.mp4', 171, 0, 'active', '2026-04-22 17:56:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organisational_history`
--

CREATE TABLE `organisational_history` (
  `id` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL DEFAULT 'Our History',
  `content` longtext DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `core_values` text DEFAULT NULL,
  `founded_year` int(11) DEFAULT 2020,
  `founded_location` varchar(255) DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisational_history`
--

INSERT INTO `organisational_history` (`id`, `title`, `content`, `mission`, `vision`, `core_values`, `founded_year`, `founded_location`, `registration_number`, `featured_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Our History', NULL, NULL, NULL, NULL, 2020, NULL, NULL, NULL, 'active', '2026-04-22 18:46:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organisation_founder`
--

CREATE TABLE `organisation_founder` (
  `id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `quote` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisation_founder`
--

INSERT INTO `organisation_founder` (`id`, `name`, `title`, `quote`, `bio`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Emmanuel Kiptoo cheres', 'Founder & Executive Director', 'You’re receiving this email because you filled out the following form using your email address. This form is owned by St Josephs Technical Training Institute for the Deaf, Nyangoma. Make sure you recognize and trust this form before copying or clicking on any links. If it looks suspicious, report it.', '', 'images/history/1777023535_founder.jpeg', 'active', '2026-04-22 19:05:46', '2026-04-24 13:14:12');

-- --------------------------------------------------------

--
-- Table structure for table `organisation_history`
--

CREATE TABLE `organisation_history` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisation_history`
--

INSERT INTO `organisation_history` (`id`, `year`, `title`, `description`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 2018, 'Foundation & First Steps', 'Soka Toto Muda Initiative Trust was founded by a group of passionate community members who recognized the urgent need to support vulnerable children in their locality. The first programs started with just 25 children in a small community hall.', 1, 'active', '2026-04-22 19:03:26', NULL),
(2, 2020, 'Expansion & Recognition', 'Despite the challenges of the pandemic, we expanded our reach to over 200 children. We were officially registered as a Trust and began receiving support from local partners and donors.', 2, 'active', '2026-04-22 19:03:26', NULL),
(3, 2022, 'Young Mothers Program Launch', 'Recognizing the unique struggles of teen mothers, we launched a dedicated empowerment program offering psychosocial support, skills training, and childcare assistance.', 3, 'active', '2026-04-22 19:03:26', NULL),
(5, 2026, 'Looking Ahead', 'Today, we serve over 1,000 children and young mothers annually. Our vision is to expand to more communities and deepen our impact through innovative programs and sustainable solutions.', 5, 'active', '2026-04-22 19:03:26', '2026-04-24 13:11:16'),
(9, 2024, 'New Facilities & Partnerships', 'Opened our first dedicated center with sports facilities and art studios.', 4, 'active', '2026-04-24 12:56:18', NULL),
(10, 2026, 'Looking Ahead 222', 'You’re receiving this email because you filled out the following form using your email address. This form is owned by St Josephs Technical Training Institute for the Deaf, Nyangoma. Make sure you recognize and trust this form before copying or clicking on any links. If it looks suspicious, report it.', 0, 'active', '2026-04-24 13:12:11', NULL),
(11, 2024, 'Looking Ahead', 'You’re receiving this email because you filled out the following form using your email address. This form is owned by St Josephs Technical Training Institute for the Deaf, Nyangoma. Make sure you recognize and trust this form before copying or clicking on any links. If it looks suspicious, report it.', 0, 'active', '2026-04-24 13:28:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organisation_leaders`
--

CREATE TABLE `organisation_leaders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_milestones`
--

CREATE TABLE `organisation_milestones` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo_path` varchar(500) DEFAULT NULL,
  `website_url` varchar(500) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo_path`, `website_url`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Global Aid', 'images/partners/global-aid.png', NULL, 1, 'active', '2026-04-22 12:17:01', '2026-04-22 12:17:01'),
(6, 'Alpha Glory', 'images/partners/1776861110_1776859462_sk_logo.png', 'https://www.shofco.org/about-us/our-story/', 1, 'active', '2026-04-22 12:31:50', '2026-04-22 12:31:50'),
(7, 'Alpha Glory', 'images/partners/1776861170_1776861110_1776859462_sk_logo.png', 'https://www.shofco.org/about-us/our-story/', 2, 'active', '2026-04-22 12:32:50', '2026-04-22 12:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `partnership_inquiries`
--

CREATE TABLE `partnership_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `partnership_type` varchar(50) DEFAULT NULL,
  `partnership_level` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','contacted','converted','archived') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partnership_inquiries`
--

INSERT INTO `partnership_inquiries` (`id`, `name`, `organization`, `email`, `phone`, `partnership_type`, `partnership_level`, `message`, `status`, `created_at`) VALUES
(1, 'Emmanuel Kiptoo', 'ssss', 'emmanuel.kip954@gmail.com', '+254 702386080', 'faith', 'silver', 'ssssss', 'pending', '2026-04-22 12:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `short_description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `header_image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `title`, `description`, `short_description`, `features`, `image_path`, `category`, `display_order`, `views`, `status`, `created_at`, `updated_at`, `header_image`) VALUES
(1, 'Sports for Development', '<p>Our Sports for Development program uses football, athletics, and team sports to build confidence, teamwork, and life skills in children. We believe that sports are a powerful tool for teaching discipline, leadership, and resilience.</p><p>Through regular training sessions, tournaments, and mentorship, we help children discover their potential and stay active. The program also promotes gender equality by encouraging girls to participate in sports.</p><p>Many of our participants have gone on to represent their schools and communities in various competitions, building self-esteem and a sense of achievement.</p>', 'Using football, athletics, and team sports to build confidence, teamwork, and life skills.', 'Regular training sessions\r\nInter-community tournaments\r\nLeadership development\r\nGirls sports initiative\r\nHealth and nutrition education', 'images/programs/1776856839_sports.jpg', 'Sports', 1, 11, 'active', '2026-04-22 08:25:44', '2026-04-24 14:11:28', NULL),
(5, 'Outreach and Discipleship', 'Through engaging and age-appropriate approaches, we nurture spiritual growth in both younger and older children. For younger children, we use interactive Bible stories that capture their imagination through storytelling, songs, and simple activities, helping them understand foundational lessons such as kindness, obedience, honesty, and love. These sessions are designed to be lively and relatable, allowing children to connect biblical teachings to their everyday experiences.\r\n\r\nFor older children, we facilitate structured Bible study sessions that encourage deeper reflection, discussion, and personal application of scripture. They are guided to ask questions, share perspectives, and develop critical thinking around faith and life choices. Through this, they gain a clearer understanding of their identity, purpose, and values, while also building confidence in expressing their beliefs.\r\n\r\nTogether, these programs create a supportive environment where children grow not only in knowledge of the Bible but also in character, integrity, and a strong moral foundation that influences their decisions and interactions within their families and communities.', 'Through Bible stories for younger children and Bible study for older ones, we foster spiritual growth and a strong sense of moral values.', 'Spiritual Activities:\r\nChildren\'s Bible stories\r\nYouth Bible study groups\r\nSpiritual mentorship\r\nValues formation workshops\r\nCommunity outreach', 'images/programs/1776857053_discipleship.jpeg', 'Discipleship', 1, 10, 'active', '2026-04-22 11:24:13', '2026-04-24 10:42:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `subtitle`, `content`, `cover_image`, `file_path`, `report_date`, `year`, `category`, `display_order`, `views`, `status`, `created_at`, `updated_at`) VALUES
(3, '2026 Annual Report', 'Quarterly report', '024 was a remarkable year for SHOFCO. We celebrated our 20th anniversary while sharpening our focus on community-driven solutions. As Kennedy Odede notes in his opening letter, SHOFCO’s future is not about delivering services to people,', 'images/reports/1777036225_cover_69eb6bc1e8e40.jpeg', 'docs/reports/1777036067_report_69eb6b23ec423.pdf', '2026-04-24', 2026, 'Annual', 0, 11, 'active', '2026-04-24 16:07:47', '2026-04-24 17:05:33'),
(4, 'Trial', 'trial 1', 'report-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-headerreport-header', 'images/reports/1777039039_cover_69eb76bf9b417.jpeg', 'docs/reports/1777037967_report_69eb728fd2694.pdf', '2026-04-24', 2025, 'try try', 0, 4, 'active', '2026-04-24 16:39:27', '2026-04-24 16:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `reports_page_settings`
--

CREATE TABLE `reports_page_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `page_title` varchar(255) DEFAULT 'Reports',
  `page_subtitle` text DEFAULT NULL,
  `header_image` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports_page_settings`
--

INSERT INTO `reports_page_settings` (`id`, `page_title`, `page_subtitle`, `header_image`, `updated_at`) VALUES
(1, 'Reports', 'Access our latest reports, research findings, and impact assessments', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `site_name` varchar(255) DEFAULT 'STMI Trust',
  `site_tagline` varchar(255) DEFAULT 'Soka Toto Muda Initiative Trust',
  `site_email` varchar(255) DEFAULT 'info@stmitrust.org',
  `site_phone` varchar(50) DEFAULT '+254 700 000 000',
  `site_address` text DEFAULT NULL,
  `facebook_url` varchar(500) DEFAULT NULL,
  `twitter_url` varchar(500) DEFAULT NULL,
  `instagram_url` varchar(500) DEFAULT NULL,
  `linkedin_url` varchar(500) DEFAULT NULL,
  `youtube_url` varchar(500) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `favicon` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_tagline`, `site_email`, `site_phone`, `site_address`, `facebook_url`, `twitter_url`, `instagram_url`, `linkedin_url`, `youtube_url`, `footer_text`, `logo`, `favicon`, `updated_at`) VALUES
(1, 'STMI Trust', 'Soka Toto Muda Initiative Trust', 'info@stmitrust.org', '+254 700 000 000', '', '', '', '', '', '', '', 'images/settings/1777044611_logo.png', '', '2026-04-24 18:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category` enum('success_story','impact_story','testimonial','featured') DEFAULT 'success_story',
  `content` text NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `story_date` date DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `title`, `author`, `category`, `content`, `excerpt`, `image_path`, `video_url`, `story_date`, `display_order`, `featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'From Despair to Hope: Mary\'s Journey', 'Mary Wanjiku', 'success_story', 'Mary was a young mother who had lost all hope after dropping out of school. Through our Young Mothers Empowerment Program, she learned tailoring skills and started her own business. Today, she employs three other young mothers and is able to support her child. \"STMI Trust gave me a second chance at life. I never thought I could be this independent,\" she says.', 'Mary was a young mother who had lost all hope after dropping out of school. Through our program, she learned tailoring skills and started her own business.', NULL, NULL, '2025-10-15', 1, 1, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(2, 'John\'s Football Dream', 'John Otieno', 'impact_story', 'John joined our Sports for Development program at age 12. He was shy and lacked confidence. Through regular training and mentorship, he not only became the captain of his team but also improved his academic performance. \"Sports taught me discipline and teamwork. Now I believe I can achieve anything,\" John shares.', 'John joined our Sports for Development program at age 12. Through regular training and mentorship, he became the captain of his team.', NULL, NULL, '2025-11-20', 2, 1, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(3, 'Creative Arts Changing Lives', 'Peter Kimani', 'impact_story', 'The Creative Arts program has helped over 200 children express themselves through art, music, and drama. One of our participants, Sarah, discovered her talent in painting and has since won several competitions. Her artwork now sells locally, providing income for her family.', 'The Creative Arts program has helped over 200 children express themselves through art, music, and drama.', NULL, NULL, '2025-09-10', 3, 0, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(4, 'A Mother\'s Testimonial', 'Jane Mwangi', 'testimonial', '\"I am forever grateful to STMI Trust. When I was pregnant at 16, I thought my life was over. The psychosocial support and skills training I received gave me hope. Now I run a small catering business and my child is healthy and happy. Thank you, STMI Trust!\"', 'A heartfelt testimonial from a young mother who found hope through our programs.', NULL, NULL, '2025-12-01', 4, 1, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(5, 'James\' Success Story', 'James Kariuki', 'success_story', 'James was a street child who found refuge in our mentorship program. With guidance and support, he returned to school and is now pursuing a degree in social work. \"I want to give back to the community that saved me,\" he says.', 'James was a street child who found refuge in our mentorship program and is now pursuing a degree in social work.', NULL, NULL, '2025-08-05', 5, 0, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(6, 'Community Impact: Clean Water Initiative', 'STMI Team', 'impact_story', 'Our clean water initiative has provided safe drinking water to over 5,000 families in rural communities. The impact on child health and school attendance has been remarkable. Cases of waterborne diseases have reduced by 70%.', 'Our clean water initiative has provided safe drinking water to over 5,000 families in rural communities.', NULL, NULL, '2025-07-18', 6, 0, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56'),
(7, 'Volunteer Testimonial', 'Sarah Muthoni', 'testimonial', '\"Volunteering with STMI Trust has been the most rewarding experience of my life. Seeing the smiles on children\'s faces and witnessing their transformation is priceless. I encourage everyone to get involved.\"', 'A volunteer shares her experience working with STMI Trust.', NULL, NULL, '2025-06-22', 7, 0, 'active', '2026-04-21 15:54:56', '2026-04-21 15:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `department` enum('leadership','operations','volunteers','board') NOT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `social_facebook` varchar(255) DEFAULT NULL,
  `social_twitter` varchar(255) DEFAULT NULL,
  `social_linkedin` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `position`, `department`, `bio`, `email`, `phone`, `image_path`, `social_facebook`, `social_twitter`, `social_linkedin`, `social_instagram`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Emmanuel Kiptoo', 'ICT', 'operations', 'Just me', 'kiptooemmanuel954@gmail.com', '+254 702386080', 'images/teams/1776863750_team.jpeg', '', '', '', '', 1, 'active', '2026-04-22 13:15:50', '2026-04-22 13:15:50');

-- --------------------------------------------------------

--
-- Table structure for table `teams_page_settings`
--

CREATE TABLE `teams_page_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `header_title` varchar(255) DEFAULT 'Our Teams',
  `header_subtitle` text DEFAULT NULL,
  `header_image` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams_page_settings`
--

INSERT INTO `teams_page_settings` (`id`, `header_title`, `header_subtitle`, `header_image`, `updated_at`) VALUES
(1, 'Our Teams', 'Meet the dedicated individuals behind our mission to transform children\'s lives.', 'images/teams/1777028060_teams_header.jpg', '2026-04-24 13:54:20');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_applications`
--

CREATE TABLE `volunteer_applications` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `availability_days` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `motivation` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(50) DEFAULT NULL,
  `hear_about_us` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_applications`
--

INSERT INTO `volunteer_applications` (`id`, `full_name`, `email`, `phone`, `address`, `date_of_birth`, `gender`, `occupation`, `skills`, `interests`, `availability`, `availability_days`, `experience`, `motivation`, `emergency_contact_name`, `emergency_contact_phone`, `hear_about_us`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(10, 'emmanu', 'kiptooemmanuel954@gmail.com', '+254 702386080', '49940', '2024-02-15', 'male', 'yes', 'yes', 'yesssss', 'weekdays', 'mon - fri', 'ddddd', 'dddddd', 'ddddd', 'ddddd', 'social_media', 'accepted', NULL, '2026-04-22 23:19:31', '2026-04-22 23:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_opportunities`
--

CREATE TABLE `volunteer_opportunities` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `commitment` varchar(255) DEFAULT NULL,
  `slots_available` int(11) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_page_settings`
--

CREATE TABLE `volunteer_page_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `hero_title` varchar(255) DEFAULT 'Become a Volunteer',
  `hero_subtitle` text DEFAULT NULL,
  `hero_image` varchar(500) DEFAULT NULL,
  `why_volunteer_content` text DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT 'volunteer@stmitrust.org',
  `contact_phone` varchar(50) DEFAULT '+254 700 000 000',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_page_settings`
--

INSERT INTO `volunteer_page_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_image`, `why_volunteer_content`, `contact_email`, `contact_phone`, `updated_at`) VALUES
(1, 'Become a Volunteer', 'Join us in making a difference in the lives of children and young mothers. Your time and skills can transform communities.', NULL, NULL, 'volunteer@stmitrust.org', '+254 700 000 000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','file') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Soka Toto Muda Initiative Trust', 'text', 'Website title', '2026-04-22 06:16:52', '2026-04-22 06:16:52'),
(2, 'site_email', 'info@stmitrust.org', 'text', 'Contact email', '2026-04-22 06:16:52', '2026-04-22 06:16:52'),
(3, 'site_phone', '+254 704118683', 'text', 'Contact phone', '2026-04-22 06:16:52', '2026-04-22 06:16:52'),
(4, 'site_address', '105-00508 Nairobi, Kenya', 'text', 'Office address', '2026-04-22 06:16:52', '2026-04-22 06:16:52'),
(5, 'footer_text', '© 2024 Soka Toto Muda Initiative Trust. All rights reserved.', 'text', 'Footer copyright text', '2026-04-22 06:16:52', '2026-04-22 06:16:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_page_content`
--
ALTER TABLE `about_page_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `idx_username` (`username`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `blog_page_settings`
--
ALTER TABLE `blog_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_published_at` (`published_at`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_values`
--
ALTER TABLE `core_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage_settings`
--
ALTER TABLE `homepage_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `impact_stats`
--
ALTER TABLE `impact_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moments_images`
--
ALTER TABLE `moments_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moments_videos`
--
ALTER TABLE `moments_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `organisational_history`
--
ALTER TABLE `organisational_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_founder`
--
ALTER TABLE `organisation_founder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_history`
--
ALTER TABLE `organisation_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_leaders`
--
ALTER TABLE `organisation_leaders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_milestones`
--
ALTER TABLE `organisation_milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `partnership_inquiries`
--
ALTER TABLE `partnership_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports_page_settings`
--
ALTER TABLE `reports_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_department` (`department`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `teams_page_settings`
--
ALTER TABLE `teams_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer_page_settings`
--
ALTER TABLE `volunteer_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD UNIQUE KEY `idx_setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `core_values`
--
ALTER TABLE `core_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `impact_stats`
--
ALTER TABLE `impact_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `moments_images`
--
ALTER TABLE `moments_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moments_videos`
--
ALTER TABLE `moments_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organisation_history`
--
ALTER TABLE `organisation_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `organisation_leaders`
--
ALTER TABLE `organisation_leaders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organisation_milestones`
--
ALTER TABLE `organisation_milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `partnership_inquiries`
--
ALTER TABLE `partnership_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
