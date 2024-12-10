-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2024 at 12:11 PM
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
-- Database: `blog_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent`, `created`, `updated`) VALUES
(1, 'category1', NULL, '2024-12-09 19:42:12', NULL),
(2, 'category2', NULL, '2024-12-09 20:19:08', NULL),
(3, 'category3', NULL, '2024-12-09 20:19:13', NULL),
(5, 'Technologyhh', 2, '2024-12-09 23:05:22', '2024-12-10 02:31:02');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `published` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `comment_id`, `user_id`, `comment`, `status`, `published`, `created`) VALUES
(2, 3, 0, 6, 'Praesent aliquam ex vel lectus ornare tritique. Nunc et eros quis enim feugiat tincidunt et vitae dui. Nullam consectetur justo ac ex laoreet rhoncus. Nunc id leo pretium, faucibus sapien vel, euismod turpis.', 1, '2024-12-10', '2024-12-10 12:58:31'),
(3, 3, 0, 7, 'incidunt et vitae dui. Nullam consectetur justo ac ex laoreet rhoncus. Nunc id leo pretium, faucibus sapien vel, euismod turpis.', 0, NULL, '2024-12-10 14:22:29');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `published` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `description`, `image`, `status`, `published`, `created`, `updated`) VALUES
(1, 1, 5, 'Simple and useful HTML layout', 'There is a clickable image with beautiful hover effect and active title link for each post item. Left side is a sticky menu bar. Right side is a blog content that will scroll up and down.\r\n\r\n', '67574522e2a50_example.jpg', 1, '2024-12-09', '2024-12-10 00:59:38', '2024-12-10 04:48:25'),
(2, 1, 1, 'Multi-purpose blog template', 'Xtra Blog is a multi-purpose HTML CSS template from TemplateMo website. Blog list, single post, about, contact pages are included. Left sidebar fixed width and content area is a fluid full-width.', '67574bf9f2930_img-02.jpg', 1, '2024-12-09', '2024-12-10 01:28:50', '2024-12-10 11:39:56'),
(3, 1, 2, 'How can you apply Xtra Blog', 'You are allowed to convert this template as any kind of CMS theme or template for your custom website builder. You can also use this for your clients. Thank you for choosing us.', '67574c8ff2d82_img-03.jpg', 1, '2024-12-09', '2024-12-10 01:31:19', '2024-12-10 11:40:16'),
(4, 1, 3, 'A little restriction to apply', 'You are not allowed to re-distribute this template as a downloadable ZIP file on any template collection website. This is strongly prohibited as we worked hard for this template. Please contact TemplateMo for more information.', '67574cb3116c8_img-04.jpg', 1, '2024-12-09', '2024-12-10 01:31:55', '2024-12-10 11:40:20'),
(5, 1, 1, 'Color hexa values of Xtra Blog', 'If you wish to kindly support us, please contact us or contribute a small PayPal amount to info [at] templatemo.com that is helpful for us.\r\nTitle #099 New #0CC', '67574cd29b901_img-05.jpg', 1, '2024-12-10', '2024-12-10 01:32:26', '2024-12-10 11:40:24'),
(6, 1, 2, 'Donec convallis varius risus', 'Quisque id ipsum vel sem maximus vulputate sed quis velit. Nunc vel turpis eget orci elementum cursus vitae in eros. Quisque vulputate nulla ut dolor consectetur luctus.', '675777a889ed7_img-06.jpg', 1, '2024-12-10', '2024-12-10 01:33:04', '2024-12-10 04:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1 = Adminstrator, 2 = Moderation, 3 = Subscriber',
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `forgot_pass` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `forgot_pass`, `status`, `created`, `updated`) VALUES
(1, 1, 'Hardik Chavdadd', 'hardik.chavda123@gmail.com', '$2y$10$CWJ4LlVdzAs8UWLTQASgq.IX3tq8lF/L7KyHJUXrpPk8K.lIft1q6', '', 1, '2024-12-09 16:22:05', '2024-12-10 15:44:45'),
(2, 2, 'Hardik Test', 'hardik@example.com', '$2y$10$MeLt9UfIoi0IscFr8jkxQ.9TPyuqG34.SFeO5EQ7QY6cFgDkGXUDK', NULL, 1, '2024-12-09 21:51:23', '2024-12-10 03:23:17'),
(6, 3, 'Mark Sonny', 'marksonny@example.com', '$2y$10$48TgyFt2p5SFixu7dcJpG.t7cw3BAIz0s/rInnMbUyKukjyrfBkka', NULL, 1, '2024-12-10 12:54:27', NULL),
(7, 3, 'Admin', 'admin@example.com', '$2y$10$6c410Mc.yoUu6OCgg/k68uij0gCCb54ycrZ5khm3epuM4MMQBytBS', NULL, 0, '2024-12-10 14:22:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
