-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 17, 2021 at 06:31 PM
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
-- Table structure for table `lkp_agency`
--

CREATE TABLE `lkp_agency` (
  `agency_id` int(10) UNSIGNED NOT NULL,
  `agency_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `agency_email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `address` text CHARACTER SET utf8,
  `postcode` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `telephone` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `fax` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `added_by` int(10) UNSIGNED DEFAULT NULL,
  `added_datetime` datetime DEFAULT NULL,
  `ip_address` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lkp_agency`
--

INSERT INTO `lkp_agency` (`agency_id`, `agency_name`, `agency_email`, `address`, `postcode`, `country`, `telephone`, `fax`, `added_by`, `added_datetime`, `ip_address`, `status`) VALUES
(1, 'IHMS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 'Phoenix', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lkp_agency`
--
ALTER TABLE `lkp_agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lkp_agency`
--
ALTER TABLE `lkp_agency`
  MODIFY `agency_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
