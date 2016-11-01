-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2016 at 05:00 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rockz`
--

-- --------------------------------------------------------

--
-- Table structure for table `device_token`
--

CREATE TABLE IF NOT EXISTS `device_token` (
  `id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `devicetoken` text NOT NULL,
  `devicetype` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device_token`
--

INSERT INTO `device_token` (`id`, `userid`, `devicetoken`, `devicetype`) VALUES
(1, 8, 'BA1A196A7DD63B5EE634BD83B338F366C19D47A64FE0759E9CFDF52E647475B2', 0),
(2, 9, '2D4C50EDEF64212CA6A15D0B8E012D0BC25307C99C1D5AE45970E37C1D6535B5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `id` bigint(20) NOT NULL,
  `partyid` bigint(20) NOT NULL,
  `guestid` bigint(20) NOT NULL,
  `mainhostid` bigint(20) NOT NULL,
  `allow` tinyint(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`id`, `partyid`, `guestid`, `mainhostid`, `allow`) VALUES
(1, 1, 8, 7, 2),
(3, 2, 7, 8, 2),
(7, 1, 8, 9, 0),
(32, 1, 9, 7, 0),
(33, 10, 9, 7, 0),
(40, 6, 9, 7, 0),
(41, 8, 9, 7, 0),
(42, 9, 9, 7, 0),
(43, 7, 9, 7, 0),
(89, 46, 8, 9, 0),
(90, 29, 8, 9, 0),
(91, 47, 8, 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `host`
--

CREATE TABLE IF NOT EXISTS `host` (
  `id` bigint(20) NOT NULL,
  `partyid` bigint(20) NOT NULL,
  `mainhostid` bigint(20) NOT NULL,
  `hostid` bigint(20) NOT NULL,
  `allow` tinyint(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `host`
--

INSERT INTO `host` (`id`, `partyid`, `mainhostid`, `hostid`, `allow`) VALUES
(1, 2, 7, 40, 0),
(15, 2, 7, 41, 0),
(16, 2, 7, 42, 0),
(18, 46, 9, 1, 1),
(19, 46, 9, 7, 1),
(20, 46, 9, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `party`
--

CREATE TABLE IF NOT EXISTS `party` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `date` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `mainhost` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `price` float NOT NULL,
  `picture` varchar(100) NOT NULL DEFAULT 'default_party.png'
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `party`
--

INSERT INTO `party` (`id`, `name`, `address`, `longitude`, `latitude`, `date`, `starttime`, `endtime`, `mainhost`, `status`, `type`, `comment`, `price`, `picture`) VALUES
(1, 'Footbal', 'New york USA', 125, 136.5, '2015-11-05', '07:00:00', '15:00:00', 7, 0, 0, '', 0, '1_party.png'),
(2, 'ice cream party', 'Texas, USA', 232, 122, '2015-11-19', '00:00:00', '10:00:00', 8, 0, 0, '', 30, '2_party.png'),
(6, 'asf', 'asdfasf', 33, 35, '2015-11-04', '09:00:00', '00:25:00', 7, 0, 0, '', 55, 'default_party.png'),
(7, 'asf', 'asdfasf', 33, 35, '2015-11-04', '09:00:00', '00:25:00', 7, 0, 0, 'fghfgh', 55, 'default_party.png'),
(8, 'asf', 'asdfasf', 33, 35, '2015-11-04', '09:00:00', '00:25:00', 7, 0, 0, 'fghfgh', 55, 'default_party.png'),
(9, 'asf', 'asdfasf', 33, 35, '2015-11-04', '09:00:00', '00:25:00', 7, 0, 0, 'fghfgh', 55, 'default_party.png'),
(10, 'asf', 'asdfasf', 33, 35, '2015-11-04', '09:00:00', '00:25:00', 7, 1, 1, 'da  ravefsefcefceCAcaWECAFEVSFSEFVSVSVSSFD', 55, 'default_party.png'),
(29, 'dsfsdfsdf', 'sdfsdf', 100, 100, '2016-11-15', '09:41:28', '10:41:28', 9, 0, 1, 'Write here about what will roll in party', 56, '29_party.png'),
(46, '555', '1120 19th St NW, Washington, DC 20036, United States', -77.0439, 38.9045, '2015-11-22', '07:35:10', '10:35:10', 9, 0, 1, 'Write here about what will roll in party', 199, '46_party.png'),
(47, '555', '1120 19th St NW, Washington, DC 20036, United States', -77.0439, 38.9045, '2015-11-22', '07:35:10', '10:35:10', 9, 0, 1, 'Write here about what will roll in party', 199, '47_party.png');

--
-- Triggers `party`
--
DELIMITER $$
CREATE TRIGGER `delete_party` AFTER DELETE ON `party`
 FOR EACH ROW BEGIN
DELETE FROM `rockz`.`guest` WHERE `guest`.`partyid` = old.id;
DELETE FROM `rockz`.`host` WHERE `host`.`partyid` = old.id ;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `photo` text NOT NULL,
  `coverphoto` varchar(500) NOT NULL DEFAULT 'default_cover.png',
  `online` tinyint(1) NOT NULL,
  `facebookid` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `photo`, `coverphoto`, `online`, `facebookid`) VALUES
(1, 'Ronaldo', 'c', 'RealMadrid', 'real@gmail.com', '2332323', '1_photo.png', '1_cover.png', 0, ''),
(7, 'jason', 'k', 'polaris', 'jasonisme555@gmail.com', 'admin', '7_photo.png', '7_cover.png', 0, ''),
(8, 'john', 'thai', 'polariss', 'jasonisme555@gmail.com', 'admin', '8_photo.png', '8_cover.png', 1, ''),
(9, 'eirc', 'k', 'guest', 'ericisme', 'guest', '9_photo.png', '9_cover.png', 1, ''),
(23, 'shirake', 'ray', '111', 'ray...gmail.com', 'ui', '', '', 1, ''),
(28, 'ro', 'ray', 'pp', 'dsd', 'ddd', '', 'default_cover.png', 1, ''),
(32, '111', '222', '555', '333', '444', '', 'default_cover.png', 1, ''),
(37, '111', '222', '444', '333', '555', '', 'default_cover.png', 1, ''),
(39, '11', '22', '55', '33', '44', '', 'default_cover.png', 1, ''),
(40, 'jack', 'chan', '4', 'jack23@gmail.com', 'jack', '', 'default_cover.png', 1, ''),
(41, 'e', 'e', 'wer', 'er', 'qqq', '', 'default_cover.png', 1, ''),
(42, 'sdfds', 'sdfds', 'admin', 'sdsdsd', 'admin', '', 'default_cover.png', 1, ''),
(43, 'saf', 'asfdas', 'dsfsdf', 'asdf', '333', '', 'default_cover.png', 1, ''),
(44, '324', '324', '333333', '2342342', '333', '', 'default_cover.png', 1, ''),
(45, 'sdsd', 'sddssd', 'guest1', 'sadsasd', 'ddd', '', 'default_cover.png', 1, ''),
(46, 'sdsd', 'sdsd', 'RonaldoLL', 'sdsdsdsd', 'ddd', '', 'default_cover.png', 1, ''),
(47, 'www', 'wwww', 'www', 'wwwww', 'www', '', 'default_cover.png', 1, ''),
(48, 'eee', 'eee', 'eee', 'eee', 'eee', '', 'default_cover.png', 1, ''),
(49, 'dsds', 'dsd', 'fff', 'dsds', 'fff', '', 'default_cover.png', 1, ''),
(50, 'zzz', 'zzz', 'zzz', 'zzz', 'zzz', '', 'default_cover.png', 1, ''),
(51, 'erer', 'erer', '666666', 'erere', 'we', '', 'default_cover.png', 1, ''),
(52, 'iii', 'iii', 'iii', 'iii', 'iii', '49_photo.png', '49_photo.png', 1, ''),
(53, 'ip', 'ip', 'ip', 'ip', 'ip', '', 'default_cover.png', 1, ''),
(54, 'ty', 'ty', 'ty', 'ty', 'ty', '', 'default_cover.png', 1, ''),
(55, 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', '', 'default_cover.png', 1, ''),
(56, 'qa', 'qa', 'qa', 'qa', 'qa', '', 'default_cover.png', 1, ''),
(57, 'df', 'fv', 'dfwwer', 'bfd', '44', '', 'default_cover.png', 1, ''),
(58, 'ttt', 'ttt', 'ttt', 'ttt', 'ttt', '58_photo.png', 'default_cover.png', 1, ''),
(59, 'jjjj', 'jjjj', 'jjjj', 'jjjj', 'jjjj', '59_photo.png', '59_cover.png', 1, ''),
(60, 'dsfsdf', 'fsdfsdf', 'fdsf', 'dsfsdf', 'eee', '60_photo.png', 'default_cover.png', 1, ''),
(74, 'Hai', 'Ha', '', 'jasonisme555@gmail.com', '', '_photo.png', 'default_cover.png', 0, '214520868888531');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device_token`
--
ALTER TABLE `device_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `host`
--
ALTER TABLE `host`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `party`
--
ALTER TABLE `party`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device_token`
--
ALTER TABLE `device_token`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `host`
--
ALTER TABLE `host`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `party`
--
ALTER TABLE `party`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=76;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
