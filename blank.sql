-- phpMyAdmin SQL Dump
-- version 4.1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2014 at 07:29 AM
-- Server version: 5.5.35
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wdr-contribution`
--

-- --------------------------------------------------------

--
-- Table structure for table `base`
--

CREATE TABLE IF NOT EXISTS `base` (
  `userid` int(6) NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(6) NOT NULL,
  `username` varchar(32) NOT NULL,
  `date` int(10) NOT NULL,
  `cycle` int(5) NOT NULL,
  `avatar` varchar(150) NOT NULL,
  `rank` int(3) NOT NULL,
  `score` double NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `loggedon` int(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `total`
--

CREATE TABLE IF NOT EXISTS `total` (
  `userid` int(5) NOT NULL,
  `rank` int(3) NOT NULL,
  `username` varchar(32) NOT NULL,
  `score` double NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `ppd` double NOT NULL,
  `avatar` varchar(150) NOT NULL,
  `logins` int(5) NOT NULL,
  `active` int(3) NOT NULL,
  UNIQUE KEY `id` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
