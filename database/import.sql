-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 22, 2022 at 11:26 PM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoisiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `ico` char(8) NOT NULL,
  `street` varchar(255) NOT NULL,
  `zip_code` char(5) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `name`, `email`, `ico`, `street`, `zip_code`, `town`, `is_deleted`) VALUES
(57, 'Online Factory s.r.o.', 'mail@mail.cz', '24273635', 'Rumunská 1798/1', '12000', 'Praha - Nové Město', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contractor`
--

CREATE TABLE `contractor` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) DEFAULT NULL,
  `ico` int(11) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `zip_code` char(5) NOT NULL,
  `town` varchar(255) NOT NULL,
  `is_superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `contractor`
--

INSERT INTO `contractor` (`id`, `first_name`, `last_name`, `email`, `password`, `ico`, `bank_account`, `street`, `zip_code`, `town`, `is_superadmin`, `is_deleted`) VALUES
(1, 'Woody', 'Cowboy', 'woody@email.cz', '$2y$10$PWicrBd3SL1RC47xhq/u0OmpyNzs9Eyf/yVFcYZUGuhUu0tnI.YUG', 12345, '123456/0600', 'Dlouhá Lhota 35', '29405', 'Dlouhá Lhota', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contractor_mn_permission`
--

CREATE TABLE `contractor_mn_permission` (
  `contractor_id` int(11) NOT NULL,
  `permission_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contractor_mn_permission`
--

INSERT INTO `contractor_mn_permission` (`contractor_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `issue_date` date NOT NULL,
  `var_symbol` int(11) DEFAULT NULL,
  `datetime_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item`
--

CREATE TABLE `invoice_item` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `charge_per_hour` int(11) NOT NULL,
  `hours_sum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permission_id` bigint(20) NOT NULL,
  `permission_parent_id` int(11) NOT NULL,
  `permission_system_name` varchar(255) NOT NULL,
  `permission_human_name` varchar(255) NOT NULL,
  `permission_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`permission_id`, `permission_parent_id`, `permission_system_name`, `permission_human_name`, `permission_order`) VALUES
(1, 2, 'contractors_list', 'List contractors', 1),
(2, 2, 'contractors_add', 'Add contractor', 2),
(3, 2, 'contractors_edit', 'Edit contractor', 3),
(4, 2, 'contractors_delete', 'Delete contractor', 5),
(5, 3, 'clients_list', 'List clients', 1),
(6, 3, 'clients_add', 'Add client', 2),
(7, 3, 'clients_detail', 'Client detail', 3),
(8, 3, 'clients_delete', 'Delete client', 4),
(9, 1, 'invoices_list', 'List invoices', 1),
(10, 1, 'invoices_edit', 'Edit invoice', 3),
(11, 2, 'set_permissions', 'Set permissions', 4),
(12, 1, 'invoices_add', 'Add invoice', 2),
(13, 1, 'invoices_delete', 'Delete invoice', 4);

-- --------------------------------------------------------

--
-- Table structure for table `permission_parent`
--

CREATE TABLE `permission_parent` (
  `permission_parent_id` int(11) NOT NULL,
  `permission_parent_system_name` varchar(255) NOT NULL,
  `permission_parent_human_name` varchar(255) NOT NULL,
  `permission_parent_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission_parent`
--

INSERT INTO `permission_parent` (`permission_parent_id`, `permission_parent_system_name`, `permission_parent_human_name`, `permission_parent_order`) VALUES
(1, 'invoices', 'Invoices', 3),
(2, 'contractors', 'Contractors', 1),
(3, 'clients', 'Clients', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contractor_mn_permission`
--
ALTER TABLE `contractor_mn_permission`
  ADD PRIMARY KEY (`contractor_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `user_id` (`contractor_id`);

--
-- Indexes for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `permission_system_name` (`permission_system_name`),
  ADD KEY `permission_parent_id` (`permission_parent_id`);

--
-- Indexes for table `permission_parent`
--
ALTER TABLE `permission_parent`
  ADD PRIMARY KEY (`permission_parent_id`),
  ADD UNIQUE KEY `permission_parent_system_name` (`permission_parent_system_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `contractor`
--
ALTER TABLE `contractor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `invoice_item`
--
ALTER TABLE `invoice_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permission_parent`
--
ALTER TABLE `permission_parent`
  MODIFY `permission_parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contractor_mn_permission`
--
ALTER TABLE `contractor_mn_permission`
  ADD CONSTRAINT `contractor_mn_permission_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contractor_mn_permission_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD CONSTRAINT `invoice_item_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`permission_parent_id`) REFERENCES `permission_parent` (`permission_parent_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
