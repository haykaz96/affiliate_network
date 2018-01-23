-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2018 at 02:08 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `affiliate_network`
--

-- --------------------------------------------------------

--
-- Table structure for table `uc_affiliate_permission_offer_matches`
--

CREATE TABLE `uc_affiliate_permission_offer_matches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `promotion_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `offer_id` int(11) NOT NULL,
  `ip_address` varchar(16) COLLATE latin1_general_ci NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `uc_affiliate_permission_offer_matches`
--

INSERT INTO `uc_affiliate_permission_offer_matches` (`id`, `user_id`, `status`, `promotion_notes`, `offer_id`, `ip_address`, `date_time`) VALUES
(1010, 1000, 3, '4\r\n', 1021, '127.0.0.1', '2018-01-18 17:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `uc_affiliate_permission_offer_status`
--

CREATE TABLE `uc_affiliate_permission_offer_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_affiliate_permission_offer_status`
--

INSERT INTO `uc_affiliate_permission_offer_status` (`id`, `name`) VALUES
(1, 'Rejected'),
(2, 'Pending Approval'),
(3, 'Approved'),
(4, 'Public');

-- --------------------------------------------------------

--
-- Table structure for table `uc_banner_creatives`
--

CREATE TABLE `uc_banner_creatives` (
  `id` int(10) NOT NULL,
  `banner_creatives_type_id` int(11) NOT NULL,
  `offer_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `banner_id` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `banner_status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uc_banner_creatives_status`
--

CREATE TABLE `uc_banner_creatives_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_banner_creatives_status`
--

INSERT INTO `uc_banner_creatives_status` (`id`, `name`) VALUES
(1, 'Rejected'),
(2, 'Pending Approval'),
(3, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `uc_banner_creatives_type`
--

CREATE TABLE `uc_banner_creatives_type` (
  `id` int(11) NOT NULL,
  `banner_type` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_banner_creatives_type`
--

INSERT INTO `uc_banner_creatives_type` (`id`, `banner_type`) VALUES
(1, '728x90'),
(2, '468x60'),
(3, '336x280'),
(4, '300x250'),
(5, '250x250'),
(6, '240x400'),
(7, '234x60'),
(8, '180x150'),
(9, '160x600'),
(10, '125x125'),
(11, '120x600'),
(12, '120x240'),
(13, '120x90');

-- --------------------------------------------------------

--
-- Table structure for table `uc_clicks`
--

CREATE TABLE `uc_clicks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `offer_id` int(11) NOT NULL,
  `sub_id` varchar(12) NOT NULL,
  `sub_id_1` varchar(12) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ip_address` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_clicks`
--

INSERT INTO `uc_clicks` (`id`, `user_id`, `date_time`, `offer_id`, `sub_id`, `sub_id_1`, `status`, `ip_address`) VALUES
(1059, 1000, '2018-01-20 13:48:25', 1000, '', '', 2, '127.0.0.1'),
(1051, 1000, '2018-01-18 18:29:30', 1000, '', '', 2, '127.0.0.1'),
(1031, 1001, '2017-12-30 08:17:18', 1001, '20', '', 2, '123.456.789'),
(1030, 1000, '2017-12-29 05:13:00', 1000, '10', '', 2, '123.365.589'),
(1028, 1001, '2017-12-27 08:18:00', 1000, 'Sub1', '', 2, '123.35.258'),
(1027, 1000, '2017-12-26 07:35:00', 1001, 'Sub1', '', 2, '123.35.258'),
(1057, 1000, '2018-01-20 12:39:03', 1000, '', '', 2, '127.0.0.1'),
(1025, 1001, '2017-12-08 08:22:22', 1000, 'Sub1', '', 2, '123.35.258'),
(1058, 1000, '2018-01-20 13:47:00', 1000, '', '', 2, '127.0.0.1'),
(1060, 1000, '2018-01-20 14:06:15', 1020, '', '', 2, '127.0.0.1'),
(1061, 1001, '2018-01-20 14:06:17', 1020, '12345678910', '', 2, '127.045.023.1212'),
(1062, 1000, '2018-01-20 14:06:36', 1020, '12345678910', '', 2, '127.0.0.1'),
(1064, 1000, '2018-01-21 19:45:59', 1020, '', '', 1, '127.0.0.1'),
(1065, 1000, '2018-01-21 19:46:47', 1021, '', '', 1, '127.0.0.1'),
(1053, 1000, '2018-01-18 18:44:22', 1000, '', '', 2, '127.0.0.1'),
(1054, 1000, '2018-01-18 18:44:35', 1000, '5432', '', 2, '127.0.0.1'),
(1055, 1000, '2018-01-18 18:45:57', 1000, '', '', 2, '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `uc_clicks_status`
--

CREATE TABLE `uc_clicks_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_clicks_status`
--

INSERT INTO `uc_clicks_status` (`id`, `name`) VALUES
(1, 'Rejected'),
(2, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `uc_configuration`
--

CREATE TABLE `uc_configuration` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_configuration`
--

INSERT INTO `uc_configuration` (`id`, `name`, `value`) VALUES
(1, 'website_name', 'Affiliate Network Script'),
(2, 'website_url', 'http://localhost/affiliate/'),
(3, 'email', 'contact@websitehome.co.uk'),
(4, 'activation', 'true'),
(5, 'resend_activation_threshold', '0'),
(6, 'language', 'models/languages/en.php'),
(7, 'template', 'models/site-templates/style.css'),
(8, 'reject_url', 'http://localhost/affiliate/reject.html'),
(9, 'trk_url', 'http://localhost/affiliate/trk/'),
(10, 'geolocate_token', 'will12345'),
(11, 'geolocate_url', '#');

-- --------------------------------------------------------

--
-- Table structure for table `uc_conversions`
--

CREATE TABLE `uc_conversions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `offer_id` int(11) NOT NULL,
  `advertiser_cost` decimal(10,2) NOT NULL,
  `affiliate_payout` decimal(10,2) NOT NULL,
  `sub_id` varchar(12) NOT NULL,
  `sub_id_1` varchar(12) NOT NULL,
  `transaction_id` varchar(30) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ip_address` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_conversions`
--

INSERT INTO `uc_conversions` (`id`, `user_id`, `date_time`, `offer_id`, `advertiser_cost`, `affiliate_payout`, `sub_id`, `sub_id_1`, `transaction_id`, `status`, `ip_address`) VALUES
(13, 1000, '2018-01-04 06:14:31', 1021, 20.00, 16.00, '0', '0', 'Today', 3, '12.25.36.11'),
(2, 1000, '2017-12-25 09:28:31', 1000, 20.00, 16.00, '0', '0', '0', 2, '123.456.78'),
(11, 1000, '2018-01-03 10:38:31', 1020, 20.00, 16.00, '0', '0', 'Yest', 3, '12.25.36.11'),
(14, 1000, '2018-01-07 06:14:31', 1000, 20.00, 16.00, '0', '0', 'Today', 3, '12.25.36.11'),
(15, 1000, '2018-01-08 06:14:31', 1020, 20.00, 16.00, '0', '0', 'Today', 3, '12.25.36.11');

-- --------------------------------------------------------

--
-- Table structure for table `uc_conversions_status`
--

CREATE TABLE `uc_conversions_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_conversions_status`
--

INSERT INTO `uc_conversions_status` (`id`, `name`) VALUES
(1, 'Invalid'),
(2, 'Delivered'),
(3, 'Confirmed'),
(4, 'Re-Validated');

-- --------------------------------------------------------

--
-- Table structure for table `uc_countries`
--

CREATE TABLE `uc_countries` (
  `id` int(11) NOT NULL,
  `country_code` char(2) NOT NULL,
  `country_name` varchar(64) NOT NULL,
  `full_name` varchar(128) NOT NULL,
  `iso_3_code` char(3) NOT NULL,
  `numeric_code` smallint(3) UNSIGNED ZEROFILL NOT NULL,
  `continent_code` char(2) NOT NULL,
  `display_order` int(3) NOT NULL DEFAULT '900'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `uc_countries`
--

INSERT INTO `uc_countries` (`id`, `country_code`, `country_name`, `full_name`, `iso_3_code`, `numeric_code`, `continent_code`, `display_order`) VALUES
(1, 'AD', 'Andorra', 'Principality of Andorra', 'AND', 020, 'EU', 7),
(2, 'AE', 'United Arab Emirates', 'United Arab Emirates', 'ARE', 784, 'AS', 232),
(3, 'AF', 'Afghanistan', 'Islamic Republic of Afghanistan', 'AFG', 004, 'AS', 3),
(4, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', 'ATG', 028, 'NA', 11),
(5, 'AI', 'Anguilla', 'Anguilla', 'AIA', 660, 'NA', 9),
(6, 'AL', 'Albania', 'Republic of Albania', 'ALB', 008, 'EU', 4),
(7, 'AM', 'Armenia', 'Republic of Armenia', 'ARM', 051, 'AS', 13),
(8, 'AN', 'Antilles (Netherlands)', 'Netherlands Antilles', 'ANT', 530, 'NA', 157),
(9, 'AO', 'Angola', 'Republic of Angola', 'AGO', 024, 'AF', 8),
(10, 'AQ', 'Antarctica', 'Antarctica (the territory South of 60 deg S)', 'ATA', 010, 'AN', 10),
(11, 'AR', 'Argentina', 'Argentine Republic', 'ARG', 032, 'SA', 12),
(12, 'AS', 'American Samoa', 'American Samoa', 'ASM', 016, 'OC', 6),
(13, 'AT', 'Austria', 'Republic of Austria', 'AUT', 040, 'EU', 16),
(14, 'AU', 'Australia', 'Commonwealth of Australia', 'AUS', 036, 'OC', 15),
(15, 'AW', 'Aruba', 'Aruba', 'ABW', 533, 'NA', 14),
(16, 'AX', 'Aland', 'Aland Islands', 'ALA', 248, 'EU', 246),
(17, 'AZ', 'Azerbaijan', 'Republic of Azerbaijan', 'AZE', 031, 'AS', 17),
(18, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', 'BIH', 070, 'EU', 29),
(19, 'BB', 'Barbados', 'Barbados', 'BRB', 052, 'NA', 21),
(20, 'BD', 'Bangladesh', 'People\'s Republic of Bangladesh', 'BGD', 050, 'AS', 20),
(21, 'BE', 'Belgium', 'Kingdom of Belgium', 'BEL', 056, 'EU', 23),
(22, 'BF', 'Burkina Faso', 'Burkina Faso', 'BFA', 854, 'AF', 37),
(23, 'BG', 'Bulgaria', 'Republic of Bulgaria', 'BGR', 100, 'EU', 36),
(24, 'BH', 'Bahrain', 'Kingdom of Bahrain', 'BHR', 048, 'AS', 19),
(25, 'BI', 'Burundi', 'Republic of Burundi', 'BDI', 108, 'AF', 38),
(26, 'BJ', 'Benin', 'Republic of Benin', 'BEN', 204, 'AF', 25),
(27, 'BL', 'Saint Barthelemy', 'Saint Barthelemy', 'BLM', 652, 'NA', 185),
(28, 'BM', 'Bermuda', 'Bermuda', 'BMU', 060, 'NA', 26),
(29, 'BN', 'Brunei Darussalam', 'Brunei Darussalam', 'BRN', 096, 'AS', 35),
(30, 'BO', 'Bolivia', 'Republic of Bolivia', 'BOL', 068, 'SA', 28),
(31, 'BR', 'Brazil', 'Federative Republic of Brazil', 'BRA', 076, 'SA', 32),
(32, 'BS', 'Bahamas', 'Commonwealth of the Bahamas', 'BHS', 044, 'NA', 18),
(33, 'BT', 'Bhutan', 'Kingdom of Bhutan', 'BTN', 064, 'AS', 27),
(34, 'BV', 'Bouvet Island', 'Bouvet Island (Bouvetoya)', 'BVT', 074, 'AN', 31),
(35, 'BW', 'Botswana', 'Republic of Botswana', 'BWA', 072, 'AF', 30),
(36, 'BY', 'Belarus', 'Republic of Belarus', 'BLR', 112, 'EU', 22),
(37, 'BZ', 'Belize', 'Belize', 'BLZ', 084, 'NA', 24),
(38, 'CA', 'Canada', 'Canada', 'CAN', 124, 'NA', 2),
(39, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CCK', 166, 'AS', 48),
(40, 'CD', 'Congo (Kinshasa)', 'Democratic Republic of the Congo', 'COD', 180, 'AF', 51),
(41, 'CF', 'Central African Republic', 'Central African Republic', 'CAF', 140, 'AF', 43),
(42, 'CG', 'Congo (Brazzaville)', 'Republic of the Congo', 'COG', 178, 'AF', 52),
(43, 'CH', 'Switzerland', 'Swiss Confederation', 'CHE', 756, 'EU', 214),
(44, 'CI', 'Cote d\'Ivoire', 'Republic of Cote d\'Ivoire', 'CIV', 384, 'AF', 55),
(45, 'CK', 'Cook Islands', 'Cook Islands', 'COK', 184, 'OC', 53),
(46, 'CL', 'Chile', 'Republic of Chile', 'CHL', 152, 'SA', 45),
(47, 'CM', 'Cameroon', 'Republic of Cameroon', 'CMR', 120, 'AF', 40),
(48, 'CN', 'China', 'People\'s Republic of China', 'CHN', 156, 'AS', 46),
(49, 'CO', 'Colombia', 'Republic of Colombia', 'COL', 170, 'SA', 49),
(50, 'CR', 'Costa Rica', 'Republic of Costa Rica', 'CRI', 188, 'NA', 54),
(51, 'CU', 'Cuba', 'Republic of Cuba', 'CUB', 192, 'NA', 57),
(52, 'CV', 'Cape Verde', 'Republic of Cape Verde', 'CPV', 132, 'AF', 41),
(53, 'CX', 'Christmas Island', 'Christmas Island', 'CXR', 162, 'AS', 47),
(54, 'CY', 'Cyprus', 'Republic of Cyprus', 'CYP', 196, 'AS', 58),
(55, 'CZ', 'Czech Republic', 'Czech Republic', 'CZE', 203, 'EU', 59),
(56, 'DE', 'Germany', 'Federal Republic of Germany', 'DEU', 276, 'EU', 82),
(57, 'DJ', 'Djibouti', 'Republic of Djibouti', 'DJI', 262, 'AF', 61),
(58, 'DK', 'Denmark', 'Kingdom of Denmark', 'DNK', 208, 'EU', 60),
(59, 'DM', 'Dominica', 'Commonwealth of Dominica', 'DMA', 212, 'NA', 62),
(60, 'DO', 'Dominican Republic', 'Dominican Republic', 'DOM', 214, 'NA', 63),
(61, 'DZ', 'Algeria', 'People\'s Democratic Republic of Algeria', 'DZA', 012, 'AF', 5),
(62, 'EC', 'Ecuador', 'Republic of Ecuador', 'ECU', 218, 'SA', 64),
(63, 'EE', 'Estonia', 'Republic of Estonia', 'EST', 233, 'EU', 69),
(64, 'EG', 'Egypt', 'Arab Republic of Egypt', 'EGY', 818, 'AF', 65),
(65, 'EH', 'Western Sahara', 'Western Sahara', 'ESH', 732, 'AF', 242),
(66, 'ER', 'Eritrea', 'State of Eritrea', 'ERI', 232, 'AF', 68),
(67, 'ES', 'Spain', 'Kingdom of Spain', 'ESP', 724, 'EU', 207),
(68, 'ET', 'Ethiopia', 'Federal Democratic Republic of Ethiopia', 'ETH', 231, 'AF', 70),
(69, 'FI', 'Finland', 'Republic of Finland', 'FIN', 246, 'EU', 74),
(70, 'FJ', 'Fiji', 'Republic of the Fiji Islands', 'FJI', 242, 'OC', 73),
(71, 'FK', 'Falkland Islands', 'Falkland Islands (Malvinas)', 'FLK', 238, 'SA', 71),
(72, 'FM', 'Micronesia', 'Federated States of Micronesia', 'FSM', 583, 'OC', 144),
(73, 'FO', 'Faroe Islands', 'Faroe Islands', 'FRO', 234, 'EU', 72),
(74, 'FR', 'France', 'French Republic', 'FRA', 250, 'EU', 75),
(75, 'GA', 'Gabon', 'Gabonese Republic', 'GAB', 266, 'AF', 79),
(76, 'GB', 'United Kingdom', 'United Kingdom of Great Britain & Northern Ireland', 'GBR', 826, 'EU', 233),
(77, 'GD', 'Grenada', 'Grenada', 'GRD', 308, 'NA', 87),
(78, 'GE', 'Georgia', 'Georgia', 'GEO', 268, 'AS', 81),
(79, 'GF', 'French Guiana', 'French Guiana', 'GUF', 254, 'SA', 76),
(80, 'GG', 'Guernsey', 'Bailiwick of Guernsey', 'GGY', 831, 'EU', 91),
(81, 'GH', 'Ghana', 'Republic of Ghana', 'GHA', 288, 'AF', 83),
(82, 'GI', 'Gibraltar', 'Gibraltar', 'GIB', 292, 'EU', 84),
(83, 'GL', 'Greenland', 'Greenland', 'GRL', 304, 'NA', 86),
(84, 'GM', 'Gambia', 'Republic of the Gambia', 'GMB', 270, 'AF', 80),
(85, 'GN', 'Guinea', 'Republic of Guinea', 'GIN', 324, 'AF', 92),
(86, 'GP', 'Guadeloupe', 'Guadeloupe', 'GLP', 312, 'NA', 88),
(87, 'GQ', 'Equatorial Guinea', 'Republic of Equatorial Guinea', 'GNQ', 226, 'AF', 67),
(88, 'GR', 'Greece', 'Hellenic Republic Greece', 'GRC', 300, 'EU', 85),
(89, 'GS', 'South Georgia and South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'SGS', 239, 'AN', 206),
(90, 'GT', 'Guatemala', 'Republic of Guatemala', 'GTM', 320, 'NA', 90),
(91, 'GU', 'Guam', 'Guam', 'GUM', 316, 'OC', 89),
(92, 'GW', 'Guinea-Bissau', 'Republic of Guinea-Bissau', 'GNB', 624, 'AF', 93),
(93, 'GY', 'Guyana', 'Co-operative Republic of Guyana', 'GUY', 328, 'SA', 94),
(94, 'HK', 'Hong Kong', 'Hong Kong Special Administrative Region of China', 'HKG', 344, 'AS', 99),
(95, 'HM', 'Heard and McDonald Islands', 'Heard Island and McDonald Islands', 'HMD', 334, 'AN', 96),
(96, 'HN', 'Honduras', 'Republic of Honduras', 'HND', 340, 'NA', 98),
(97, 'HR', 'Croatia', 'Republic of Croatia', 'HRV', 191, 'EU', 56),
(98, 'HT', 'Haiti', 'Republic of Haiti', 'HTI', 332, 'NA', 95),
(99, 'HU', 'Hungary', 'Republic of Hungary', 'HUN', 348, 'EU', 100),
(100, 'ID', 'Indonesia', 'Republic of Indonesia', 'IDN', 360, 'AS', 103),
(101, 'IE', 'Ireland', 'Ireland', 'IRL', 372, 'EU', 106),
(102, 'IL', 'Israel', 'State of Israel', 'ISR', 376, 'AS', 108),
(103, 'IM', 'Isle of Man', 'Isle of Man', 'IMN', 833, 'EU', 107),
(104, 'IN', 'India', 'Republic of India', 'IND', 356, 'AS', 102),
(105, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory (Chagos Archipelago)', 'IOT', 086, 'AS', 33),
(106, 'IQ', 'Iraq', 'Republic of Iraq', 'IRQ', 368, 'AS', 105),
(107, 'IR', 'Iran', 'Islamic Republic of Iran', 'IRN', 364, 'AS', 104),
(108, 'IS', 'Iceland', 'Republic of Iceland', 'ISL', 352, 'EU', 101),
(109, 'IT', 'Italy', 'Italian Republic', 'ITA', 380, 'EU', 109),
(110, 'JE', 'Jersey', 'Bailiwick of Jersey', 'JEY', 832, 'EU', 112),
(111, 'JM', 'Jamaica', 'Jamaica', 'JAM', 388, 'NA', 110),
(112, 'JO', 'Jordan', 'Hashemite Kingdom of Jordan', 'JOR', 400, 'AS', 113),
(113, 'JP', 'Japan', 'Japan', 'JPN', 392, 'AS', 111),
(114, 'KE', 'Kenya', 'Republic of Kenya', 'KEN', 404, 'AF', 115),
(115, 'KG', 'Kyrgyzstan', 'Kyrgyz Republic', 'KGZ', 417, 'AS', 120),
(116, 'KH', 'Cambodia', 'Kingdom of Cambodia', 'KHM', 116, 'AS', 39),
(117, 'KI', 'Kiribati', 'Republic of Kiribati', 'KIR', 296, 'OC', 116),
(118, 'KM', 'Comoros', 'Union of the Comoros', 'COM', 174, 'AF', 50),
(119, 'KN', 'Saint Kitts and Nevis', 'Federation of Saint Kitts and Nevis', 'KNA', 659, 'NA', 187),
(120, 'KP', 'Korea, North', 'Democratic People\'s Republic of Korea', 'PRK', 408, 'AS', 117),
(121, 'KR', 'Korea, South', 'Republic of Korea', 'KOR', 410, 'AS', 118),
(122, 'KW', 'Kuwait', 'State of Kuwait', 'KWT', 414, 'AS', 119),
(123, 'KY', 'Cayman Islands', 'Cayman Islands', 'CYM', 136, 'NA', 42),
(124, 'KZ', 'Kazakhstan', 'Republic of Kazakhstan', 'KAZ', 398, 'AS', 114),
(125, 'LA', 'Laos', 'Lao People\'s Democratic Republic', 'LAO', 418, 'AS', 121),
(126, 'LB', 'Lebanon', 'Lebanese Republic', 'LBN', 422, 'AS', 123),
(127, 'LC', 'Saint Lucia', 'Saint Lucia', 'LCA', 662, 'NA', 188),
(128, 'LI', 'Liechtenstein', 'Principality of Liechtenstein', 'LIE', 438, 'EU', 127),
(129, 'LK', 'Sri Lanka', 'Democratic Socialist Republic of Sri Lanka', 'LKA', 144, 'AS', 208),
(130, 'LR', 'Liberia', 'Republic of Liberia', 'LBR', 430, 'AF', 125),
(131, 'LS', 'Lesotho', 'Kingdom of Lesotho', 'LSO', 426, 'AF', 124),
(132, 'LT', 'Lithuania', 'Republic of Lithuania', 'LTU', 440, 'EU', 128),
(133, 'LU', 'Luxembourg', 'Grand Duchy of Luxembourg', 'LUX', 442, 'EU', 129),
(134, 'LV', 'Latvia', 'Republic of Latvia', 'LVA', 428, 'EU', 122),
(135, 'LY', 'Libya', 'Libyan Arab Jamahiriya', 'LBY', 434, 'AF', 126),
(136, 'MA', 'Morocco', 'Kingdom of Morocco', 'MAR', 504, 'AF', 150),
(137, 'MC', 'Monaco', 'Principality of Monaco', 'MCO', 492, 'EU', 146),
(138, 'MD', 'Moldova', 'Republic of Moldova', 'MDA', 498, 'EU', 145),
(139, 'ME', 'Montenegro', 'Republic of Montenegro', 'MNE', 499, 'EU', 148),
(140, 'MF', 'Saint Martin (French part)', 'Saint Martin', 'MAF', 663, 'NA', 189),
(141, 'MG', 'Madagascar', 'Republic of Madagascar', 'MDG', 450, 'AF', 132),
(142, 'MH', 'Marshall Islands', 'Republic of the Marshall Islands', 'MHL', 584, 'OC', 138),
(143, 'MK', 'Macedonia', 'Republic of Macedonia', 'MKD', 807, 'EU', 131),
(144, 'ML', 'Mali', 'Republic of Mali', 'MLI', 466, 'AF', 136),
(145, 'MM', 'Myanmar', 'Union of Myanmar', 'MMR', 104, 'AS', 152),
(146, 'MN', 'Mongolia', 'Mongolia', 'MNG', 496, 'AS', 147),
(147, 'MO', 'Macau', 'Macao Special Administrative Region of China', 'MAC', 446, 'AS', 130),
(148, 'MP', 'Northern Mariana Islands', 'Commonwealth of the Northern Mariana Islands', 'MNP', 580, 'OC', 165),
(149, 'MQ', 'Martinique', 'Martinique', 'MTQ', 474, 'NA', 139),
(150, 'MR', 'Mauritania', 'Islamic Republic of Mauritania', 'MRT', 478, 'AF', 140),
(151, 'MS', 'Montserrat', 'Montserrat', 'MSR', 500, 'NA', 149),
(152, 'MT', 'Malta', 'Republic of Malta', 'MLT', 470, 'EU', 137),
(153, 'MU', 'Mauritius', 'Republic of Mauritius', 'MUS', 480, 'AF', 141),
(154, 'MV', 'Maldives', 'Republic of Maldives', 'MDV', 462, 'AS', 135),
(155, 'MW', 'Malawi', 'Republic of Malawi', 'MWI', 454, 'AF', 133),
(156, 'MX', 'Mexico', 'United Mexican States', 'MEX', 484, 'NA', 143),
(157, 'MY', 'Malaysia', 'Malaysia', 'MYS', 458, 'AS', 134),
(158, 'MZ', 'Mozambique', 'Republic of Mozambique', 'MOZ', 508, 'AF', 151),
(159, 'NA', 'Namibia', 'Republic of Namibia', 'NAM', 516, 'AF', 153),
(160, 'NC', 'New Caledonia', 'New Caledonia', 'NCL', 540, 'OC', 158),
(161, 'NE', 'Niger', 'Republic of Niger', 'NER', 562, 'AF', 161),
(162, 'NF', 'Norfolk Island', 'Norfolk Island', 'NFK', 574, 'OC', 164),
(163, 'NG', 'Nigeria', 'Federal Republic of Nigeria', 'NGA', 566, 'AF', 162),
(164, 'NI', 'Nicaragua', 'Republic of Nicaragua', 'NIC', 558, 'NA', 160),
(165, 'NL', 'Netherlands', 'Kingdom of the Netherlands', 'NLD', 528, 'EU', 156),
(166, 'NO', 'Norway', 'Kingdom of Norway', 'NOR', 578, 'EU', 166),
(167, 'NP', 'Nepal', 'State of Nepal', 'NPL', 524, 'AS', 155),
(168, 'NR', 'Nauru', 'Republic of Nauru', 'NRU', 520, 'OC', 154),
(169, 'NU', 'Niue', 'Niue', 'NIU', 570, 'OC', 163),
(170, 'NZ', 'New Zealand', 'New Zealand', 'NZL', 554, 'OC', 159),
(171, 'OM', 'Oman', 'Sultanate of Oman', 'OMN', 512, 'AS', 167),
(172, 'PA', 'Panama', 'Republic of Panama', 'PAN', 591, 'NA', 171),
(173, 'PE', 'Peru', 'Republic of Peru', 'PER', 604, 'SA', 174),
(174, 'PF', 'French Polynesia', 'French Polynesia', 'PYF', 258, 'OC', 77),
(175, 'PG', 'Papua New Guinea', 'Independent State of Papua New Guinea', 'PNG', 598, 'OC', 172),
(176, 'PH', 'Philippines', 'Republic of the Philippines', 'PHL', 608, 'AS', 175),
(177, 'PK', 'Pakistan', 'Islamic Republic of Pakistan', 'PAK', 586, 'AS', 168),
(178, 'PL', 'Poland', 'Republic of Poland', 'POL', 616, 'EU', 177),
(179, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'SPM', 666, 'NA', 190),
(180, 'PN', 'Pitcairn', 'Pitcairn Islands', 'PCN', 612, 'OC', 176),
(181, 'PR', 'Puerto Rico', 'Commonwealth of Puerto Rico', 'PRI', 630, 'NA', 179),
(182, 'PS', 'Palestine', 'Occupied Palestinian Territory', 'PSE', 275, 'AS', 170),
(183, 'PT', 'Portugal', 'Portuguese Republic', 'PRT', 620, 'EU', 178),
(184, 'PW', 'Palau', 'Republic of Palau', 'PLW', 585, 'OC', 169),
(185, 'PY', 'Paraguay', 'Republic of Paraguay', 'PRY', 600, 'SA', 173),
(186, 'QA', 'Qatar', 'State of Qatar', 'QAT', 634, 'AS', 180),
(187, 'RE', 'Reunion', 'Reunion', 'REU', 638, 'AF', 181),
(188, 'RO', 'Romania', 'Romania', 'ROU', 642, 'EU', 182),
(189, 'RS', 'Serbia', 'Republic of Serbia', 'SRB', 688, 'EU', 197),
(190, 'RU', 'Russian Federation', 'Russian Federation', 'RUS', 643, 'EU', 183),
(191, 'RW', 'Rwanda', 'Republic of Rwanda', 'RWA', 646, 'AF', 184),
(192, 'SA', 'Saudi Arabia', 'Kingdom of Saudi Arabia', 'SAU', 682, 'AS', 195),
(193, 'SB', 'Solomon Islands', 'Solomon Islands', 'SLB', 090, 'OC', 203),
(194, 'SC', 'Seychelles', 'Republic of Seychelles', 'SYC', 690, 'AF', 198),
(195, 'SD', 'Sudan', 'Republic of Sudan', 'SDN', 736, 'AF', 209),
(196, 'SE', 'Sweden', 'Kingdom of Sweden', 'SWE', 752, 'EU', 213),
(197, 'SG', 'Singapore', 'Republic of Singapore', 'SGP', 702, 'AS', 200),
(198, 'SH', 'Saint Helena', 'Saint Helena', 'SHN', 654, 'AF', 186),
(199, 'SI', 'Slovenia', 'Republic of Slovenia', 'SVN', 705, 'EU', 202),
(200, 'SJ', 'Svalbard and Jan Mayen Islands', 'Svalbard & Jan Mayen Islands', 'SJM', 744, 'EU', 211),
(201, 'SK', 'Slovakia', 'Slovakia (Slovak Republic)', 'SVK', 703, 'EU', 201),
(202, 'SL', 'Sierra Leone', 'Republic of Sierra Leone', 'SLE', 694, 'AF', 199),
(203, 'SM', 'San Marino', 'Republic of San Marino', 'SMR', 674, 'EU', 193),
(204, 'SN', 'Senegal', 'Republic of Senegal', 'SEN', 686, 'AF', 196),
(205, 'SO', 'Somalia', 'Somali Republic', 'SOM', 706, 'AF', 204),
(206, 'SR', 'Suriname', 'Republic of Suriname', 'SUR', 740, 'SA', 210),
(207, 'ST', 'Sao Tome and Principe', 'Democratic Republic of Sao Tome and Principe', 'STP', 678, 'AF', 194),
(208, 'SV', 'El Salvador', 'Republic of El Salvador', 'SLV', 222, 'NA', 66),
(209, 'SY', 'Syria', 'Syrian Arab Republic', 'SYR', 760, 'AS', 215),
(210, 'SZ', 'Swaziland', 'Kingdom of Swaziland', 'SWZ', 748, 'AF', 212),
(211, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'TCA', 796, 'NA', 228),
(212, 'TD', 'Chad', 'Republic of Chad', 'TCD', 148, 'AF', 44),
(213, 'TF', 'French Southern Lands', 'French Southern Territories', 'ATF', 260, 'AN', 78),
(214, 'TG', 'Togo', 'Togolese Republic', 'TGO', 768, 'AF', 221),
(215, 'TH', 'Thailand', 'Kingdom of Thailand', 'THA', 764, 'AS', 219),
(216, 'TJ', 'Tajikistan', 'Republic of Tajikistan', 'TJK', 762, 'AS', 217),
(217, 'TK', 'Tokelau', 'Tokelau', 'TKL', 772, 'OC', 222),
(218, 'TL', 'Timor-Leste', 'Democratic Republic of Timor-Leste', 'TLS', 626, 'AS', 220),
(219, 'TM', 'Turkmenistan', 'Turkmenistan', 'TKM', 795, 'AS', 227),
(220, 'TN', 'Tunisia', 'Tunisian Republic', 'TUN', 788, 'AF', 225),
(221, 'TO', 'Tonga', 'Kingdom of Tonga', 'TON', 776, 'OC', 223),
(222, 'TR', 'Turkey', 'Republic of Turkey', 'TUR', 792, 'AS', 226),
(223, 'TT', 'Trinidad and Tobago', 'Republic of Trinidad and Tobago', 'TTO', 780, 'NA', 224),
(224, 'TV', 'Tuvalu', 'Tuvalu', 'TUV', 798, 'OC', 229),
(225, 'TW', 'Taiwan', 'Taiwan', 'TWN', 158, 'AS', 216),
(226, 'TZ', 'Tanzania', 'United Republic of Tanzania', 'TZA', 834, 'AF', 218),
(227, 'UA', 'Ukraine', 'Ukraine', 'UKR', 804, 'EU', 231),
(228, 'UG', 'Uganda', 'Republic of Uganda', 'UGA', 800, 'AF', 230),
(229, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UMI', 581, 'OC', 234),
(230, 'US', 'United States of America', 'United States of America', 'USA', 840, 'NA', 1),
(231, 'UY', 'Uruguay', 'Eastern Republic of Uruguay', 'URY', 858, 'SA', 236),
(232, 'UZ', 'Uzbekistan', 'Republic of Uzbekistan', 'UZB', 860, 'AS', 237),
(233, 'VA', 'Vatican City', 'Holy See (Vatican City State)', 'VAT', 336, 'EU', 97),
(234, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'VCT', 670, 'NA', 191),
(235, 'VE', 'Venezuela', 'Bolivarian Republic of Venezuela', 'VEN', 862, 'SA', 239),
(236, 'VG', 'Virgin Islands, British', 'British Virgin Islands', 'VGB', 092, 'NA', 34),
(237, 'VI', 'Virgin Islands, U.S.', 'United States Virgin Islands', 'VIR', 850, 'NA', 235),
(238, 'VN', 'Vietnam', 'Socialist Republic of Vietnam', 'VNM', 704, 'AS', 240),
(239, 'VU', 'Vanuatu', 'Republic of Vanuatu', 'VUT', 548, 'OC', 238),
(240, 'WF', 'Wallis and Futuna Islands', 'Wallis and Futuna', 'WLF', 876, 'OC', 241),
(241, 'WS', 'Samoa', 'Independent State of Samoa', 'WSM', 882, 'OC', 192),
(242, 'YE', 'Yemen', 'Yemen', 'YEM', 887, 'AS', 243),
(243, 'YT', 'Mayotte', 'Mayotte', 'MYT', 175, 'AF', 142),
(244, 'ZA', 'South Africa', 'Republic of South Africa', 'ZAF', 710, 'AF', 205),
(245, 'ZM', 'Zambia', 'Republic of Zambia', 'ZMB', 894, 'AF', 244),
(246, 'ZW', 'Zimbabwe', 'Republic of Zimbabwe', 'ZWE', 716, 'AF', 245),
(0, 'WW', 'WorldWide', 'WorldWide', 'WWI', 000, 'WW', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uc_offers`
--

CREATE TABLE `uc_offers` (
  `id` int(11) NOT NULL,
  `offer_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  `restrictions` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `payable_action` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `preview_url` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `offer_url` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `advertiser_cost` decimal(10,2) NOT NULL,
  `affiliate_payout` decimal(10,2) NOT NULL,
  `affiliate_payout_second` decimal(10,2) NOT NULL,
  `affiliate_payout_second_rate` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `country` text COLLATE latin1_general_ci NOT NULL,
  `thumb_id` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `private_offer` tinyint(4) NOT NULL,
  `featured_offer` tinyint(4) NOT NULL,
  `epc` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `uc_offers`
--

INSERT INTO `uc_offers` (`id`, `offer_name`, `description`, `restrictions`, `payable_action`, `preview_url`, `offer_url`, `advertiser_cost`, `affiliate_payout`, `affiliate_payout_second`, `affiliate_payout_second_rate`, `category`, `country`, `thumb_id`, `status`, `private_offer`, `featured_offer`, `epc`) VALUES
(1020, 'Booking.com - Hotels &amp Travel', 'Booking.com is one of the worlds largest hotel provider. Offer your visitors the chance to make big savings on hotels in 118000 destinations worldwide. Browse hotel reviews and find the guaranteed best price on hotels for all budgets.\n<p>\nAs an affiliate, you will benefit from the high converting landing page, and top industry payouts.', 'No Incentive traffic permitted on this campaign. No brand bidding. Email creatives must be authorised before sending.', 'Payable on a valid booking. (Fully paid & Confirmed).', 'http://booking.com', 'https://www.booking.com/index.html?aid=929856&sid={user_id}', 18.00, 12.60, 13.60, 10, 13, '76', '1020.jpeg', 2, 0, 0, 0.48),
(1021, 'QuidCo Cashback', 'Quidco is the smart and easy way to earn cashback when shopping online, in-store and on mobile. With over 4,300 top retailers to choose from, Quidco gives its 5 million members the chance to enjoy 8,000 offers with deals from all the big brands including; Sky, Talk Talk, Apple, Tesco, LoveFilm, Superdry, PC World, and many more.', 'No Incentive traffic permitted on this campaign. No brand bidding. Email creatives must be authorised before sending.', 'Email submit. Double opt-in.', 'http://quidco.com', 'http://clkuk.tradedoubler.com/click?p(229891)a(2976651)g(21028100)&EPI={user_id}', 2.20, 1.60, 1.80, 100, 11, '0', '1021.jpeg', 2, 1, 1, 0.89),
(1000, 'Test Offer', 'Test offer description', 'Test offer restrictions', 'Test offer payable action', 'http://testoffer.com/', 'http://google.com?user_id={user_id}&sub_id={sub_id}', 20.00, 14.00, 16.00, 100, 10, '0', '1000.jpeg', 2, 0, 0, 0.48);

-- --------------------------------------------------------

--
-- Table structure for table `uc_offers_categories`
--

CREATE TABLE `uc_offers_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_offers_categories`
--

INSERT INTO `uc_offers_categories` (`id`, `name`) VALUES
(1, 'Automotive'),
(2, 'Business to Business'),
(3, 'Computers & Electronics'),
(4, 'Education & Careers'),
(5, 'Fashion & Beauty'),
(6, 'Finance & Insurance'),
(7, 'Gaming & Gambling'),
(8, 'Home & Garden'),
(9, 'Media & Entertainment'),
(10, 'Incentive'),
(11, 'Shopping & Retail'),
(12, 'Social & Dating'),
(13, 'Travel');

-- --------------------------------------------------------

--
-- Table structure for table `uc_offers_status`
--

CREATE TABLE `uc_offers_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_offers_status`
--

INSERT INTO `uc_offers_status` (`id`, `name`) VALUES
(1, 'Paused'),
(2, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `uc_pages`
--

CREATE TABLE `uc_pages` (
  `id` int(11) NOT NULL,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_pages`
--

INSERT INTO `uc_pages` (`id`, `page`, `private`) VALUES
(2, 'account.php', 0),
(3, 'admin_configuration.php', 0),
(46, 'postback.php', 0),
(6, 'admin_page.php', 0),
(7, 'admin_pages.php', 0),
(8, 'admin_permission.php', 0),
(9, 'admin_permissions.php', 0),
(11, 'admin_user.php', 0),
(12, 'admin_users.php', 0),
(13, 'dashboard-header.php', 0),
(14, 'footer.php', 0),
(15, 'forgot-password.php', 0),
(16, 'header.php', 0),
(17, 'index.php', 0),
(18, 'login.php', 0),
(19, 'logout.php', 0),
(20, 'menu.php', 0),
(21, 'register.php', 0),
(22, 'resend-activation.php', 0),
(24, 'admin_click_report.php', 0),
(25, 'publisher-click-report.php', 0),
(27, 'publisher-offer-report.php', 0),
(28, 'admin_conversion_report.php', 0),
(42, 'admin_countries.php', 0),
(31, 'admin_offer_report.php', 0),
(32, 'publisher-campaigns.php', 0),
(33, 'publisher-conversion-report.php', 0),
(34, 'publisher-payment-centre.php', 0),
(36, 'admin_campaign_approvals.php', 0),
(37, 'admin_creative_files.php', 0),
(38, 'publisher-account-settings.php', 0),
(39, 'publisher-campaign.php', 0),
(41, 'right-panel.php', 0),
(47, 'contact-us.php', 0),
(48, 'dashboard-footer.php', 0),
(49, 'performance-marketing.php', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uc_payments`
--

CREATE TABLE `uc_payments` (
  `id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uc_payments`
--

INSERT INTO `uc_payments` (`id`, `user_id`, `start_date`, `end_date`, `amount`, `status`) VALUES
(1, 1000, '2017-12-01 00:00:00', '2017-12-31 00:00:00', 1250.25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `uc_payments_method`
--

CREATE TABLE `uc_payments_method` (
  `id` int(11) NOT NULL,
  `payment_method` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_payments_method`
--

INSERT INTO `uc_payments_method` (`id`, `payment_method`) VALUES
(1, 'Paypal - (&pound;50 Minimum)'),
(2, 'Bank Transfer - UK Only (&pound;250 Minimum)');

-- --------------------------------------------------------

--
-- Table structure for table `uc_payments_status`
--

CREATE TABLE `uc_payments_status` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_payments_status`
--

INSERT INTO `uc_payments_status` (`id`, `status`) VALUES
(1, 'Rejected'),
(2, 'Pending'),
(3, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `uc_permissions`
--

CREATE TABLE `uc_permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_permissions`
--

INSERT INTO `uc_permissions` (`id`, `name`) VALUES
(1, 'Global Administrator'),
(2, 'Administrator'),
(10, 'Affiliate Account'),
(20, 'Account Closed');

-- --------------------------------------------------------

--
-- Table structure for table `uc_permission_page_matches`
--

CREATE TABLE `uc_permission_page_matches` (
  `id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uc_users`
--

CREATE TABLE `uc_users` (
  `id` int(11) NOT NULL,
  `id_code` varchar(5) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `telephone_number` varchar(20) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_details` varchar(200) NOT NULL,
  `traffic_details` varchar(10000) NOT NULL,
  `title` varchar(150) NOT NULL,
  `ref_id` varchar(11) NOT NULL,
  `sub_id` varchar(20) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `session_token` text NOT NULL,
  `sign_up_stamp` int(11) NOT NULL,
  `last_sign_in_stamp` int(11) NOT NULL,
  `user_notes` mediumtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_users`
--

INSERT INTO `uc_users` (`id`, `id_code`, `user_name`, `password`, `email`, `name`, `address`, `postal_code`, `country_id`, `telephone_number`, `payment_method`, `payment_details`, `traffic_details`, `title`, `ref_id`, `sub_id`, `ip_address`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `session_token`, `sign_up_stamp`, `last_sign_in_stamp`, `user_notes`) VALUES
(1000, '1000', 'administrator', '7cf81208b790b164d615c820b62cb2262a44b75da2120314ec03d7ffbc2a3a39c', 'admin@gmail.com', 'Administrator Account', '1 Street Road', '1217', 20, '01715892032', 'Paypal', 'engr.mrksohag@gmail.com', 'This is where user will update their traffic details.', 'Global Administrator', '12345', 'Google Adwords', '11.11.11.11', '0c70eba31d6fd7e86edcd747880a2cb5', 1508756609, 0, 1, 'c95436a8465ff180c3d229577dee3583', 1503397537, 1516630023, 'No Notes'),
(1001, 'Hs8sS', 'useraccount', 'eb593a8b12b3243bf4c7bb137496c3f614193637665f17654e09787e623d6bd42', 'useraccount@gmail.com', 'First Name First Name', 'Address', 'PO1 CO2', 76, '012345678978', 'Paypal', 'email@paypal.com', 'None', 'Affiliate Account', '1000', 'Sub', '12.34.56.789', 'a7108ceb8b5079dafda29f6d6b95a14d', 1508586826, 1, 1, '84c96ad3f535c6f7f7ee62abd463018b', 1508586826, 1516457838, 'User account notes.');

-- --------------------------------------------------------

--
-- Table structure for table `uc_user_permission_matches`
--

CREATE TABLE `uc_user_permission_matches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_user_permission_matches`
--

INSERT INTO `uc_user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
(1, 1000, 1),
(2, 1001, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uc_affiliate_permission_offer_matches`
--
ALTER TABLE `uc_affiliate_permission_offer_matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_affiliate_permission_offer_status`
--
ALTER TABLE `uc_affiliate_permission_offer_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_banner_creatives`
--
ALTER TABLE `uc_banner_creatives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_banner_creatives_status`
--
ALTER TABLE `uc_banner_creatives_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_banner_creatives_type`
--
ALTER TABLE `uc_banner_creatives_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_clicks`
--
ALTER TABLE `uc_clicks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_clicks_status`
--
ALTER TABLE `uc_clicks_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_configuration`
--
ALTER TABLE `uc_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_conversions`
--
ALTER TABLE `uc_conversions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_conversions_status`
--
ALTER TABLE `uc_conversions_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_countries`
--
ALTER TABLE `uc_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_offers`
--
ALTER TABLE `uc_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_offers_categories`
--
ALTER TABLE `uc_offers_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_offers_status`
--
ALTER TABLE `uc_offers_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_pages`
--
ALTER TABLE `uc_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_payments`
--
ALTER TABLE `uc_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `uc_payments_method`
--
ALTER TABLE `uc_payments_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_payments_status`
--
ALTER TABLE `uc_payments_status`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `uc_permissions`
--
ALTER TABLE `uc_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_permission_page_matches`
--
ALTER TABLE `uc_permission_page_matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_users`
--
ALTER TABLE `uc_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uc_user_permission_matches`
--
ALTER TABLE `uc_user_permission_matches`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uc_affiliate_permission_offer_matches`
--
ALTER TABLE `uc_affiliate_permission_offer_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1011;
--
-- AUTO_INCREMENT for table `uc_banner_creatives`
--
ALTER TABLE `uc_banner_creatives`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `uc_clicks`
--
ALTER TABLE `uc_clicks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1066;
--
-- AUTO_INCREMENT for table `uc_clicks_status`
--
ALTER TABLE `uc_clicks_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `uc_configuration`
--
ALTER TABLE `uc_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `uc_conversions`
--
ALTER TABLE `uc_conversions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `uc_conversions_status`
--
ALTER TABLE `uc_conversions_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `uc_countries`
--
ALTER TABLE `uc_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT for table `uc_pages`
--
ALTER TABLE `uc_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `uc_payments`
--
ALTER TABLE `uc_payments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `uc_permissions`
--
ALTER TABLE `uc_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `uc_permission_page_matches`
--
ALTER TABLE `uc_permission_page_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
--
-- AUTO_INCREMENT for table `uc_users`
--
ALTER TABLE `uc_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;
--
-- AUTO_INCREMENT for table `uc_user_permission_matches`
--
ALTER TABLE `uc_user_permission_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1024;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
