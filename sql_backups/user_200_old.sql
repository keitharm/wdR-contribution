-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 10, 2013 at 01:59 PM
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
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(32) NOT NULL,
  `score` double NOT NULL,
  `posts` int(5) NOT NULL,
  `reputation` int(5) NOT NULL,
  `joindate` int(10) NOT NULL,
  `ppd` double NOT NULL,
  `url` varchar(150) NOT NULL,
  `avatar` varchar(150) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `score`, `posts`, `reputation`, `joindate`, `ppd`, `url`, `avatar`) VALUES
(1, 'Kyek', 4575.25, 5240, 1160, 1266642000, 3.94, 'http://webdevrefinery.com/forums/user/1-kyek/', 'http://webdevrefinery.com/forums/uploads/av-1.png?_r=0'),
(2, 'hydralisk', 3387.44, 496, 9005, 1267506000, 0.38, 'http://webdevrefinery.com/forums/user/2-hydralisk/', 'http://webdevrefinery.com/forums/uploads/av-2.png?_r=0'),
(3, 'AjBlue', 0.79, 172, 6, 1268024400, 0.13, 'http://webdevrefinery.com/forums/user/244-ajblue/', 'http://webdevrefinery.com/forums/uploads/av-244.jpg?_r=0'),
(4, 'Olli', 51.67, 685, 99, 1268024400, 0.52, 'http://webdevrefinery.com/forums/user/61-olli/', 'http://webdevrefinery.com/forums/uploads/av-61.jpg?_r=0'),
(5, 'Carson', 18.94, 857, 29, 1268024400, 0.65, 'http://webdevrefinery.com/forums/user/302-carson/', 'http://www.gravatar.com/avatar/76f6536d01d15732013a2ebdef48fdaf?s=100'),
(6, 'Dissident', 1.76, 144, 16, 1268024400, 0.11, 'http://webdevrefinery.com/forums/user/13-dissident/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-13.jpg?_r=0'),
(7, 'Rob', 1.26, 207, 8, 1268024400, 0.16, 'http://webdevrefinery.com/forums/user/189-rob/', 'http://webdevrefinery.com/forums/uploads/av-189.png?_r=0'),
(8, 'magik', 66.74, 1095, 80, 1268024400, 0.83, 'http://webdevrefinery.com/forums/user/325-magik/', 'http://webdevrefinery.com/forums/uploads/av-325.jpg?_r=0'),
(9, 'Mark', 3.76, 549, 9, 1268024400, 0.42, 'http://webdevrefinery.com/forums/user/258-mark/', 'http://webdevrefinery.com/forums/uploads/av-258.png?_r=0'),
(10, 'Wyatt', 1.12, 293, 5, 1268024400, 0.22, 'http://webdevrefinery.com/forums/user/330-wyatt/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-330.gif?_r=0'),
(11, 'IcyTexx', 1.88, 352, 7, 1268024400, 0.27, 'http://webdevrefinery.com/forums/user/287-icytexx/', 'http://webdevrefinery.com/forums/uploads/av-287.png?_r=0'),
(12, 'Secretss', 4.35, 248, 23, 1268024400, 0.19, 'http://webdevrefinery.com/forums/user/9-secretss/', 'http://webdevrefinery.com/forums/uploads/av-9.png?_r=0'),
(13, 'Starblaster100', 5.63, 211, 35, 1268024400, 0.16, 'http://webdevrefinery.com/forums/user/311-starblaster100/', 'images/wdr_default.png'),
(14, 'AwesomezGuy', 142.75, 1266, 148, 1268024400, 0.96, 'http://webdevrefinery.com/forums/user/82-awesomezguy/', 'http://webdevrefinery.com/forums/uploads/av-82.png?_r=0'),
(15, 'Smarag', 24.47, 584, 55, 1268024400, 0.44, 'http://webdevrefinery.com/forums/user/12-smarag/', 'images/wdr_default.png'),
(16, 'Renegade', 49.34, 753, 86, 1268024400, 0.57, 'http://webdevrefinery.com/forums/user/110-renegade/', 'http://webdevrefinery.com/forums/uploads/av-110.png?_r=0'),
(17, 'DarkCoder', 33.44, 1463, 30, 1268024400, 1.11, 'http://webdevrefinery.com/forums/user/98-darkcoder/', 'http://webdevrefinery.com/forums/uploads/av-98.png?_r=0'),
(18, 'Hyde', 145.91, 1623, 118, 1268024400, 1.24, 'http://webdevrefinery.com/forums/user/310-hyde/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-310.png?_r=0'),
(19, 'Quinn', 87.63, 1307, 88, 1268024400, 1, 'http://webdevrefinery.com/forums/user/235-quinn/', 'http://webdevrefinery.com/forums/uploads/av-235.png?_r=0'),
(20, 'Chris', 0, 106, 0, 1268024400, 0.08, 'http://webdevrefinery.com/forums/user/113-chris/', 'http://webdevrefinery.com/forums/uploads/av-113.jpg?_r=0'),
(21, 'Mack', 153.86, 2082, 97, 1268024400, 1.59, 'http://webdevrefinery.com/forums/user/282-mack/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-282.jpg?_r=0'),
(22, 'iRook', 1.6, 300, 7, 1268110800, 0.23, 'http://webdevrefinery.com/forums/user/702-irook/', 'http://webdevrefinery.com/forums/uploads/av-702.png?_r=0'),
(23, 'ta6ish', 0.09, 115, 1, 1268110800, 0.09, 'http://webdevrefinery.com/forums/user/592-ta6ish/', 'http://webdevrefinery.com/forums/uploads/av-592.png?_r=0'),
(24, 'AfaoMAX', 0.63, 166, 5, 1268110800, 0.13, 'http://webdevrefinery.com/forums/user/425-afaomax/', 'http://webdevrefinery.com/forums/uploads/av-425.jpg?_r=0'),
(25, 'Nick', 17.02, 465, 48, 1268110800, 0.35, 'http://webdevrefinery.com/forums/user/599-nick/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-599.png?_r=0'),
(26, 'Protego', 0, 106, 0, 1268110800, 0.08, 'http://webdevrefinery.com/forums/user/653-protego/', 'http://webdevrefinery.com/forums/uploads/av-653.png?_r=0'),
(27, 'Nico', 7.02, 263, 35, 1268110800, 0.2, 'http://webdevrefinery.com/forums/user/497-nico/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-497.gif?_r=0'),
(28, 'arronhunt', 1185.1, 3485, 446, 1268110800, 2.66, 'http://webdevrefinery.com/forums/user/602-arronhunt/', 'http://www.gravatar.com/avatar/627d197fb09fe8fde309eac97b05e418?s=100'),
(29, 'XingChow', -0.58, 190, -4, 1268110800, 0.14, 'http://webdevrefinery.com/forums/user/446-xingchow/', 'http://webdevrefinery.com/forums/uploads/av-446.png?_r=0'),
(30, 'iH8Sn0w', 0, 231, 0, 1268197200, 0.18, 'http://webdevrefinery.com/forums/user/909-ih8sn0w/', 'http://webdevrefinery.com/forums/uploads/av-909.png?_r=0'),
(31, 'dida', 66.71, 1987, 44, 1268197200, 1.52, 'http://webdevrefinery.com/forums/user/872-dida/', 'http://webdevrefinery.com/forums/uploads/av-872.jpg?_r=0'),
(32, 'Koen', 427.99, 2504, 224, 1268197200, 1.91, 'http://webdevrefinery.com/forums/user/850-koen/', 'http://www.gravatar.com/avatar/c70e11e593d27379de06db109d73e18b?s=100'),
(33, 'Alvin21', 0.15, 195, 1, 1268197200, 0.15, 'http://webdevrefinery.com/forums/user/741-alvin21/', 'images/wdr_default.png'),
(34, '_Sam', 23.64, 673, 46, 1268283600, 0.51, 'http://webdevrefinery.com/forums/user/1049-sam/', 'images/wdr_default.png'),
(35, 'wind0ws', 0.5, 109, 6, 1268370000, 0.08, 'http://webdevrefinery.com/forums/user/1181-wind0ws/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-1181.jpg?_r=0'),
(36, 'Matas', 19.06, 804, 31, 1268456400, 0.61, 'http://webdevrefinery.com/forums/user/1290-matas/', 'http://webdevrefinery.com/forums/uploads/av-1290.png?_r=0'),
(37, 'newphp', 0.2, 128, 2, 1268625600, 0.1, 'http://webdevrefinery.com/forums/user/1586-newphp/', 'images/wdr_default.png'),
(38, 'sparkhh', 1.29, 336, 5, 1268884800, 0.26, 'http://webdevrefinery.com/forums/user/1866-sparkhh/', 'images/wdr_default.png'),
(39, 'Leamsi', 18.56, 806, 30, 1268884800, 0.62, 'http://webdevrefinery.com/forums/user/1903-leamsi/', 'http://webdevrefinery.com/forums/uploads/av-1903.jpg?_r=0'),
(40, 'ShanePerreault', 43.77, 1075, 53, 1268971200, 0.83, 'http://webdevrefinery.com/forums/user/1997-shaneperreault/', 'http://www.gravatar.com/avatar/c2161aec9a33d296106b71f5f68a90f4?s=100'),
(41, 'child_in_time', 1.79, 137, 17, 1269144000, 0.11, 'http://webdevrefinery.com/forums/user/2170-child-in-time/', 'http://webdevrefinery.com/forums/uploads/av-2170.png?_r=0'),
(42, 'Hamador', 1, 145, 9, 1269144000, 0.11, 'http://webdevrefinery.com/forums/user/2128-hamador/', 'http://www.gravatar.com/avatar/6ca9280a3bb71bd5e949103b962e0deb?s=100'),
(43, 'Aamer', 3.64, 525, 9, 1269230400, 0.4, 'http://webdevrefinery.com/forums/user/2213-aamer/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-2213.jpg?_r=1375116287'),
(44, 'Sole_Wolf', 7.81, 349, 29, 1269403200, 0.27, 'http://webdevrefinery.com/forums/user/2338-sole-wolf/', 'http://www.gravatar.com/avatar/51081c376e63230ff43918a0c224c3ed?s=100'),
(45, 'Cheddam', 0.3, 191, 2, 1270094400, 0.15, 'http://webdevrefinery.com/forums/user/2741-cheddam/', 'http://webdevrefinery.com/forums/uploads/av-2741.png?_r=0'),
(46, 'TheDevMan', 24.96, 617, 52, 1270353600, 0.48, 'http://webdevrefinery.com/forums/user/2848-thedevman/', 'http://webdevrefinery.com/forums/uploads/av-2848.png?_r=0'),
(47, 'BokTheGolem', 37.93, 668, 73, 1270353600, 0.52, 'http://webdevrefinery.com/forums/user/2865-bokthegolem/', 'http://webdevrefinery.com/forums/uploads/av-2865.png?_r=0'),
(48, 'Zachary', 6.6, 422, 20, 1270958400, 0.33, 'http://webdevrefinery.com/forums/user/3114-zachary/', 'http://webdevrefinery.com/forums/uploads/av-3114.png?_r=0'),
(49, 'Kirity', 2, 320, 8, 1271044800, 0.25, 'http://webdevrefinery.com/forums/user/3134-kirity/', 'http://webdevrefinery.com/forums/uploads/av-3134.jpg?_r=0'),
(50, 'soulcyon', 125.03, 1611, 99, 1271217600, 1.26, 'http://webdevrefinery.com/forums/user/3232-soulcyon/', 'images/wdr_default.png'),
(51, 'ianonavy', 104.4, 716, 186, 1271217600, 0.56, 'http://webdevrefinery.com/forums/user/3235-ianonavy/', 'http://webdevrefinery.com/forums/uploads/av-3235.png?_r=0'),
(52, 'Ruku', 160.16, 1396, 146, 1271476800, 1.1, 'http://webdevrefinery.com/forums/user/3309-ruku/', 'http://webdevrefinery.com/forums/uploads/av-3309.jpg?_r=0'),
(53, 'Daniel15', 1301.3, 3569, 464, 1271476800, 2.8, 'http://webdevrefinery.com/forums/user/3291-daniel15/', 'http://www.gravatar.com/avatar/872578c4e56897b913fd03ed88daef51?s=100'),
(54, 'FriesCream', 1.01, 128, 10, 1271649600, 0.1, 'http://webdevrefinery.com/forums/user/3354-friescream/', 'images/wdr_default.png'),
(55, 'Sync', 8.73, 336, 33, 1271736000, 0.26, 'http://webdevrefinery.com/forums/user/3404-sync/', 'http://webdevrefinery.com/forums/uploads/av-3404.jpg?_r=0'),
(56, 'Owen', 0.7, 146, 6, 1272600000, 0.12, 'http://webdevrefinery.com/forums/user/3687-owen/', 'http://webdevrefinery.com/forums/uploads/av-3687.png?_r=0'),
(57, 'NoizeMe', 37.72, 591, 80, 1273118400, 0.47, 'http://webdevrefinery.com/forums/user/3894-noizeme/', 'http://webdevrefinery.com/forums/uploads/av-3894.jpg?_r=0'),
(58, 'markbrown4', 20.81, 404, 64, 1274068800, 0.33, 'http://webdevrefinery.com/forums/user/4234-markbrown4/', 'http://webdevrefinery.com/forums/uploads/av-4234.jpg?_r=0'),
(59, 'Marked', 0.41, 126, 4, 1274414400, 0.1, 'http://webdevrefinery.com/forums/user/4328-marked/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-4328.gif?_r=0'),
(60, 'KiNG', 8.24, 637, 16, 1274500800, 0.51, 'http://webdevrefinery.com/forums/user/4343-king/', 'http://webdevrefinery.com/forums/uploads/av-4343.jpg?_r=0'),
(61, 'cookie', 18.24, 593, 38, 1274673600, 0.48, 'http://webdevrefinery.com/forums/user/4404-cookie/', 'images/wdr_default.png'),
(62, 'TheMaster', 40.78, 854, 59, 1274673600, 0.69, 'http://webdevrefinery.com/forums/user/4395-themaster/', 'images/wdr_default.png'),
(63, 'SergioT', 0.63, 110, 7, 1275192000, 0.09, 'http://webdevrefinery.com/forums/user/4535-sergiot/', 'http://webdevrefinery.com/forums/uploads/av-4535.png?_r=0'),
(64, 'Sephern', 171.79, 997, 211, 1275624000, 0.81, 'http://webdevrefinery.com/forums/user/4637-sephern/', 'images/wdr_default.png'),
(65, 'aldld', 6.37, 297, 26, 1276660800, 0.24, 'http://webdevrefinery.com/forums/user/4892-aldld/', 'http://www.gravatar.com/avatar/6d3cf1b1aff2bc8f9f7bbea6d2949eb1?s=100'),
(66, 'mkohlmyr', 3.21, 155, 25, 1277092800, 0.13, 'http://webdevrefinery.com/forums/user/4967-mkohlmyr/', 'http://www.gravatar.com/avatar/5d100475797bee0fc17ef2fac0c53e19?s=100'),
(67, 'gibbonweb', 408.5, 2078, 237, 1277265600, 1.72, 'http://webdevrefinery.com/forums/user/5013-gibbonweb/', 'http://webdevrefinery.com/forums/uploads/av-5013.jpg?_r=0'),
(68, 'Kasene', 0.86, 115, 9, 1277697600, 0.1, 'http://webdevrefinery.com/forums/user/5122-kasene/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-5122.jpg?_r=0'),
(69, 'AbrarSyed', 22.92, 685, 40, 1278129600, 0.57, 'http://webdevrefinery.com/forums/user/5205-abrarsyed/', 'http://www.gravatar.com/avatar/45af98f557327df590b5db0ff5e9dfef?s=100'),
(70, 'derTechniker', 47.69, 1210, 47, 1278388800, 1.01, 'http://webdevrefinery.com/forums/user/5242-dertechniker/', 'http://www.gravatar.com/avatar/13f3a1f27fed94ca77a0a0d0421953c0?s=100'),
(71, 'NeilHanlon', 93.84, 931, 120, 1278561600, 0.78, 'http://webdevrefinery.com/forums/user/5269-neilhanlon/', 'http://www.gravatar.com/avatar/b60e4362f034da465284d40d9e0b6626?s=100'),
(72, 'YungLovah', 0.32, 190, 2, 1278734400, 0.16, 'http://webdevrefinery.com/forums/user/5287-yunglovah/', 'http://webdevrefinery.com/forums/uploads/av-5287.jpg?_r=0'),
(73, 'Mo3', 285.47, 2001, 168, 1279684800, 1.7, 'http://webdevrefinery.com/forums/user/5574-mo3/', 'http://www.gravatar.com/avatar/61e53089b4bf4969ff3ba20e8d84c805?s=100'),
(74, 'Cyril', 296.3, 2556, 135, 1280808000, 2.19, 'http://webdevrefinery.com/forums/user/5758-cyril/', 'http://www.gravatar.com/avatar/8d326ebb02a3062038e9737f4fd0e1c8?s=100'),
(75, 'devinsba', 0.3, 116, 3, 1281240000, 0.1, 'http://webdevrefinery.com/forums/user/5849-devinsba/', 'http://www.gravatar.com/avatar/8d327c32d79a39833485484de00a38f3?s=100'),
(76, 'EternalNinja0', 0.6, 116, 6, 1281672000, 0.1, 'http://webdevrefinery.com/forums/user/5958-eternalninja0/', 'http://webdevrefinery.com/forums/uploads/av-5958.png?_r=0'),
(77, 'glynnforrest', 1.36, 130, 12, 1282104000, 0.11, 'http://webdevrefinery.com/forums/user/6038-glynnforrest/', 'http://www.gravatar.com/avatar/ec5ae674e7088727630dcf28632e49fa?s=100'),
(78, 'xDroid', 3.92, 374, 12, 1282536000, 0.33, 'http://webdevrefinery.com/forums/user/6121-xdroid/', 'images/wdr_default.png'),
(79, 'xHEARTLESSATTACKx', 0.5, 114, 5, 1283400000, 0.1, 'http://webdevrefinery.com/forums/user/6266-xheartlessattackx/', 'http://webdevrefinery.com/forums/uploads/av-6266.jpg?_r=0'),
(80, 'c2d1', 3.49, 165, 24, 1283400000, 0.15, 'http://webdevrefinery.com/forums/user/6265-c2d1/', 'images/wdr_default.png'),
(81, 'deucalion0', 0, 190, 0, 1283659200, 0.17, 'http://webdevrefinery.com/forums/user/6296-deucalion0/', 'images/wdr_default.png'),
(82, 'timnovis', 0.57, 157, 4, 1285905600, 0.14, 'http://webdevrefinery.com/forums/user/6583-timnovis/', 'http://webdevrefinery.com/forums/uploads/av-6583.jpg?_r=0'),
(83, 'TheEmpty', 1472.18, 5212, 312, 1285992000, 4.72, 'http://webdevrefinery.com/forums/user/6599-theempty/', 'http://www.gravatar.com/avatar/5225f74b5d05b955511d1d8a8f9dcf74?s=100'),
(84, 'Shadower856', 0.76, 138, 6, 1287115200, 0.13, 'http://webdevrefinery.com/forums/user/6774-shadower856/', 'images/wdr_default.png'),
(85, 'Qasim', 23.61, 532, 48, 1287979200, 0.49, 'http://webdevrefinery.com/forums/user/6991-qasim/', 'http://webdevrefinery.com/forums/uploads/av-6991.png?_r=0'),
(86, 'Fike', 13.25, 358, 40, 1288065600, 0.33, 'http://webdevrefinery.com/forums/user/7024-fike/', 'http://webdevrefinery.com/forums/uploads/av-7024.png?_r=0'),
(87, 'alexdavey', 47.53, 848, 60, 1288929600, 0.79, 'http://webdevrefinery.com/forums/user/7173-alexdavey/', 'http://webdevrefinery.com/forums/uploads/av-7173.jpg?_r=0'),
(88, 'Cocoa', 4, 418, 10, 1291093200, 0.4, 'http://webdevrefinery.com/forums/user/7520-cocoa/', 'http://www.gravatar.com/avatar/4722823c0921f59236ffe6615e5fab06?s=100'),
(89, 'SilverDoe', 1.97, 221, 9, 1294117200, 0.22, 'http://webdevrefinery.com/forums/user/8024-silverdoe/', 'http://webdevrefinery.com/forums/uploads/av-8024.jpg?_r=0'),
(90, 'gushort', 7.15, 481, 15, 1294203600, 0.48, 'http://webdevrefinery.com/forums/user/8070-gushort/', 'http://webdevrefinery.com/forums/uploads/av-8070.jpg?_r=0'),
(91, 'Dev4Ev', 0.12, 119, 1, 1294635600, 0.12, 'http://webdevrefinery.com/forums/user/8300-dev4ev/', 'http://webdevrefinery.com/forums/uploads/av-8300.png?_r=0'),
(92, 'callumacrae', 707.34, 2919, 241, 1295499600, 2.94, 'http://webdevrefinery.com/forums/user/8588-callumacrae/', 'http://webdevrefinery.com/forums/uploads/av-8588.png?_r=0'),
(93, 'Beau', 5.85, 303, 19, 1296363600, 0.31, 'http://webdevrefinery.com/forums/user/8681-beau/', 'http://webdevrefinery.com/forums/uploads/av-8681.png?_r=0'),
(94, 'Lemon', 179.4, 742, 232, 1298523600, 0.77, 'http://webdevrefinery.com/forums/user/8861-lemon/', 'http://www.gravatar.com/avatar/a3862f6abeb14017781999be8aff1308?s=100'),
(95, 'JustinP', 1.95, 311, 6, 1298696400, 0.32, 'http://webdevrefinery.com/forums/user/8871-justinp/', 'http://webdevrefinery.com/forums/uploads/av-8871.png?_r=0'),
(96, 'cosmie', 8.98, 251, 32, 1304136000, 0.28, 'http://webdevrefinery.com/forums/user/9306-cosmie/', 'images/wdr_default.png'),
(97, 'iCyan', 5.88, 207, 25, 1305432000, 0.24, 'http://webdevrefinery.com/forums/user/9382-icyan/', 'http://webdevrefinery.com/forums/uploads/av-9382.png?_r=0'),
(98, '@Tom', 36.85, 746, 43, 1306209600, 0.86, 'http://webdevrefinery.com/forums/user/9432-tom/', 'http://www.gravatar.com/avatar/23b2c854878d00f33eb9db5da4ae8e9d?s=100'),
(99, 'SapporoGuy', 90.51, 1073, 72, 1307678400, 1.26, 'http://webdevrefinery.com/forums/user/9549-sapporoguy/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-9549.gif?_r=0'),
(100, 'Varemenos', 1.05, 108, 7, 1319256000, 0.15, 'http://webdevrefinery.com/forums/user/10166-varemenos/', 'http://webdevrefinery.com/forums/uploads/av-10166.png?_r=0'),
(101, 'GreySyntax', 0.47, 102, 6, 1268024400, 0.08, 'http://webdevrefinery.com/forums/user/316-greysyntax/', 'http://webdevrefinery.com/forums/uploads/av-316.jpg?_r=0'),
(102, 'EhICan', 0.39, 102, 5, 1268024400, 0.08, 'http://webdevrefinery.com/forums/user/225-ehican/', 'http://webdevrefinery.com/forums/uploads/av-225.jpg?_r=0'),
(103, 'rafael', 0.07, 96, 1, 1268024400, 0.07, 'http://webdevrefinery.com/forums/user/16-rafael/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-16.gif?_r=0'),
(104, 'Ramsey', 0, 80, 0, 1268024400, 0.06, 'http://webdevrefinery.com/forums/user/8-ramsey/', 'http://www.gravatar.com/avatar/490a8115a8a8d074b9c84e130b44a240?s=100'),
(105, 'Celyst', -0.24, 78, -4, 1268024400, 0.06, 'http://webdevrefinery.com/forums/user/158-celyst/', 'http://webdevrefinery.com/forums/uploads/av-158.jpg?_r=0'),
(106, 'Jessee', 0.12, 79, 2, 1268024400, 0.06, 'http://webdevrefinery.com/forums/user/249-jessee/', 'images/wdr_default.png'),
(107, 'BootyliciousXx', 0.07, 48, 2, 1268024400, 0.04, 'http://webdevrefinery.com/forums/user/154-bootyliciousxx/', 'http://webdevrefinery.com/forums/uploads/av-154.png?_r=0'),
(108, 'Snuupy', 0.06, 76, 1, 1268024400, 0.06, 'http://webdevrefinery.com/forums/user/272-snuupy/', 'images/wdr_default.png'),
(109, 'iPhone', 0.04, 50, 1, 1268024400, 0.04, 'http://webdevrefinery.com/forums/user/37-iphone/', 'http://www.gravatar.com/avatar/9d8af5e82994c09836fdbb32b2f4e88b?s=100'),
(110, 'joelixny', 0.17, 45, 5, 1268024400, 0.03, 'http://webdevrefinery.com/forums/user/83-joelixny/', 'http://www.gravatar.com/avatar/c73eaaad73af7e94316f32c166761a5f?s=100'),
(111, 'Origin8', 0.05, 36, 2, 1268024400, 0.03, 'http://webdevrefinery.com/forums/user/317-origin8/', 'http://webdevrefinery.com/forums/uploads/av-317.jpg?_r=0'),
(112, 'Killer_X', 0, 42, 0, 1268024400, 0.03, 'http://webdevrefinery.com/forums/user/130-killer-x/', 'images/wdr_default.png'),
(113, 'Necrotex', 0, 43, 0, 1268024400, 0.03, 'http://webdevrefinery.com/forums/user/14-necrotex/', 'http://webdevrefinery.com/forums/uploads/av-14.jpg?_r=0'),
(114, 'Helpmy360isEMO', 0.07, 48, 2, 1268024400, 0.04, 'http://webdevrefinery.com/forums/user/19-helpmy360isemo/', 'images/wdr_default.png'),
(115, 'AceDecade', 0, 67, 0, 1268024400, 0.05, 'http://webdevrefinery.com/forums/user/47-acedecade/', 'http://webdevrefinery.com/forums/uploads/av-47.png?_r=0'),
(116, 'iWinterBoard', 0, 74, 0, 1268110800, 0.06, 'http://webdevrefinery.com/forums/user/675-iwinterboard/', 'images/wdr_default.png'),
(117, 'DJ_Psycho', 0, 38, 0, 1268110800, 0.03, 'http://webdevrefinery.com/forums/user/591-dj-psycho/', 'http://webdevrefinery.com/forums/uploads/av-591.jpg?_r=0'),
(118, 'masquerade', 0.08, 36, 3, 1268110800, 0.03, 'http://webdevrefinery.com/forums/user/608-masquerade/', 'images/wdr_default.png'),
(119, 'Alpha-Wat3rLoo', -0.18, 79, -3, 1268110800, 0.06, 'http://webdevrefinery.com/forums/user/429-alpha-wat3rloo/', 'images/wdr_default.png'),
(120, 'H4CK3R', -0.06, 85, -1, 1268110800, 0.06, 'http://webdevrefinery.com/forums/user/470-h4ck3r/', 'http://www.gravatar.com/avatar/5a321423163e276e074caadc52c367a6?s=100'),
(121, 'Rayzr', 0.07, 91, 1, 1268110800, 0.07, 'http://webdevrefinery.com/forums/user/442-rayzr/', 'images/wdr_default.png'),
(122, 'Comkid', 0.27, 87, 4, 1268110800, 0.07, 'http://webdevrefinery.com/forums/user/514-comkid/', 'http://www.gravatar.com/avatar/1b3f3588790a68ed289897cf5e4b1332?s=100'),
(123, 'Nate', 0.36, 67, 7, 1268110800, 0.05, 'http://webdevrefinery.com/forums/user/542-nate/', 'http://webdevrefinery.com/forums/uploads/av-542.jpg?_r=0'),
(124, 'Ozzapoo', 0, 47, 0, 1268110800, 0.04, 'http://webdevrefinery.com/forums/user/447-ozzapoo/', 'images/wdr_default.png'),
(125, 'Three3', 0.19, 42, 6, 1268197200, 0.03, 'http://webdevrefinery.com/forums/user/839-three3/', 'http://webdevrefinery.com/forums/uploads/av-839.png?_r=0'),
(126, 'heLLo', 0.05, 70, 1, 1268197200, 0.05, 'http://webdevrefinery.com/forums/user/805-hello/', 'http://webdevrefinery.com/forums/uploads/av-805.jpg?_r=0'),
(127, 'thesmart1', 0.05, 61, 1, 1268197200, 0.05, 'http://webdevrefinery.com/forums/user/961-thesmart1/', 'http://webdevrefinery.com/forums/uploads/av-961.jpg?_r=0'),
(128, 'AbysalRush', -0.03, 41, -1, 1268197200, 0.03, 'http://webdevrefinery.com/forums/user/926-abysalrush/', 'http://webdevrefinery.com/forums/uploads/av-926.png?_r=0'),
(129, 'KUNITZ', 0, 44, 0, 1268197200, 0.03, 'http://webdevrefinery.com/forums/user/814-kunitz/', 'http://webdevrefinery.com/forums/uploads/av-814.jpg?_r=0'),
(130, 'GrimSage', 0, 37, 0, 1268197200, 0.03, 'http://webdevrefinery.com/forums/user/870-grimsage/', 'images/wdr_default.png'),
(131, 'iMerk', 0, 48, 0, 1268283600, 0.04, 'http://webdevrefinery.com/forums/user/1056-imerk/', 'http://webdevrefinery.com/forums/uploads/av-1056.jpg?_r=0'),
(132, 'WhatMan', 0.04, 53, 1, 1268283600, 0.04, 'http://webdevrefinery.com/forums/user/1034-whatman/', 'http://webdevrefinery.com/forums/uploads/av-1034.png?_r=0'),
(133, 'AlanG', 0.05, 62, 1, 1268370000, 0.05, 'http://webdevrefinery.com/forums/user/1217-alang/', 'http://webdevrefinery.com/forums/uploads/av-1217.jpg?_r=0'),
(134, 'ficeto', 0.15, 96, 2, 1268370000, 0.07, 'http://webdevrefinery.com/forums/user/1208-ficeto/', 'images/wdr_default.png'),
(135, 'xFunKy', 0, 50, 0, 1268798400, 0.04, 'http://webdevrefinery.com/forums/user/1757-xfunky/', 'images/wdr_default.png'),
(136, 'danielhep', 0.07, 94, 1, 1268971200, 0.07, 'http://webdevrefinery.com/forums/user/1988-danielhep/', 'http://www.gravatar.com/avatar/2771090bc4a0168e97804c314819ced8?s=100'),
(137, 'Earnest', 0.9, 98, 12, 1268971200, 0.08, 'http://webdevrefinery.com/forums/user/1942-earnest/', 'http://webdevrefinery.com/forums/uploads/av-1942.jpg?_r=0'),
(138, 'grumman94', 0.11, 71, 2, 1269144000, 0.05, 'http://webdevrefinery.com/forums/user/2146-grumman94/', 'http://webdevrefinery.com/forums/uploads/av-2146.jpg?_r=0'),
(139, 'lobabob', 0.77, 100, 10, 1269144000, 0.08, 'http://webdevrefinery.com/forums/user/2145-lobabob/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-2145.jpg?_r=0'),
(140, 'Dvir', 0.24, 63, 5, 1269316800, 0.05, 'http://webdevrefinery.com/forums/user/2266-dvir/', 'images/wdr_default.png'),
(141, 'adamm', 0, 83, 0, 1269403200, 0.06, 'http://webdevrefinery.com/forums/user/2335-adamm/', 'images/wdr_default.png'),
(142, 'Ready2Learn', 0.06, 80, 1, 1270008000, 0.06, 'http://webdevrefinery.com/forums/user/2703-ready2learn/', 'images/wdr_default.png'),
(143, 'inkjetcanvas', 0.64, 74, 11, 1270699200, 0.06, 'http://webdevrefinery.com/forums/user/3008-inkjetcanvas/', 'http://webdevrefinery.com/forums/uploads/av-3008.png?_r=0'),
(144, 'ToonBoon', 0.16, 69, 3, 1271304000, 0.05, 'http://webdevrefinery.com/forums/user/3249-toonboon/', 'images/wdr_default.png'),
(145, 'GregHouse', 0.11, 69, 2, 1271908800, 0.05, 'http://webdevrefinery.com/forums/user/3489-greghouse/', 'http://webdevrefinery.com/forums/uploads/av-3489.png?_r=0'),
(146, 'running_onCode', 0, 42, 0, 1271995200, 0.03, 'http://webdevrefinery.com/forums/user/3500-running-oncode/', 'images/wdr_default.png'),
(147, 'Ric', 0, 104, 0, 1272859200, 0.08, 'http://webdevrefinery.com/forums/user/3772-ric/', 'images/wdr_default.png'),
(148, 'Crysis', 0.43, 90, 6, 1272945600, 0.07, 'http://webdevrefinery.com/forums/user/3825-crysis/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-3825.gif?_r=0'),
(149, 'zing', 0.07, 42, 2, 1273118400, 0.03, 'http://webdevrefinery.com/forums/user/3887-zing/', 'images/wdr_default.png'),
(150, 'Tobias', 2.53, 99, 32, 1273204800, 0.08, 'http://webdevrefinery.com/forums/user/3926-tobias/', 'images/wdr_default.png'),
(151, 'InvisibleX', 1.18, 82, 18, 1273291200, 0.07, 'http://webdevrefinery.com/forums/user/3966-invisiblex/', 'http://webdevrefinery.com/forums/uploads/profile/photo-3966.png?_r=0'),
(152, 'Liksko', 1.42, 89, 20, 1273377600, 0.07, 'http://webdevrefinery.com/forums/user/3973-liksko/', 'http://webdevrefinery.com/forums/uploads/av-3973.jpg?_r=0'),
(153, 'Sunnysidesounds', 0.07, 84, 1, 1273464000, 0.07, 'http://webdevrefinery.com/forums/user/4043-sunnysidesounds/', 'http://webdevrefinery.com/forums/uploads/av-4043.jpg?_r=0'),
(154, 'jonivb', 0.13, 39, 4, 1273636800, 0.03, 'http://webdevrefinery.com/forums/user/4081-jonivb/', 'http://webdevrefinery.com/forums/uploads/av-4081.jpg?_r=0'),
(155, 'Theta', 0, 56, 0, 1273982400, 0.05, 'http://webdevrefinery.com/forums/user/4192-theta/', 'http://webdevrefinery.com/forums/uploads/av-4192.png?_r=0'),
(156, 'SpiritStorm', 0, 100, 0, 1274760000, 0.08, 'http://webdevrefinery.com/forums/user/4421-spiritstorm/', 'images/wdr_default.png'),
(157, 'OneTopicMan', 0.07, 42, 2, 1275796800, 0.03, 'http://webdevrefinery.com/forums/user/4688-onetopicman/', 'images/wdr_default.png'),
(158, 'ONi', 0.07, 42, 2, 1275796800, 0.03, 'http://webdevrefinery.com/forums/user/4678-oni/', 'http://www.gravatar.com/avatar/f8146c37c5f897fb1dd64fb430a7ee7d?s=100'),
(159, 'DisneyRicky', 0.14, 58, 3, 1276747200, 0.05, 'http://webdevrefinery.com/forums/user/4913-disneyricky/', 'http://webdevrefinery.com/forums/uploads/av-4913.jpg?_r=0'),
(160, 'hego555', 0, 48, 0, 1277179200, 0.04, 'http://webdevrefinery.com/forums/user/4997-hego555/', 'images/wdr_default.png'),
(161, 'Dusk', 0.07, 44, 2, 1277438400, 0.04, 'http://webdevrefinery.com/forums/user/5051-dusk/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-5051.gif?_r=0'),
(162, 'priyeshdesign', 0.17, 41, 5, 1277784000, 0.03, 'http://webdevrefinery.com/forums/user/5131-priyeshdesign/', 'images/wdr_default.png'),
(163, 'framedrop', 0.26, 61, 5, 1279080000, 0.05, 'http://webdevrefinery.com/forums/user/5355-framedrop/', 'http://webdevrefinery.com/forums/uploads/av-5355.png?_r=0'),
(164, 'Kartoffels', 0, 38, 0, 1279080000, 0.03, 'http://webdevrefinery.com/forums/user/5366-kartoffels/', 'images/wdr_default.png'),
(165, 'Keiron_Lowe', 0.05, 64, 1, 1279166400, 0.05, 'http://webdevrefinery.com/forums/user/5404-keiron-lowe/', 'http://www.gravatar.com/avatar/f12bb3cf3d1521a7ceb0d0b2277656b7?s=100'),
(166, 'mlippie', 0.08, 90, 1, 1279252800, 0.08, 'http://webdevrefinery.com/forums/user/5478-mlippie/', 'images/wdr_default.png'),
(167, 'Ryu', 0.38, 74, 6, 1279252800, 0.06, 'http://webdevrefinery.com/forums/user/5477-ryu/', 'http://webdevrefinery.com/forums/uploads/av-5477.png?_r=0'),
(168, 'ayonimiller', 0, 40, 0, 1279598400, 0.03, 'http://webdevrefinery.com/forums/user/5554-ayonimiller/', 'images/wdr_default.png'),
(169, 'Kcmartz', 0, 48, 0, 1279944000, 0.04, 'http://webdevrefinery.com/forums/user/5620-kcmartz/', 'http://www.gravatar.com/avatar/60450f2d8b07ed15136d351d29606858?s=100'),
(170, 'papuccino1', 0, 41, 0, 1280289600, 0.04, 'http://webdevrefinery.com/forums/user/5678-papuccino1/', 'http://www.gravatar.com/avatar/b6fb2887eca6f71cde62436096b53131?s=100'),
(171, 'patrickquinn', 0, 51, 0, 1281326400, 0.04, 'http://webdevrefinery.com/forums/user/5874-patrickquinn/', 'images/wdr_default.png'),
(172, 'Cedrick', 0.22, 86, 3, 1281844800, 0.07, 'http://webdevrefinery.com/forums/user/5998-cedrick/', 'images/wdr_default.png'),
(173, 'Tim', 0, 86, 0, 1281931200, 0.07, 'http://webdevrefinery.com/forums/user/6009-tim/', 'http://webdevrefinery.com/forums/uploads/av-6009.jpg?_r=0'),
(174, 'AndrewJLingley', 0.06, 69, 1, 1284091200, 0.06, 'http://webdevrefinery.com/forums/user/6376-andrewjlingley/', 'http://www.gravatar.com/avatar/4b61bb40a1a2a6d7daab50a12295b0d6?s=100'),
(175, 'learn2play', 0.15, 57, 3, 1284523200, 0.05, 'http://webdevrefinery.com/forums/user/6428-learn2play/', 'http://webdevrefinery.com/forums/uploads/av-6428.png?_r=0'),
(176, 'twenty', 0.04, 39, 1, 1289624400, 0.04, 'http://webdevrefinery.com/forums/user/7299-twenty/', 'images/wdr_default.png'),
(177, 'AaronB', 0.17, 45, 4, 1290488400, 0.04, 'http://webdevrefinery.com/forums/user/7421-aaronb/', 'http://webdevrefinery.com/forums/uploads/profile/photo-thumb-7421.gif?_r=0'),
(178, 'Aviar', 0.07, 71, 1, 1291006800, 0.07, 'http://webdevrefinery.com/forums/user/7505-aviar/', 'images/wdr_default.png'),
(179, 'hazelong', 0.47, 61, 8, 1291870800, 0.06, 'http://webdevrefinery.com/forums/user/7645-hazelong/', 'http://www.gravatar.com/avatar/fb428fe30d5b6d50c70e7e18937a3bfa?s=100'),
(180, 'lemiant', 0, 48, 0, 1292475600, 0.05, 'http://webdevrefinery.com/forums/user/7748-lemiant/', 'http://webdevrefinery.com/forums/uploads/av-7748.png?_r=0'),
(181, 'GeekyMacBoy1', 0, 36, 0, 1293512400, 0.04, 'http://webdevrefinery.com/forums/user/7891-geekymacboy1/', 'http://webdevrefinery.com/forums/uploads/av-7891.jpeg?_r=0'),
(182, 'stenaphie', 0, 54, 0, 1295413200, 0.05, 'http://webdevrefinery.com/forums/user/8576-stenaphie/', 'images/wdr_default.png'),
(183, 'joshbedo', 0.04, 38, 1, 1295586000, 0.04, 'http://webdevrefinery.com/forums/user/8592-joshbedo/', 'http://www.gravatar.com/avatar/ca4c025cfa455b36d130c37a3fbf5032?s=100'),
(184, 'elm', 0, 60, 0, 1298264400, 0.06, 'http://webdevrefinery.com/forums/user/8841-elm/', 'images/wdr_default.png'),
(185, 'Peppe', 0.49, 92, 5, 1300939200, 0.1, 'http://webdevrefinery.com/forums/user/9030-peppe/', 'http://webdevrefinery.com/forums/uploads/av-9030.png?_r=0'),
(186, 'Xikeon', 0, 56, 0, 1304308800, 0.06, 'http://webdevrefinery.com/forums/user/9319-xikeon/', 'http://www.gravatar.com/avatar/470a96552a91f2e3a857d6ff92736d31?s=100'),
(187, 'Hermes', 0, 77, 0, 1304308800, 0.09, 'http://webdevrefinery.com/forums/user/9316-hermes/', 'http://www.gravatar.com/avatar/03067b9748db5ab2ee75a6197e524059?s=100'),
(188, 'WideBlade', 0.04, 39, 1, 1305777600, 0.04, 'http://webdevrefinery.com/forums/user/9404-wideblade/', 'images/wdr_default.png'),
(189, 'HeartLess', 0, 40, 0, 1308628800, 0.05, 'http://webdevrefinery.com/forums/user/9613-heartless/', 'images/wdr_default.png'),
(190, 'Cydrobolt', 0, 36, 0, 1314244800, 0.05, 'http://webdevrefinery.com/forums/user/9991-cydrobolt/', 'http://webdevrefinery.com/forums/uploads/av-9991.jpg?_r=0'),
(191, 'Struki', 0.13, 48, 2, 1318910400, 0.07, 'http://webdevrefinery.com/forums/user/10153-struki/', 'http://www.gravatar.com/avatar/e933bfec3c5de2c3f935a783800a0f86?s=100'),
(192, 'TheUnknown', 0, 48, 0, 1319169600, 0.07, 'http://webdevrefinery.com/forums/user/10162-theunknown/', 'http://www.gravatar.com/avatar/613cd37a4ac8cd3604544f544ed53312?s=100'),
(193, 'Nand', 0.36, 36, 6, 1330059600, 0.06, 'http://webdevrefinery.com/forums/user/13438-nand/', 'http://www.gravatar.com/avatar/7263892148ae1c4a7cff25de1f5dac28?s=100'),
(194, 'renewaltopics', 0, 53, 0, 1332648000, 0.09, 'http://webdevrefinery.com/forums/user/13769-renewaltopics/', 'images/wdr_default.png'),
(195, 'JCJeff', 0, 55, 0, 1334721600, 0.1, 'http://webdevrefinery.com/forums/user/14041-jcjeff/', 'http://webdevrefinery.com/forums/uploads/av-14041.png?_r=0'),
(196, 'Etisfo', 0.45, 101, 2, 1342843200, 0.23, 'http://webdevrefinery.com/forums/user/14852-etisfo/', 'http://webdevrefinery.com/forums/uploads/av-14852.png?_r=0'),
(197, 'Lluvia', 0.09, 40, 1, 1343966400, 0.09, 'http://webdevrefinery.com/forums/user/14886-lluvia/', 'http://webdevrefinery.com/forums/uploads/av-14886.jpg?_r=0'),
(198, 'goldenpages', 0, 47, 0, 1343966400, 0.11, 'http://webdevrefinery.com/forums/user/14889-goldenpages/', 'http://webdevrefinery.com/forums/uploads/profile/photo-14889.png?_r=0'),
(199, 'Ocifer', 1.24, 70, 7, 1347336000, 0.18, 'http://webdevrefinery.com/forums/user/15893-ocifer/', 'http://webdevrefinery.com/forums/uploads/av-15893.jpg?_r=0'),
(200, 'dgeehoms1', 0.27, 74, 1, 1358139600, 0.27, 'http://webdevrefinery.com/forums/user/18512-dgeehoms1/', 'http://www.gravatar.com/avatar/d9a2e81db5c4dba6bdcc7b1272b15515?s=100');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         