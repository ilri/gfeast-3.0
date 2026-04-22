-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 17, 2021 at 12:40 PM
-- Server version: 5.7.35-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `munichretest`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `role_description` text CHARACTER SET utf8,
  `can_add` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `added_by` int(10) UNSIGNED DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `ip_address` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`role_id`, `role_name`, `role_description`, `can_add`, `added_by`, `added_date`, `ip_address`, `status`) VALUES
(1, 'Product Admin', 'This is product admin role', '2,3,4,5,6,7,8', 1, '2020-01-02 00:00:00', '127.0.0.1', 1),
(2, 'Super Admin', 'Super Admin', '2,3,4,5,6,7,8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(3, 'Client Admin', 'Client Admin', '4,5,6,7,8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(4, 'HO Admin', 'Head Office Admin', '5,6,7,8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(5, 'State Admin', 'State Admin', '6,7,8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(6, 'Cluster Admin', 'Cluster Admin', '7,8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(7, 'District Admin', 'District Admin', '8', 1, '2020-01-02 00:00:00', '49.206.201.197', 1),
(8, 'Enumerator', 'Enumerator', NULL, 1, '2020-01-02 00:00:00', '49.206.201.197', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `added_by_2` (`added_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
