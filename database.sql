-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.8-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5930
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for gifgrabber
CREATE DATABASE IF NOT EXISTS `gifgrabber` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `gifgrabber`;

-- Dumping structure for table gifgrabber.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table gifgrabber.category: ~9 rows (approximately)
DELETE FROM `category`;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `created_at`, `updated_at`, `name`) VALUES
	(1, '2018-07-08 21:07:40', '2018-07-08 21:07:40', 'Motion'),
	(2, '2018-07-08 21:07:49', '2018-07-08 21:07:49', 'Energy'),
	(3, '2018-07-08 21:07:59', '2018-07-08 21:07:59', 'Force/Momentum'),
	(4, '2018-07-08 21:08:05', '2018-07-08 21:08:05', 'Waves'),
	(5, '2018-07-08 21:08:12', '2018-07-11 12:22:11', 'E&M'),
	(6, '2018-07-08 21:08:18', '2018-07-08 21:08:18', 'Space'),
	(7, '2018-07-11 12:22:07', '2018-07-11 12:22:07', 'Power'),
	(8, '2018-07-11 12:22:37', '2018-07-11 12:22:37', 'Failure'),
	(9, '2018-07-11 12:22:44', '2018-07-11 12:22:44', 'Success'),
	(25, '2020-04-02 23:38:26', '2020-04-02 23:38:26', 'Category 1');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Dumping structure for table gifgrabber.gif
CREATE TABLE IF NOT EXISTS `gif` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(13) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `file_type` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  UNIQUE KEY `title_category` (`title`,`category_id`) USING BTREE,
  KEY `gif_category` (`category_id`) USING BTREE,
  CONSTRAINT `gif_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table gifgrabber.gif: ~23 rows (approximately)
DELETE FROM `gif`;
/*!40000 ALTER TABLE `gif` DISABLE KEYS */;
INSERT INTO `gif` (`id`, `created_at`, `updated_at`, `category_id`, `title`, `caption`, `url`, `approved`, `file_type`) VALUES
	(1, '2018-07-10 07:15:38', '2020-04-03 01:10:43', 2, 'xkcd: Earth Temperature Timeline', '', 'https://xkcd.com/1732', 1, 'png'),
	(3, '2018-07-10 20:14:04', '2020-04-03 01:13:53', 3, 'Boat Stabilizer', 'How does this device use the Conservation of Momentum to keep boats steady?', 'https://www.reddit.com/r/EngineeringPorn/comments/8wk5e2/boat_stabiliser/', 1, 'mp4'),
	(4, '2018-07-10 20:14:42', '2020-04-02 13:58:52', 1, 'One Wheeled Biking', 'EGREGIOUSLY out of the bike lane, but mad skills!', 'https://imgur.com/p56yhlS', 1, 'mp4'),
	(5, '2018-07-10 20:17:29', '2020-04-02 14:20:24', 3, 'Rocket Fail', 'Not quite enough downward momentum here...', 'https://gfycat.com/UnhealthyGenerousKomododragon/', 1, 'mp4'),
	(6, '2018-07-10 20:19:16', '2020-04-02 14:18:34', 2, 'Backyard Roller Coaster', 'Where does does the energy come from? Who does the work?', 'https://gfycat.com/FirmHastyElephant/', 1, 'mp4'),
	(7, '2018-07-10 20:20:09', '2020-04-03 01:16:46', 3, 'Trying to jump in a short hallway', 'Watch his body, how does that show the property of inertia?', 'https://www.reddit.com/r/gifs/comments/8t9tr7/in_house_long_jump', 1, 'mp4'),
	(8, '2018-07-10 20:20:49', '2020-04-02 14:21:24', 3, 'One armed Pushups', 'How does he balance himself?', 'https://gfycat.com/ClosedDefensiveBobolink', 1, 'mp4'),
	(9, '2018-07-10 20:21:29', '2020-04-02 14:16:20', 1, 'BB-8', 'What is the relative motion of the head vs the \'wheel?\'', 'https://gfycat.com/LankyInsidiousAndalusianhorse', 1, 'mp4'),
	(10, '2018-07-10 20:22:14', '2020-04-03 01:30:59', 4, 'Sad X-Ray Machine', 'Why would an X-Ray machine be sad?', 'https://www.reddit.com/r/mildlyinteresting/comments/8s57qx/portable_xray_machine_that_looks_like_its_having/', 1, 'jpeg'),
	(11, '2018-07-10 20:23:12', '2020-04-02 14:50:11', 7, 'Drone Burns Debris Off Powerline', 'What\'s the problem with debris on the powerline? Why wouldn\'t you just tear it down?', 'https://gfycat.com/TiredFixedGardensnake/', 1, 'mp4'),
	(12, '2018-07-10 20:26:27', '2020-04-02 14:16:59', 1, 'Army teamwork', 'Who is the most important person in this activity?', 'https://gfycat.com/RigidResponsibleBoa', 1, 'mp4'),
	(13, '2018-07-10 20:28:41', '2020-04-02 14:22:27', 3, 'missile through walls', 'Name 3 things that must be true about the materials and motion of the objects in this gif.', 'https://gfycat.com/disloyalradiantbluet', 1, 'mp4'),
	(14, '2018-07-10 20:30:55', '2020-04-03 01:12:39', 3, 'barn vs tornado', 'Use the term net force to explain what happened to this barn.', 'https://deadspin.com/scottish-golf-course-to-u-s-opens-windy-conditions-1826889294', 1, 'mp4'),
	(15, '2018-07-10 20:32:27', '2020-04-02 14:46:59', 4, 'confused cat water glass', 'Why is the cat confused and how does it pertain to waves?', 'https://i.imgur.com/m62WJLg', 1, 'mp4'),
	(16, '2018-07-10 20:34:09', '2020-04-02 14:23:42', 3, 'stuck w no seatbelt', 'What law explains what happened and how would a seatbelt have helped?', 'http://imgur.com/w5wyuXr', 1, 'mp4'),
	(17, '2018-07-10 20:35:24', '2020-04-03 01:48:32', 3, 'brick karate chop', 'Use Newton\'s 3 Laws to describe this interaction.', 'https://www.reddit.com/r/interestingasfuck/comments/8rbc7d/slow_motion_karate_chopping_a_cement_brick/', 1, 'gif'),
	(18, '2018-07-10 20:38:34', '2020-04-02 14:24:44', 3, 'iceberg crack', 'How is the iceberg being lifted out of the water?', 'https://i.imgur.com/lxrEG04', 1, 'mp4'),
	(24, '2018-07-10 20:44:01', '2020-04-02 14:25:43', 3, 'rock in truck', 'Use the term net force, to explain what happens here...what would need to be different for this to work?', 'http://gfycat.com/WarmheartedForkedAustraliansilkyterrier', 1, 'mp4'),
	(25, '2018-07-10 20:48:04', '2020-04-02 14:33:41', 3, 'champagne ricochet', 'How does the bottle change direction? Use Force in your explanation. ', 'https://gfycat.com/GloomyUniformDobermanpinscher/', 1, 'mp4'),
	(26, '2018-07-10 20:50:00', '2020-04-02 14:35:19', 3, 'centripetal rocket', 'How does this work? Give evidence using the conservation of momentum and/or a discussion of net force.', 'https://i.imgur.com/bR0SHlB', 1, 'mp4'),
	(27, '2018-07-10 20:51:32', '2020-04-02 14:36:53', 3, 'Dog diving board ', 'Why is the man so helplessly going into the water in this scenario? Use the terms support force and center of mass.', 'https://i.imgur.com/J3g0KCk', 1, 'mp4'),
	(28, '2018-07-11 00:34:01', '2020-04-02 14:44:36', 3, 'Blowing out candles Prank', 'What happens to the flour when the air goes in?', 'https://i.imgur.com/rRmiCPa', 1, 'mp4'),
	(34, '2018-09-02 02:55:31', '2020-04-02 14:45:41', 3, 'Kid discovers water hose', 'Why do you think he turned it towards himself?', 'http://gfycat.com/ImpureRepentantDinosaur', 0, 'mp4'),
	(52, '2020-04-02 23:43:13', '2020-04-03 00:41:14', 25, 'Gif 1', 'GIF Caption!', 'http://www.reddit.com/r/EngineeringPorn/comments/fu2lv7/persistense_of_vision/', 1, NULL);
/*!40000 ALTER TABLE `gif` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
