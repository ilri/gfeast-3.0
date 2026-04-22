-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 23, 2021 at 04:09 AM
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
-- Table structure for table `lkp_district`
--

CREATE TABLE `lkp_district` (
  `district_id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `district_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `lat` varchar(100) DEFAULT NULL,
  `lng` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lkp_district`
--

INSERT INTO `lkp_district` (`district_id`, `country_id`, `state_id`, `district_name`, `lat`, `lng`, `status`) VALUES
(31, 1, 2, 'Ambala', NULL, NULL, 1),
(32, 1, 2, 'Bhiwani', NULL, NULL, 1),
(33, 1, 2, 'Charkhi Dadri', NULL, NULL, 1),
(34, 1, 2, 'Faridabad', NULL, NULL, 1),
(35, 1, 2, 'Fatehabad', NULL, NULL, 1),
(36, 1, 2, 'Gurgaon', NULL, NULL, 1),
(37, 1, 2, 'Hisar', NULL, NULL, 1),
(38, 1, 2, 'Jhajjar', NULL, NULL, 1),
(39, 1, 2, 'Jind', NULL, NULL, 1),
(40, 1, 2, 'Kaithal', NULL, NULL, 1),
(41, 1, 2, 'Karnal', NULL, NULL, 1),
(42, 1, 2, 'Kurukshetra', NULL, NULL, 1),
(43, 1, 2, 'Mahendragarh', NULL, NULL, 1),
(44, 1, 2, 'Mewat', NULL, NULL, 1),
(45, 1, 2, 'Palwal', NULL, NULL, 1),
(46, 1, 2, 'Panchkula', NULL, NULL, 1),
(47, 1, 2, 'Panipat', NULL, NULL, 1),
(48, 1, 2, 'Rewari', NULL, NULL, 1),
(49, 1, 2, 'Rohtak', NULL, NULL, 1),
(50, 1, 2, 'Sirsa', NULL, NULL, 1),
(51, 1, 2, 'Sonipat', NULL, NULL, 1),
(52, 1, 2, 'Yamunanagar', NULL, NULL, 1),
(53, 1, 6, 'Bagalkot', NULL, NULL, 1),
(54, 1, 6, 'Bangalore Rural', NULL, NULL, 1),
(55, 1, 6, 'Chamarajanagar', NULL, NULL, 1),
(56, 1, 6, 'Chikmagalur', NULL, NULL, 1),
(57, 1, 6, 'Gadag', NULL, NULL, 1),
(58, 1, 6, 'Uttar Kannada', NULL, NULL, 1),
(59, 1, 7, 'Osmanabad', NULL, NULL, 1),
(68, 1, 3, 'Ajmer', NULL, NULL, 1),
(69, 1, 3, 'Jalor', NULL, NULL, 1),
(70, 1, 3, 'Kota', NULL, NULL, 1),
(71, 1, 3, 'Sawai Madhopur', NULL, NULL, 1),
(72, 1, 1, 'Balrampur', NULL, NULL, 1),
(73, 1, 1, 'Bastar', NULL, NULL, 1),
(74, 1, 1, 'Bijapur', NULL, NULL, 1),
(75, 1, 1, 'Bilaspur', NULL, NULL, 1),
(76, 1, 1, 'Gorella-Pendra-Marwahi', NULL, NULL, 1),
(77, 1, 1, 'Jashpur', NULL, NULL, 1),
(78, 1, 1, 'Kabeerdham', NULL, NULL, 1),
(79, 1, 1, 'Raipur', NULL, NULL, 1),
(80, 1, 3, 'Alwar', NULL, NULL, 1),
(81, 1, 3, 'Bhilwara', NULL, NULL, 1),
(82, 1, 3, 'Churu', NULL, NULL, 1),
(83, 1, 3, 'Dausa', NULL, NULL, 1),
(84, 1, 3, 'Jhalawar', NULL, NULL, 1),
(85, 1, 3, 'Rajsamand', NULL, NULL, 1),
(86, 1, 3, 'Sri Ganganagar', NULL, NULL, 1),
(87, 1, 8, 'Bargarh', NULL, NULL, 1),
(88, 1, 8, 'Bhadrak', NULL, NULL, 1),
(89, 1, 8, 'Debagarh', NULL, NULL, 1),
(90, 1, 8, 'Gajapati', NULL, NULL, 1),
(91, 1, 8, 'Jagatsinghapur', NULL, NULL, 1),
(92, 1, 8, 'Jajapur', NULL, NULL, 1),
(93, 1, 8, 'Kendrapara', NULL, NULL, 1),
(94, 1, 8, 'Mayurbhanj', NULL, NULL, 1),
(95, 1, 8, 'Nabarangapur', NULL, NULL, 1),
(96, 1, 7, 'Akola', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lkp_district`
--
ALTER TABLE `lkp_district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `county_id` (`country_id`),
  ADD KEY `sub_county_id` (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lkp_district`
--
ALTER TABLE `lkp_district`
  MODIFY `district_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
