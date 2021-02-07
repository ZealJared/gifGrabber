-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.5.3-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.6013
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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table gifgrabber.category: ~12 rows (approximately)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
REPLACE INTO `category` (`id`, `created_at`, `updated_at`, `name`) VALUES
	(1, '2018-07-08 21:07:40', '2018-07-08 21:07:40', 'Motion'),
	(2, '2018-07-08 21:07:49', '2018-07-08 21:07:49', 'Energy'),
	(3, '2018-07-08 21:07:59', '2018-07-08 21:07:59', 'Force/Momentum'),
	(4, '2018-07-08 21:08:05', '2018-07-08 21:08:05', 'Waves'),
	(5, '2018-07-08 21:08:12', '2018-07-11 12:22:11', 'E&M'),
	(7, '2018-07-11 12:22:07', '2018-07-11 12:22:07', 'Power'),
	(8, '2018-07-11 12:22:37', '2018-07-11 12:22:37', 'Failure'),
	(9, '2018-07-11 12:22:44', '2018-07-11 12:22:44', 'Success'),
	(26, '2020-04-15 02:42:08', '2020-04-15 02:42:08', 'Maybe maybe maybe'),
	(27, '2020-04-15 03:12:04', '2020-04-15 03:12:04', 'Awwww'),
	(28, '2020-04-15 19:07:12', '2020-04-15 19:07:12', 'Engineering'),
	(29, '2020-06-16 21:09:20', '2020-06-16 21:09:20', 'Math in the real world');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Dumping structure for table gifgrabber.gif
CREATE TABLE IF NOT EXISTS `gif` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(13) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_category` (`title`,`category_id`) USING BTREE,
  UNIQUE KEY `url_category_id` (`url`,`category_id`),
  KEY `gif_category` (`category_id`) USING BTREE,
  CONSTRAINT `gif_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table gifgrabber.gif: ~135 rows (approximately)
/*!40000 ALTER TABLE `gif` DISABLE KEYS */;
REPLACE INTO `gif` (`id`, `created_at`, `updated_at`, `approved`, `title`, `caption`, `url`, `category_id`) VALUES
	(1, '2018-07-10 07:15:38', '2021-02-05 13:12:23', 1, 'xkcd: Earth Temperature Timeline', '', 'https://xkcd.com/1732/', 2),
	(3, '2018-07-10 20:14:04', '2021-02-05 13:21:40', 1, 'Boat Stabilizer', 'How does this device use the Conservation of Momentum to keep boats steady?', 'https://www.reddit.com/r/EngineeringPorn/comments/8wk5e2/boat_stabiliser/', 3),
	(4, '2018-07-10 20:14:42', '2021-02-05 12:42:17', 1, 'One Wheeled Biking', 'EGREGIOUSLY out of the bike lane, but mad skills!', 'https://imgur.com/p56yhlS', 1),
	(5, '2018-07-10 20:17:29', '2021-02-05 13:22:34', 1, 'Rocket Fail', 'Not quite enough downward momentum here...', 'https://gfycat.com/UnhealthyGenerousKomododragon/', 3),
	(6, '2018-07-10 20:19:16', '2021-02-05 13:12:39', 1, 'Backyard Roller Coaster', 'Where does does the energy come from? Who does the work?', 'https://gfycat.com/FirmHastyElephant/', 2),
	(7, '2018-07-10 20:20:09', '2021-02-05 13:23:00', 1, 'Trying to jump in a short hallway', 'Watch his body, how does that show the property of inertia?', 'https://www.reddit.com/r/gifs/comments/8t9tr7/in_house_long_jump/', 3),
	(8, '2018-07-10 20:20:49', '2021-02-05 13:23:44', 1, 'One armed Pushups', 'How does he balance himself?', 'http://gifopotamo.com/i/pin/2Fvw71CSP', 3),
	(9, '2018-07-10 20:21:29', '2021-02-05 12:42:28', 1, 'BB-8', 'What is the relative motion of the head vs the \'wheel?\'', 'https://imgur.com/gallery/TLMiZ4s/', 1),
	(10, '2018-07-10 20:22:14', '2021-02-05 15:35:52', 1, 'Sad X-Ray Machine', 'Why would an X-Ray machine be sad?', 'https://www.reddit.com/r/mildlyinteresting/comments/8s57qx/portable_xray_machine_that_looks_like_its_having/', 4),
	(11, '2018-07-10 20:23:12', '2021-02-05 15:41:42', 1, 'Drone Burns Debris Off Powerline', 'What\'s the problem with debris on the powerline? Why wouldn\'t you just tear it down?', 'https://gfycat.com/TiredFixedGardensnake', 7),
	(12, '2018-07-10 20:26:27', '2021-02-05 12:43:30', 1, 'Army teamwork', 'Who is the most important person in this activity?', 'https://gifer.com/en/40Vh', 1),
	(13, '2018-07-10 20:28:41', '2021-02-05 13:24:02', 1, 'missile through walls', 'Name 3 things that must be true about the materials and motion of the objects in this gif.', 'https://gfycat.com/disloyalradiantbluet/', 3),
	(14, '2018-07-10 20:30:55', '2021-02-05 13:27:01', 1, 'barn vs tornado', 'Use the term net force to explain what happened to this barn.', 'http://deadspin.com/scottish-golf-course-to-u-s-opens-windy-conditions-1826889294', 3),
	(15, '2018-07-10 20:32:27', '2021-02-05 15:36:08', 1, 'confused cat water glass', 'Why is the cat confused and how does it pertain to waves?', 'https://i.imgur.com/m62WJLg/', 4),
	(16, '2018-07-10 20:34:09', '2021-02-05 13:28:04', 1, 'stuck w no seatbelt', 'What law explains what happened and how would a seatbelt have helped?', 'http://imgur.com/w5wyuXr/', 3),
	(17, '2018-07-10 20:35:24', '2021-02-05 13:28:42', 1, 'brick karate chop', 'Use Newton\'s 3 Laws to describe this interaction.', 'https://www.reddit.com/r/interestingasfuck/comments/8rbc7d/slow_motion_karate_chopping_a_cement_brick/', 3),
	(18, '2018-07-10 20:38:34', '2021-02-05 13:28:58', 1, 'iceberg crack', 'How is the iceberg being lifted out of the water?', 'https://i.imgur.com/lxrEG04/', 3),
	(24, '2018-07-10 20:44:01', '2021-02-05 13:29:10', 1, 'rock in truck', 'Use the term net force, to explain what happens here...what would need to be different for this to work?', 'http://gfycat.com/WarmheartedForkedAustraliansilkyterrier/', 3),
	(25, '2018-07-10 20:48:04', '2021-02-05 13:29:35', 1, 'champagne ricochet', 'How does the bottle change direction? Use Force in your explanation. ', 'https://gfycat.com/GloomyUniformDobermanpinscher', 3),
	(26, '2018-07-10 20:50:00', '2021-02-05 13:29:54', 1, 'centripetal rocket', 'How does this work? Give evidence using the conservation of momentum and/or a discussion of net force.', 'https://i.imgur.com/bR0SHlB/', 3),
	(27, '2018-07-10 20:51:32', '2021-02-05 13:31:38', 1, 'Dog diving board ', 'Why is the man so helplessly going into the water in this scenario? Use the terms support force and center of mass.', 'https://imgur.com/WqKftSt', 3),
	(28, '2018-07-11 00:34:01', '2021-02-05 13:33:33', 1, 'Blowing out candles Prank', 'What happens to the flour when the air goes in?', 'https://i.imgur.com/rRmiCPa/', 3),
	(34, '2018-09-02 02:55:31', '2021-02-05 13:33:49', 0, 'Kid discovers water hose', 'Why do you think he turned it towards himself?', 'http://gfycat.com/ImpureRepentantDinosaur/', 3),
	(54, '2020-04-09 16:52:16', '2021-02-05 16:31:03', 1, 'Insane backwards basketball shot', NULL, 'https://i.imgur.com/cwCZ6Fc/', 9),
	(57, '2020-04-15 02:44:18', '2021-02-05 13:34:04', 1, 'unexpected "The mould effect" ', 'Look at the sum of the forces of each area of the curve independently to explain how this is happening', 'https://www.reddit.com/r/Damnthatsinteresting/comments/dkk3nw/the_mould_effect', 3),
	(59, '2020-04-15 02:45:56', '2021-02-05 13:35:24', 1, 'A different way to skydive', 'How are the "jumpers" going higher and lower?', 'https://gfycat.com/enragedappropriateblackandtancoonhound-oh-no-teamtm/', 3),
	(60, '2020-04-15 02:51:50', '2021-02-05 16:50:52', 1, 'Gorillas hiding from the rain', 'Nobody likes to get wet.', 'https://i.imgur.com/H9Fw1Ba', 26),
	(62, '2020-04-15 03:12:39', '2021-02-05 16:54:50', 1, 'Wave of Puppies waking up', 'Watch for the first puppy to wake up :)', 'https://www.reddit.com/r/BetterEveryLoop/comments/drvql3/these_puppies_waking_up_is_worth_watching_a_few', 27),
	(63, '2020-04-15 03:14:52', '2021-02-05 16:55:04', 1, 'Kid cries getting a puppy', NULL, 'https://i.imgur.com/q0CYqtz', 27),
	(65, '2020-04-15 03:21:07', '2021-02-05 15:43:53', 1, 'Dog misses treat', '"I thought I had it!"', 'https://www.reddit.com/r/aww/comments/dsqxam/what_a_emotional_roller_coaster', 8),
	(66, '2020-04-15 03:22:06', '2021-02-05 15:36:20', 1, '"Invisible" Shield', 'When light changes mediums, it refracts - how did they use that here?', 'https://gfycat.com/neardishonestiraniangroundjay/', 4),
	(67, '2020-04-15 03:24:35', '2021-02-05 13:12:50', 1, 'Double trampolining!', 'Describe the energy transfers and transformations. How does the moving car change the energy?', 'https://gfycat.com/grimyhideousballoonfish', 2),
	(68, '2020-04-15 03:25:52', '2021-02-05 16:51:04', 1, 'Cat saves kid', '"I got you" "I will help you"', 'https://www.reddit.com/r/AnimalsBeingBros/comments/dsr5p6/cat_saves_baby_that_escaped_from_her_crib_from', 26),
	(69, '2020-04-15 03:27:19', '2021-02-05 13:35:58', 1, 'Gyroscope in a box', 'Describe the equal and opposite reactions that are happening in the gyroscopic top. How could these be used to turn a space ship?', 'https://gfycat.com/basiclinedhoiho/', 3),
	(70, '2020-04-15 03:32:14', '2021-02-05 15:44:18', 1, 'Dog misses water', '"I can\'t do it" "Why does nothing change when I do the same thing again?"', 'https://www.reddit.com/r/funny/comments/dvoe51/god_damn_it_why_isnt_this_working', 8),
	(71, '2020-04-15 03:35:14', '2021-02-05 16:55:16', 1, 'Misbehaving kid monkey and mother', 'For when a kid is asking too many questions', 'https://gfycat.com/mediummediumhind-nature/', 27),
	(72, '2020-04-15 03:37:03', '2021-02-05 16:31:12', 1, 'Kid dances on swing', 'Sick moves!', 'https://gfycat.com/sarcasticgleamingassassinbug/', 9),
	(73, '2020-04-15 03:37:58', '2021-02-05 15:38:34', 1, 'Thermal imaging of faucet', 'This image is possible because a detector of infrared waves translates those signals into visible light waves', 'https://gfycat.com/vigilantsatisfiediaerismetalmark/', 4),
	(74, '2020-04-15 03:40:33', '2021-02-05 13:38:00', 1, 'Indoor Skydiver Shows off', 'How is he using Newton\'s 3rd Law to do these tricks? Do you think the air is changing?', 'https://www.reddit.com/r/nextfuckinglevel/comments/dtrxbr/my_ifly_instructor_showing_off', 3),
	(76, '2020-04-15 18:43:10', '2021-02-05 16:52:04', 1, 'Yak Flip', 'I tried, I messed up, but it worked out!', 'https://www.reddit.com/r/NatureIsFuckingLit/comments/e0xdz8/can_a_1000_kilo_yak_do_a_flip_yakflip', 26),
	(77, '2020-04-15 18:44:37', '2021-02-05 12:50:56', 1, 'Racing in T-rex costumes', 'What do you notice about the winners? When is there acceleration and when are they moving at constant velocity?', 'https://i.imgur.com/vz44aVj', 1),
	(78, '2020-04-15 18:47:27', '2021-02-05 16:31:21', 1, 'Skydiver lands on motorcycle', '"Nailed it!"', 'https://gfycat.com/kaleidoscopicimaginarybobwhite/', 9),
	(79, '2020-04-15 18:48:10', '2021-02-05 13:13:03', 1, 'Trampoline double-bouncing tricks', 'How does the jumper gain energy? Where does that energy come from?', 'https://gfycat.com/talkativefarflungjumpingbean', 2),
	(80, '2020-04-15 18:51:49', '2021-02-05 13:38:37', 1, 'Water cancels ball\'s momentum', 'Which do you think has more mass: the ball or the water that it hits? If that\'s the case, which one is going faster?', 'https://www.reddit.com/r/YasuoMains/comments/dxpxvl/when_u_learn_how_to_windwall_in_real_life', 3),
	(81, '2020-04-15 18:55:13', '2021-02-05 13:43:57', 1, 'Moonwalk dancing', 'sound makes a huge difference on this one!', 'https://www.instagram.com/p/B5itsbFIpyW/', 3),
	(83, '2020-04-15 18:56:54', '2021-02-05 12:51:09', 1, 'Any-terrain Car', 'How does the person stay in the same position all the time? Use "Relative motion" in your answer', 'https://i.imgur.com/AiGlkjV', 1),
	(84, '2020-04-15 19:01:10', '2021-02-05 13:44:22', 1, 'Air powered motion', ' Do you think the fans are changing the speed of the air or does the air resistance of the cloths change? Why?', 'https://www.reddit.com/r/nextfuckinglevel/comments/e5d0ll/in_my_local_mall_i_thought_it_was_so_beautiful', 3),
	(85, '2020-04-15 19:02:31', '2021-02-05 15:38:45', 1, 'Resonance Buildings Earthquake', 'Why are only some buildings destroyed during an earthquake?', 'https://gfycat.com/greencharmingduckbillplatypus/', 4),
	(86, '2020-04-15 19:04:05', '2021-02-05 15:30:46', 1, 'Safety bar fails on ride', 'What happens to you if you don\'t wear your seatbelt?', 'https://www.reddit.com/r/CatastrophicFailure/comments/e56o8l/a_safety_bar_on_an_amusement_ride_fails_and_5/', 3),
	(88, '2020-04-15 19:06:07', '2021-02-05 15:40:30', 1, 'Failed Edison Bulb', 'What happens when a light bulb burns out', 'https://gfycat.com/caringeveryjohndory/', 5),
	(89, '2020-04-15 19:07:54', '2021-02-05 16:57:13', 1, 'Building cardboard missile launcher', 'What engineering steps are evident in this gif? ', 'https://www.reddit.com/r/nextfuckinglevel/comments/e9whvs/cardboard_missile_launcher', 28),
	(90, '2020-04-15 20:26:53', '2021-02-05 15:45:19', 1, 'Biking on Ice', 'What changed about the "Normal Force" as he biked?', 'https://i.imgur.com/A9rAMaG', 8),
	(91, '2020-04-15 23:43:53', '2021-02-05 12:51:20', 1, 'Motion-stabilized Star Trek', 'How is this an example of relative motion?', 'https://i.imgur.com/hZNHKUS', 1),
	(92, '2020-04-16 02:22:30', '2021-02-05 16:31:35', 1, 'Firework Dancing', NULL, 'https://www.reddit.com/r/nextfuckinglevel/comments/e8sgah/the_most_lit_dancer', 9),
	(93, '2020-04-16 02:23:17', '2021-02-05 15:45:35', 1, 'Doesn\'t jump...like at all', '"You gave up before you tried!"', 'https://gfycat.com/decimalmindlesschuckwalla-jumpers-world-trackandfield-whosondeck/', 8),
	(94, '2020-04-16 02:24:34', '2021-02-05 15:45:56', 1, 'Truck causes huge accident', 'When one student gets everyone else off track', 'https://www.reddit.com/r/CatastrophicFailure/comments/e8wvbv/truck_veers_onto_other_side_of_road_causing_chain', 8),
	(95, '2020-04-17 18:40:00', '2021-02-05 15:32:18', 1, '\'Tablecloth trick\' on face', 'What law explains why the other things stay in place when the cloth is removed? What must be true about the friction between the cloth and the glass and glasses?', 'https://www.reddit.com/r/nextfuckinglevel/comments/e8ujov/the_table_cloth_face_trick_is_spot_on/', 3),
	(96, '2020-04-17 18:40:51', '2021-02-05 16:55:26', 1, 'Cat cares for puppies', '(long) "when someone finds their purpose from helping others"', 'https://www.reddit.com/r/AnimalsBeingBros/comments/e8s624/love_knows_no_boundaries', 27),
	(97, '2020-04-17 18:43:03', '2021-02-05 15:41:53', 1, 'Transporting Wind Turbine Blade', 'Why is this turbine blade so big? What do you notice about how they completed this maneuver? ', 'https://i.imgur.com/HCnacT0', 7),
	(99, '2020-04-17 18:45:22', '2021-02-05 15:46:31', 1, 'Thief puts loot in a pick-up truck', '"When your deception is so obvious that it\'s stupid"', 'https://i.imgur.com/Q9EIPmb', 8),
	(100, '2020-04-17 18:57:04', '2021-02-05 15:49:24', 1, 'Truck too heavy for road', '"When you take on too much pressure" "When I try to do everything"', 'https://www.reddit.com/r/Whatcouldgowrong/comments/ebu306/if_i_drive_on_an_unstable_road_with_a_heavily', 8),
	(101, '2020-04-17 18:59:04', '2021-02-05 16:52:22', 1, 'Dog breaks up chicken fight', '"Do I need to separate you two?"', 'https://i.imgur.com/8vWlPqk', 26),
	(102, '2020-04-17 19:01:10', '2021-02-05 16:52:35', 1, 'Snow kayaking', '"you didn\'t take the normal route, but you got there eventually..."', 'https://www.reddit.com/r/sports/comments/eapdlk/this_looks_like_a_fun_way_to_die', 26),
	(103, '2020-04-17 19:02:18', '2021-02-05 15:50:08', 1, 'Kid runs into many clear windows', '"Keep trying, but maybe a little more carefully..."', 'https://www.reddit.com/r/Whatcouldgowrong/comments/ea3kkt/when_we_clean_the_windows_too_well', 8),
	(104, '2020-04-17 19:03:50', '2020-04-17 19:04:33', 1, 'Car\'s Pedestrian Catcher', 'Old school solution to cars hitting people. What sub-problem does this solution address? What are some problems with it?', 'https://gfycat.com/forthrightcolorlessagouti-invention-literature-subject-physical-exercise-interest', 28),
	(105, '2020-04-17 19:05:27', '2020-04-17 19:05:27', 1, 'Helmet with heads-up display', 'What problem(s) does this helmet solve? What are some potential issues with this prototype?', 'https://i.imgur.com/gLocZd8.gifv', 28),
	(106, '2020-04-17 19:11:31', '2021-02-05 16:52:48', 1, 'Geese go out in cold, change minds', '"When everybody is ready to do something and then all decide to bail"', 'https://www.reddit.com/r/funny/comments/ecwj0y/too_honkin_cold', 26),
	(108, '2020-04-17 19:16:32', '2021-02-05 16:53:03', 1, 'Fetch: the lazy way', '"Doing the bare minimum"', 'https://gfycat.com/fluffyjadedbrocketdeer-dog/', 26),
	(109, '2020-04-17 19:17:17', '2021-02-05 13:13:47', 1, 'Red hot making ball bearings', '"Why are these ball bearings glowing red?"', 'https://gfycat.com/brilliantheartfelthammerheadshark', 2),
	(110, '2020-04-17 19:19:49', '2021-02-05 13:14:00', 1, 'Kid launched by blob', 'Explain the energy transfers necessary for this kid to go so high up:', 'https://gfycat.com/inconsequentialgentleheron', 2),
	(111, '2020-04-17 19:21:20', '2021-02-05 15:40:00', 1, 'Tuning Fork Resonance', 'Why does the ping pong ball move? What must be true about the two blocks? ', 'https://www.reddit.com/r/Damnthatsinteresting/comments/eiduvf/sound_waves_travelling/', 4),
	(112, '2020-04-17 19:23:51', '2021-02-05 15:32:42', 1, 'Ironman-style Jetpack', 'What must be true about the air pushed downwards from his arms? What would happen if he lifted his arms upward?', 'https://gfycat.com/politicalseparatecygnet/', 3),
	(113, '2020-04-17 19:24:27', '2021-02-05 16:55:46', 1, 'Baby tiger scares mom', NULL, 'https://gfycat.com/maturefixedgrassspider-cats/', 27),
	(114, '2020-04-17 19:26:14', '2021-02-05 12:51:44', 1, 'Skydiver Unexpected Result', 'Why were you surprised when the skydiver ended up being tiny? (Relative motion/perspective)', 'https://gfycat.com/beneficialcarelessbarnowl', 1),
	(115, '2020-04-17 19:26:41', '2021-02-05 15:50:52', 1, 'Launched by bean bag', 'When someone messes someone else up', 'https://www.reddit.com/r/funny/comments/ek3tej/girl_gets_launched_by_a_bean_bag', 8),
	(116, '2020-04-17 19:27:31', '2021-02-05 15:43:14', 1, 'Wind turbine fail', 'Article: https://www.fox5ny.com/news/recently-installed-wind-turbine-collapses-in-the-bronx', 'https://www.reddit.com/r/CatastrophicFailure/comments/ek43id/wind_turbine_collapses_destroys_a_billboard_and', 7),
	(117, '2020-04-17 19:28:32', '2021-02-05 15:51:21', 1, 'Doesn\'t let go of zipline', '"At some point, you have to go for it, even if it\'s scary to let go"', 'https://www.reddit.com/r/BetterEveryLoop/comments/ek7gzi/kid_riding_a_zipline', 8),
	(118, '2020-04-17 19:29:30', '2021-02-05 12:51:54', 1, 'Cat confused by treadmill', 'Why does the cat not expect this? How do treadmills work in terms of relative motion?', 'https://i.imgur.com/ROW4Wpg', 1),
	(119, '2020-04-17 19:30:36', '2021-02-05 16:31:53', 1, 'Bowling Double strike', '"You got both parts of this!"', 'https://www.reddit.com/r/nextfuckinglevel/comments/ejrnzw/this_bowling_trick_shot_is_amazing', 9),
	(120, '2020-04-17 19:32:10', '2021-02-05 16:33:31', 1, 'Fish gets berry above water', '"you reached for it and you got it!"', 'https://www.reddit.com/r/NatureIsFuckingLit/comments/ejv8y8/piraputanga_jumps_out_of_water_to_pick_fruit_off', 9),
	(121, '2020-04-17 19:34:49', '2021-02-05 16:53:14', 1, 'Jumping to avoid car accident', '"When you save it at the last minute"', 'https://www.reddit.com/r/Unexpected/comments/eptj2q/this_video_is_not_for_the_fainthearted', 26),
	(122, '2020-04-17 19:35:44', '2021-02-05 15:40:43', 1, 'Electricity "fight"', 'How does this work? Look up Tesla Coils and Faraday Cages', 'https://www.reddit.com/r/nextfuckinglevel/comments/epykbz/these_guys_fighting_it_out_with_electricity', 5),
	(123, '2020-04-17 19:36:59', '2021-02-05 15:51:49', 1, 'Car hits pole', '"You didn\'t read the instructions" "Complete unawareness"', 'https://www.reddit.com/r/facepalm/comments/ep044k/out_of_nowhere', 8),
	(124, '2020-04-17 19:38:06', '2021-02-05 16:47:26', 1, 'Cat Flip', '"Good recognition of a potential problem there!"', 'https://skullsinthestars.files.wordpress.com/2020/01/catfliplong.gif', 9),
	(125, '2020-04-17 19:40:01', '2021-02-05 15:58:48', 1, 'Uno read the directions', 'Students re: directions', 'https://i.redd.it/7m3dm0scst941.jpg', 8),
	(126, '2020-04-17 19:43:33', '2021-02-05 15:59:03', 1, 'Cat on rotating chair', '"When you write a lot but say nothing"', 'https://i.imgur.com/FXlnzST', 8),
	(127, '2020-04-17 19:44:53', '2021-02-05 16:55:55', 1, 'Puppy plays with baby birds', '"When the bigger kid plays nice"', 'https://gfycat.com/halfwholeeeve/', 27),
	(128, '2020-04-17 19:46:20', '2021-02-05 15:59:17', 1, 'Tries to save it, doesn\'t', '"When you concentrate on the wrong part of the problem" "When you try but miss the point"', 'https://www.reddit.com/r/Unexpected/comments/ekabr5/dad_reflexes', 8),
	(129, '2020-04-17 19:47:45', '2021-02-05 15:33:52', 1, 'Delivery on Ice', 'How does the delivery person get this package up the hill? Where does he find "Normal force" rather than friction?', 'https://www.reddit.com/r/nextfuckinglevel/comments/ek31w2/dedication_of_this_ups_driver', 3),
	(130, '2020-04-24 20:08:47', '2021-02-05 16:01:13', 1, 'Travolta confused green screen', '"Where were you?" Can edit background!', 'https://giphy.com/gifs/nba-20k1punZ5bpmM', 8),
	(131, '2020-05-12 22:03:33', '2021-02-05 16:47:58', 1, 'Skateboarder eventually lands trick', 'Keep trying until you get it!', 'https://gfycat.com/clutteredwholeamberpenshell-skateboarding-jiemba-sands-funnyvideos/', 9),
	(132, '2020-05-21 06:49:47', '2021-02-05 15:34:10', 1, 'Car brakes on carpet', 'Describe the role of momentum and inertia in this interaction', 'https://i.imgur.com/zZMdmdn/', 3),
	(134, '2020-05-21 06:51:09', '2021-02-05 16:01:33', 1, 'Trying to pull yourself up by your bootstraps', 'Why doesn\'t this work?', 'https://www.reddit.com/r/funny/comments/esymjp/did_not_do_the_math', 8),
	(135, '2020-05-30 04:10:27', '2021-02-05 16:48:18', 1, 'Black Lives Matter', '(And so do all other parts of them)', 'https://tenor.com/view/black-lives-blackpeople-blacklivesmatter-gif-8493330', 9),
	(136, '2020-06-01 18:16:08', '2021-02-05 16:48:35', 1, 'Astronaut fist bump', 'Space X launch success', 'http://i.redd.it/lwj8frmbsy151.gif', 9),
	(137, '2020-06-01 18:18:11', '2021-02-05 16:53:44', 1, 'Octopus rides Eel', NULL, 'https://www.reddit.com/r/NatureIsFuckingLit/comments/gtf7rx/octopus_rides_moray_eel_to_avoid_its_attack', 26),
	(139, '2020-06-08 19:00:40', '2021-02-05 16:56:12', 1, 'Kid tries to absorb knowledge', '"When the test is in 2 minutes and you haven\'t studied"', 'https://gfycat.com/spiritedcorrupthusky/', 27),
	(140, '2020-06-08 19:02:25', '2021-02-05 16:56:31', 1, 'Dog Smile', '"When I see you in class"', 'https://www.reddit.com/r/aww/comments/gx2vwr/be_careful_smiles_are_contagious', 27),
	(142, '2020-06-16 20:06:13', '2021-02-05 15:34:31', 1, 'Crow Seesaw', 'Does the crow\'s mass change? What about its Force of Gravity? What does change?', 'https://gfycat.com/floweryzealousgossamerwingedbutterfly/', 3),
	(143, '2020-06-16 20:06:41', '2021-02-05 16:03:12', 1, 'Rubber band Watermelon', 'When it blows up in your face', 'https://imgur.com/AoJftsN', 8),
	(146, '2020-06-16 20:09:43', '2021-02-05 16:03:32', 1, 'Late attempt at eating', 'A very delayed response', 'https://i.imgur.com/Z0DA4NP', 8),
	(147, '2020-06-16 20:11:01', '2021-02-05 16:53:59', 1, 'Strength vs flexibility', 'Strength isn\'t everything', 'https://www.reddit.com/r/Wellthatsucks/comments/eqvttm/itchy_sticker_always_wins_against_big_muscle_guy', 26),
	(148, '2020-06-16 20:11:37', '2021-02-05 12:52:05', 1, 'Pencil "up nose"', 'Perspective and frame of reference are crucial!', 'https://i.imgur.com/B6yYBHM', 1),
	(149, '2020-06-16 20:13:20', '2021-02-05 12:52:17', 1, 'Pool table on a cruise ship', 'Why don\'t the pool balls move when the ship moves? What is the pool table moving in comparison to?', 'https://gfycat.com/meekmarveloushatchetfish-amazing', 1),
	(150, '2020-06-16 20:15:02', '2021-02-05 16:03:44', 1, 'Head against wall series', 'When you keep making the same mistake over and over...', 'https://imgur.com/Szxgs1t/', 8),
	(151, '2020-06-16 20:16:06', '2021-02-05 16:48:49', 1, '3 straight lucky pool shots', 'Better lucky than good', 'https://i.imgur.com/cwmN1KM', 9),
	(152, '2020-06-16 20:18:32', '2021-02-05 16:04:05', 1, 'Baby chimp tries to look tough', 'Doesn\'t always go well when you first start!', 'https://gfycat.com/abandonedsardonicarcticwolf-nature/', 8),
	(153, '2020-06-16 20:22:08', '2021-02-05 15:40:13', 1, 'Light through water', 'What does the water do to light? What do the clear balls do?', 'https://i.imgur.com/PFHXmPf/', 4),
	(155, '2020-06-16 20:24:07', '2021-02-05 16:04:16', 1, 'Pole jumping over a creek', 'If at first you don\'t succeed...', 'https://i.imgur.com/219gtvd', 8),
	(156, '2020-06-16 20:26:53', '2021-02-05 13:03:45', 1, 'Plane appears to stand still', 'How is the visible plane moving relative to the ground? How is it moving relative to plane that\'s filming? (get it, PLANE?)', 'https://www.reddit.com/r/confusing_perspective/comments/f0t3hu/two_planes_traveling_but_one_is_slightly_slower/', 1),
	(157, '2020-06-16 20:28:10', '2021-02-05 16:04:31', 1, 'Twins both fall', 'A bad group project', 'https://www.reddit.com/r/aww/comments/f0uhtr/an_epic_battle_between_my_twins', 8),
	(158, '2020-06-16 20:29:21', '2021-02-05 13:11:36', 1, 'Train motion', 'Does the train change speeds? What does change speeds?', 'https://i.imgur.com/cskW5TY', 1),
	(159, '2020-06-16 20:30:32', '2021-02-05 13:11:57', 1, 'Low budget film tricks', 'How do some of these tricks take advantage of frame of reference?', 'https://i.imgur.com/oRdrFYp', 1),
	(160, '2020-06-16 20:33:19', '2021-02-05 16:04:59', 1, 'Two friends fall off a sign', 'A friend who drags you down', 'https://gfycat.com/felinecautiousamoeba/', 8),
	(161, '2020-06-16 20:34:43', '2021-02-05 15:34:55', 1, 'Prank: snowmobile switched to reverse', 'Describe this situation using the term inertia', 'https://www.reddit.com/r/funny/comments/f7wfkx/get_put_in_reverse', 3),
	(162, '2020-06-16 20:36:41', '2021-02-05 16:56:48', 1, 'Animal jumps for treat', 'Anybody know what animal this is?', 'https://i.imgur.com/2P7lOp2', 27),
	(164, '2020-06-16 20:52:24', '2020-06-16 20:52:24', 1, 'Phone Protector', 'What problem does this solve? How does it solve it? Use the words impulse, force, and time in your answer', 'https://www.reddit.com/r/nextfuckinglevel/comments/f6hbz6/this_device_deploys_to_protect_your_phone_if_you/', 28),
	(165, '2020-06-16 20:53:35', '2021-02-05 16:48:59', 1, 'Bowling from a car', 'Does the ball go faster than the car? Explain how that\'s possible', 'https://i.imgur.com/rMalTGZ', 9),
	(166, '2020-06-16 20:55:15', '2021-02-05 16:05:12', 1, 'Two people rope balancing', 'When a group project goes wrong', 'https://www.reddit.com/r/Whatcouldgowrong/comments/f2zzct/wcgw_if_i_balance_on_a_rope_above_water_with', 8),
	(167, '2020-06-16 20:55:58', '2021-02-05 16:49:14', 1, 'Sweet alley-oop', 'When a group project goes right!', 'https://gfycat.com/disastrousphysicalcalf/', 9),
	(168, '2020-06-16 20:57:11', '2021-02-05 13:14:14', 1, 'Resistance Band Fail', 'What happens to the force on the object as the person stretches twice as far? What happens to the energy? ', 'https://www.reddit.com/r/Whatcouldgowrong/comments/fe4o5d/wccgw_if_my_resistance_band_isnt_secured', 2),
	(169, '2020-06-16 20:59:04', '2021-02-05 16:49:23', 1, 'Kite surfing from a window', NULL, 'https://gfycat.com/colorlesssimplecapybara-kitesurfing/', 9),
	(170, '2020-06-16 21:00:00', '2021-02-05 16:05:30', 1, 'Forgot to put parking brake on', 'When you don\'t notice how things are going wrong...', 'https://www.reddit.com/r/Wellthatsucks/comments/fdddrz/forgetting_to_put_handbrake_on', 8),
	(171, '2020-06-16 21:00:34', '2021-02-05 13:17:50', 1, 'Sink under thermal camera', NULL, 'https://www.reddit.com/r/brockhampton/comments/all8pu/matt_is_really_blowing_up', 2),
	(172, '2020-06-16 21:03:42', '2021-02-05 15:35:32', 1, 'Wind carries branch', NULL, 'https://www.reddit.com/r/maybemaybemaybe/comments/fcsfjr/maybe_maybe_maybe', 3),
	(173, '2020-06-16 21:04:32', '2020-06-16 21:04:32', 1, '18th century "robot" writes', NULL, 'https://i.imgur.com/c3hqweD.gifv', 28),
	(175, '2020-06-16 21:06:09', '2021-02-05 16:49:35', 1, 'Two models dancing together', 'When you\'re with a friend for a group project', 'https://www.reddit.com/r/oddlysatisfying/comments/fik35b/i_love_everything_about_this', 9),
	(176, '2020-06-16 21:06:45', '2021-02-05 16:06:36', 1, 'Travolta meme claymation', 'Where\'s the assignment/answer?', 'http://i.redd.it/b0whwfgin0m41.gif', 8),
	(177, '2020-06-16 21:08:10', '2021-02-05 13:18:25', 1, 'Pendulum with extra energy', 'Why is this so scary/dangerous? What would have happened, and how did the energy change?', 'https://www.reddit.com/r/trashy/comments/fggc1m/respect_to_the_girl/', 2),
	(178, '2020-06-16 21:09:47', '2020-06-16 21:09:47', 1, 'Pizza Pi representation', 'How many pizza diameters was the crust?', 'https://gfycat.com/soulfuldigitaldegu-pizza', 29),
	(179, '2020-06-16 21:10:53', '2021-02-05 16:54:33', 1, 'Diver denies Shark', NULL, 'https://i.imgur.com/QY6n27R', 26),
	(180, '2020-06-16 21:11:49', '2021-02-05 16:06:52', 1, 'Kid gets worked by spinning thing', 'When you just can\'t figure it out...', 'https://www.reddit.com/r/funny/comments/flp6x3/basically_2020_so_far', 8),
	(182, '2020-06-26 22:45:27', '2021-02-05 16:30:42', 1, 'Office Space Destruction', 'Technology Frustration', 'https://tenor.com/view/office-space-gif-5094406/', 8),
	(183, '2020-06-26 22:52:58', '2021-02-05 16:50:35', 1, 'Crazy Game-tying shot', 'Luka Doncic against the Blazers', 'https://gfycat.com/keendeterminedboto/', 9);
/*!40000 ALTER TABLE `gif` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
