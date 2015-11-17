-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 13, 2015 at 03:39 PM
-- Server version: 5.6.27-0ubuntu1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mrh`
--

-- --------------------------------------------------------

--
-- Table structure for table `retreats`
--

CREATE TABLE IF NOT EXISTS `retreats` (
  `id` int(10) unsigned NOT NULL,
  `idnumber` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `silent` tinyint(1) NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `year` int(11) NOT NULL,
  `attending` int(11) NOT NULL,
  `directorid` int(11) NOT NULL,
  `innkeeperid` int(11) NOT NULL,
  `assistantid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=1359 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `retreats`
--

INSERT INTO `retreats` (`id`, `idnumber`, `title`, `description`, `start`, `end`, `type`, `silent`, `amount`, `year`, `attending`, `directorid`, `innkeeperid`, `assistantid`, `created_at`, `updated_at`) VALUES
(1227, 215, 'AA Men', '', '2015-01-02 06:00:00', '2015-01-04 06:00:00', '', 0, 0.00, 2015, 60, 4, 3, 1, '0000-00-00 00:00:00', '2015-11-14 03:23:51'),
(1275, 1015, 'Women', '', '2015-03-05 06:00:00', '2015-03-08 06:00:00', '', 0, 0.00, 2015, 51, 1, 4, 3, '0000-00-00 00:00:00', '2015-11-14 03:09:23'),
(1286, 26015, 'Hispanic Men/Women/Couples', '', '2015-02-13 06:00:00', '2015-02-15 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1295, 515, 'Men', '', '2015-01-15 06:00:00', '2015-01-18 06:00:00', '', 0, 0.00, 2015, 28, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1296, 315, 'Men', '', '2015-01-08 06:00:00', '2015-01-11 06:00:00', '', 0, 0.00, 2015, 65, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1297, 6115, 'Dallas Priests I', '', '2015-01-19 06:00:00', '2015-01-23 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1298, 6415, 'Fort Worth Priests', '', '2015-01-11 06:00:00', '2015-01-15 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1299, 6515, 'Episcopal Priests', '', '2015-01-26 06:00:00', '2015-01-29 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1300, 715, 'Women', '', '2015-02-05 06:00:00', '2015-02-08 06:00:00', '', 0, 0.00, 2015, 43, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1301, 20715, 'Montserrat Day of Renewal', '', '2015-02-07 06:00:00', '2015-02-07 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1302, 8215, 'Tyler Priests', '', '2015-02-09 06:00:00', '2015-02-13 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1303, 1015, 'Women', '', '2015-03-05 06:00:00', '2015-03-08 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1304, 1115, 'Men', '', '2015-03-12 05:00:00', '2015-03-15 05:00:00', '', 0, 0.00, 2015, 54, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1305, 1215, 'Women', '', '2015-03-26 05:00:00', '2015-03-29 05:00:00', '', 0, 0.00, 2015, 44, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1306, 13015, 'Holy Week Women', '', '2015-03-29 05:00:00', '2015-04-01 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1307, 1515, 'Holy Week Men', '', '2015-04-01 05:00:00', '2015-04-04 05:00:00', '', 0, 0.00, 2015, 38, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1308, 1415, 'Women', '', '2015-04-09 05:00:00', '2015-04-12 05:00:00', '', 0, 0.00, 2015, 57, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1309, 5315, 'Women', '', '2015-04-16 05:00:00', '2015-04-19 05:00:00', '', 0, 0.00, 2015, 63, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1310, 43015, 'Dallas Deacons/Spouses II', '', '2015-04-30 05:00:00', '2015-05-03 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-11-13 23:32:11'),
(1311, 8315, 'Tyler Deacons/Spouses I', '', '2015-04-24 05:00:00', '2015-04-26 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1312, 1715, 'Women', '', '2015-05-07 05:00:00', '2015-05-10 05:00:00', '', 0, 0.00, 2015, 20, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1313, 50215, 'Montserrat Day of Renewal', '', '2015-05-02 05:00:00', '2015-05-02 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1314, 2015, 'Men cancelled', '', '2015-05-21 05:00:00', '2015-05-24 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1315, 83015, 'Tyler Deacons/Spouses II', '', '2015-05-29 05:00:00', '2015-05-31 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1316, 9315, 'Open 2-Day cancelled', '', '2015-06-05 05:00:00', '2015-06-07 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1317, 61515, 'Vincentian Priests', '', '2015-06-15 05:00:00', '2015-06-19 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1318, 615, 'Fort Worth Deacons/Spouses', '', '2015-06-19 05:00:00', '2015-06-21 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1319, 7515, '5-Day Directed', '', '2015-06-25 05:00:00', '2015-06-30 05:00:00', '', 0, 0.00, 2015, 22, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1320, 7615, '8-Day Directed', '', '2015-06-25 05:00:00', '2015-07-03 05:00:00', '', 0, 0.00, 2015, 24, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1321, 60615, 'Montserrat Day of Renewal', '', '2015-06-06 05:00:00', '2015-06-06 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1322, 70415, 'Montserrat Day of Renewal', '', '2015-07-04 05:00:00', '2015-07-04 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1323, 9515, 'Open 2-Day', '', '2015-07-10 05:00:00', '2015-07-12 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1324, 2815, 'Women', '', '2015-07-16 05:00:00', '2015-07-19 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1325, 80115, 'Montserrat Day of Renewal', '', '2015-08-01 05:00:00', '2015-08-01 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1326, 3315, 'Hispanic Men/Women/Couples', '', '2015-08-07 05:00:00', '2015-08-09 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1327, 9815, 'Open 2-Day', '', '2015-08-14 05:00:00', '2015-08-16 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1328, 415, 'AA Women', '', '2015-08-20 05:00:00', '2015-08-23 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1329, 82015, 'Episcopal Deacons', '', '2015-08-20 05:00:00', '2015-08-22 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1330, 3915, 'Women', '', '2015-09-03 05:00:00', '2015-09-06 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1331, 4415, 'Women', '', '2015-09-10 05:00:00', '2015-09-13 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1332, 90515, 'Montserrat Day of Renewal', '', '2015-09-05 05:00:00', '2015-09-05 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1333, 4215, 'Men', '', '2015-10-01 05:00:00', '2015-10-04 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1334, 4315, 'Dallas Deacons/Spouses I', '', '2015-10-08 05:00:00', '2015-10-11 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1335, 61015, 'Dallas Priests II', '', '2015-10-12 05:00:00', '2015-10-16 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1336, 5615, 'Oklahoma City Deacons', '', '2015-10-23 05:00:00', '2015-10-25 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1337, 100315, 'Montserrat Day of Renewal', '', '2015-10-03 05:00:00', '2015-10-03 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1338, 4615, 'Women', '', '2015-11-05 06:00:00', '2015-11-08 06:00:00', '', 0, 0.00, 2015, 0, 4, 3, 2, '0000-00-00 00:00:00', '2015-11-12 09:39:40'),
(1339, 9615, 'Couples', '', '2015-11-12 06:00:00', '2015-11-15 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1340, 110715, 'Montserrat Day of Renewal', '', '2015-11-07 06:00:00', '2015-11-07 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1341, 5015, 'Men Advent I', '', '2015-12-03 06:00:00', '2015-12-06 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1342, 9915, 'Open 2-Day', '', '2015-12-07 06:00:00', '2015-12-09 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1343, 120515, 'Montserrat Day of Renewal', '', '2015-12-05 06:00:00', '2015-12-05 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1344, 10515, 'Jesuit College Prep Staff', '', '2015-01-05 06:00:00', '2015-01-06 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1346, 21815, 'Ash Wednesday', '', '2015-02-18 06:00:00', '2015-02-18 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1347, 22615, 'Tyler Spirituality Group', '', '2015-02-26 06:00:00', '2015-02-28 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1348, 30715, 'Montserrat 1st Saturday', '', '2015-03-07 06:00:00', '2015-03-07 06:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1349, 32115, 'Starter Retreat', 'Fr. Edmundo Rodriguez', '2015-03-21 05:00:00', '2015-03-21 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1350, 32215, 'Padres de Nino''s de Primera Communion', 'Fr. Edmundo Rodriguez', '2015-03-22 05:00:00', '2015-03-22 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1351, 91915, 'Grief Workshop #1', 'Liz Moulin', '2015-09-19 05:00:00', '2015-09-19 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1352, 32815, 'Knights of Columbus #759 Day', 'Fr. Edmundo Rodriguez', '2015-03-28 05:00:00', '2015-03-28 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1353, 42315, 'Tyler Spirituality Program', '', '2015-04-23 05:00:00', '2015-04-25 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1354, 72615, 'ISI Practicum & Workshop', 'Fr. Tetlow/Carol Ackels', '2015-07-26 05:00:00', '2015-08-02 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1355, 72315, 'Tyler Spirituality Program', '', '2015-07-23 05:00:00', '2015-07-25 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1356, 92615, 'Grief Workshop #2', 'Liz Moulin', '2015-09-26 05:00:00', '2015-09-26 05:00:00', '', 0, 0.00, 2015, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1358, 5555, 'aoeu', '', '2015-11-13 06:00:00', '2015-11-16 06:00:00', 'aoeu', 1, 400.00, 2015, 25, 1, 3, 4, '2015-11-13 10:57:52', '2015-11-14 03:01:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `retreats`
--
ALTER TABLE `retreats`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `retreats`
--
ALTER TABLE `retreats`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1359;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
