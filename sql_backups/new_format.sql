-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 17, 2013 at 11:26 AM
-- Server version: 5.0.96-community-log
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `code_wdr`
--

-- --------------------------------------------------------

--
-- Table structure for table `base`
--

CREATE TABLE IF NOT EXISTS `base` (
  `id` int(11) NOT NULL auto_increment,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `url` int(10) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(3) NOT NULL,
  `username` varchar(32) NOT NULL,
  `date` int(10) NOT NULL,
  `cycle` int(5) NOT NULL,
  `avatar` varchar(150) NOT NULL,
  `rank` int(3) NOT NULL,
  `score` double NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `rank_change` int(3) NOT NULL,
  `score_change` int(3) NOT NULL,
  `posts_change` int(3) NOT NULL,
  `reputation_change` int(3) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `total`
--

CREATE TABLE IF NOT EXISTS `total` (
  `id` int(11) NOT NULL auto_increment,
  `rank` int(3) NOT NULL,
  `username` varchar(32) NOT NULL,
  `score` double NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `ppd` double NOT NULL,
  `avatar` varchar(150) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         