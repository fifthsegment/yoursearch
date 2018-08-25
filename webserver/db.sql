-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 25, 2018 at 07:37 AM
-- Server version: 10.1.35-MariaDB-1~xenial
-- PHP Version: 5.6.37-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `35_156_173_29_nip_io`
--

-- --------------------------------------------------------

--
-- Table structure for table `pagecache`
--

CREATE TABLE `pagecache` (
  `id` double UNSIGNED NOT NULL,
  `url` mediumtext NOT NULL,
  `data` text NOT NULL,
  `saved_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ref` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pagecontents`
--

CREATE TABLE `pagecontents` (
  `id` double UNSIGNED NOT NULL,
  `ref` double NOT NULL,
  `content` text NOT NULL,
  `revision` int(11) NOT NULL DEFAULT '1',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urlindex`
--

CREATE TABLE `urlindex` (
  `id` double UNSIGNED NOT NULL,
  `url` mediumtext NOT NULL,
  `saved` int(1) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pagecache`
--
ALTER TABLE `pagecache`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagecontents`
--
ALTER TABLE `pagecontents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urlindex`
--
ALTER TABLE `urlindex`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pagecache`
--
ALTER TABLE `pagecache`
  MODIFY `id` double UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagecontents`
--
ALTER TABLE `pagecontents`
  MODIFY `id` double UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urlindex`
--
ALTER TABLE `urlindex`
  MODIFY `id` double UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
