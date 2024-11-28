-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 28, 2024 at 05:18 AM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `andypos`
--

-- --------------------------------------------------------

--
-- Table structure for table `menulist`
--

CREATE TABLE `menulist` (
  `menu_id` int(4) NOT NULL,
  `menu_name` varchar(50) NOT NULL,
  `menu_price` float(6,2) NOT NULL,
  `menu_cost` float(6,2) NOT NULL,
  `type_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menulist`
--

INSERT INTO `menulist` (`menu_id`, `menu_name`, `menu_price`, `menu_cost`, `type_id`) VALUES
(1, 'Hot Espresso', 50.00, 35.00, 2),
(2, 'Hot Americano', 60.00, 40.00, 2),
(3, 'Hot Cappuccino', 60.00, 40.00, 2),
(4, 'Hot Cafe Latte', 60.00, 40.00, 2),
(5, 'Hot Mocha', 60.00, 45.00, 2),
(6, 'Hot Caramel Macchiato', 60.00, 45.00, 2),
(7, 'Ice Americano', 65.00, 45.00, 4),
(8, 'Ice Thai Es Yen', 70.00, 45.00, 4),
(9, 'Ice Cappuccino', 70.00, 45.00, 4),
(10, 'Ice Cafe Latte', 70.00, 45.00, 4),
(11, 'Ice Mocha', 75.00, 50.00, 4),
(12, 'Ice Caramel Macchiato', 75.00, 50.00, 4),
(13, 'Black Orange', 75.00, 50.00, 7),
(14, 'NAM-DOK-MAI', 80.00, 47.00, 7),
(15, 'Dirty', 85.00, 45.00, 7),
(16, 'Wake it Up!', 75.00, 48.00, 7),
(17, 'SUNNY DOY', 85.00, 52.00, 7),
(18, 'Hot Thai Tea', 60.00, 35.00, 3),
(19, 'Hot Premium Matcha', 75.00, 45.00, 3),
(20, 'Hot Cocoa', 60.00, 35.00, 3),
(21, 'Hot Fresh Milk', 50.00, 20.00, 3),
(22, 'Hot Caramel Milk', 60.00, 35.00, 3),
(23, 'Ice Thai Tea', 65.00, 40.00, 5),
(24, 'Ice Peach Tea', 65.00, 40.00, 5),
(25, 'Ice Lime Tea', 65.00, 35.00, 5),
(26, 'Ice Honey Lime Tea', 70.00, 40.00, 5),
(27, 'Ice Premium Matcha', 80.00, 50.00, 5),
(28, 'Ice Cocoa', 70.00, 37.00, 5),
(29, 'Ice Mint Cocoa', 75.00, 40.00, 5),
(30, 'Ice Fresh Milk', 75.00, 35.00, 5),
(31, 'Ice Mint Milk', 60.00, 37.00, 5),
(32, 'Ice Caramel Milk', 65.00, 37.00, 5),
(33, 'Ice Vanilla Milk', 65.00, 37.00, 5),
(34, 'Strawberry Soda', 60.00, 40.00, 6),
(35, 'Apple Soda', 60.00, 40.00, 6),
(36, 'Mint Soda', 60.00, 40.00, 6),
(37, 'Red Lime Soda', 60.00, 35.00, 6),
(38, 'Honey Lime Soda', 60.00, 40.00, 6),
(39, 'Frappe Cocoa', 75.00, 47.00, 1),
(40, 'Frappe Mint Cocoa', 80.00, 50.00, 1),
(41, 'Frappe Caramel Cocoa', 80.00, 47.00, 1),
(42, 'Frappe Fresh Milk', 70.00, 45.00, 1),
(43, 'Frappe Strawberry Smoothie', 85.00, 55.00, 1),
(44, 'Frappe Mango Smoothie', 85.00, 55.00, 1),
(78, 'เทสนะจ้ะ01', 20.00, 10.00, 6);

-- --------------------------------------------------------

--
-- Table structure for table `menutype`
--

CREATE TABLE `menutype` (
  `type_id` int(4) NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `type_color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menutype`
--

INSERT INTO `menutype` (`type_id`, `type_name`, `type_color`) VALUES
(1, 'Frappe', '#800080'),
(2, 'HotCoffee', '#8b4513'),
(3, 'HotNonCoffee', '#006400'),
(4, 'IceCoffee', '#cd853f'),
(5, 'IceNonCoffee', '#00a36c'),
(6, 'Soda', '#ffc0c8'),
(7, 'Special', '#ffd700');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `rc_id` int(4) NOT NULL,
  `rc_pt` enum('Cash','QR Payment') NOT NULL,
  `rc_date` date NOT NULL,
  `rc_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`rc_id`, `rc_pt`, `rc_date`, `rc_time`) VALUES
(105, 'QR Payment', '2024-03-23', '01:02:25'),
(106, 'Cash', '2024-03-23', '03:56:34'),
(107, 'QR Payment', '2024-03-23', '04:04:42'),
(108, 'QR Payment', '2024-03-23', '04:16:31'),
(109, 'Cash', '2024-03-24', '16:06:32'),
(110, 'Cash', '2024-03-26', '22:50:42'),
(111, 'QR Payment', '2024-04-01', '16:45:22'),
(112, 'Cash', '2024-04-20', '21:44:50'),
(113, 'Cash', '2024-04-20', '22:40:40'),
(114, 'Cash', '2024-04-22', '22:23:44'),
(115, 'QR Payment', '2024-04-27', '12:52:21'),
(116, 'Cash', '2024-04-27', '12:53:20'),
(117, 'QR Payment', '2024-04-27', '12:53:26'),
(118, 'QR Payment', '2024-04-27', '12:53:30'),
(119, 'QR Payment', '2024-04-27', '12:53:36'),
(120, 'Cash', '2024-04-27', '12:53:45'),
(121, 'QR Payment', '2024-04-28', '11:34:23');

-- --------------------------------------------------------

--
-- Table structure for table `receiptdetail`
--

CREATE TABLE `receiptdetail` (
  `rcd_id` int(4) NOT NULL,
  `rcd_price` float(6,2) NOT NULL,
  `rcd_cost` float(6,2) NOT NULL,
  `rcd_qty` int(2) NOT NULL,
  `menu_id` int(4) NOT NULL,
  `rc_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `receiptdetail`
--

INSERT INTO `receiptdetail` (`rcd_id`, `rcd_price`, `rcd_cost`, `rcd_qty`, `menu_id`, `rc_id`) VALUES
(370, 50.00, 35.00, 1, 1, 105),
(371, 60.00, 40.00, 1, 2, 105),
(372, 60.00, 40.00, 1, 3, 105),
(373, 60.00, 40.00, 1, 4, 105),
(374, 60.00, 45.00, 1, 5, 105),
(375, 60.00, 45.00, 1, 6, 105),
(376, 65.00, 45.00, 1, 7, 105),
(377, 70.00, 45.00, 1, 8, 105),
(378, 70.00, 45.00, 1, 9, 105),
(379, 70.00, 45.00, 1, 10, 105),
(380, 75.00, 50.00, 1, 11, 105),
(381, 75.00, 50.00, 1, 12, 105),
(382, 75.00, 50.00, 1, 13, 105),
(383, 80.00, 47.00, 1, 14, 105),
(384, 85.00, 45.00, 1, 15, 105),
(385, 75.00, 48.00, 1, 16, 105),
(386, 85.00, 52.00, 1, 17, 105),
(387, 60.00, 35.00, 1, 18, 105),
(388, 75.00, 45.00, 1, 19, 105),
(389, 60.00, 35.00, 1, 20, 105),
(390, 50.00, 20.00, 1, 21, 105),
(391, 60.00, 35.00, 1, 22, 105),
(392, 65.00, 40.00, 1, 23, 105),
(393, 65.00, 40.00, 1, 24, 105),
(394, 65.00, 35.00, 1, 25, 105),
(395, 70.00, 40.00, 1, 26, 105),
(396, 80.00, 50.00, 1, 27, 105),
(397, 70.00, 37.00, 1, 28, 105),
(398, 75.00, 40.00, 1, 29, 105),
(399, 75.00, 35.00, 1, 30, 105),
(400, 60.00, 37.00, 1, 31, 105),
(401, 65.00, 37.00, 1, 32, 105),
(402, 65.00, 37.00, 1, 33, 105),
(403, 60.00, 40.00, 1, 34, 105),
(404, 60.00, 40.00, 1, 35, 105),
(405, 60.00, 40.00, 1, 36, 105),
(406, 60.00, 35.00, 1, 37, 105),
(407, 60.00, 40.00, 1, 38, 105),
(408, 75.00, 47.00, 1, 39, 105),
(409, 80.00, 50.00, 1, 40, 105),
(410, 80.00, 47.00, 1, 41, 105),
(411, 70.00, 45.00, 1, 42, 105),
(412, 85.00, 55.00, 1, 43, 105),
(413, 85.00, 55.00, 1, 44, 105),
(414, 50.00, 35.00, 4, 1, 106),
(415, 60.00, 40.00, 1, 3, 106),
(416, 60.00, 40.00, 1, 4, 106),
(417, 60.00, 45.00, 2, 5, 106),
(418, 60.00, 35.00, 3, 18, 106),
(419, 75.00, 45.00, 2, 19, 106),
(420, 75.00, 47.00, 1, 39, 106),
(421, 85.00, 55.00, 1, 44, 106),
(422, 70.00, 45.00, 1, 9, 107),
(423, 20.00, 10.00, 1, 78, 107),
(424, 60.00, 40.00, 1, 34, 108),
(425, 60.00, 40.00, 1, 35, 108),
(426, 50.00, 35.00, 3, 1, 109),
(427, 60.00, 40.00, 4, 2, 109),
(428, 60.00, 40.00, 5, 3, 109),
(429, 60.00, 40.00, 3, 4, 109),
(430, 60.00, 45.00, 1, 5, 109),
(431, 60.00, 45.00, 1, 6, 109),
(432, 65.00, 45.00, 2, 7, 109),
(433, 70.00, 45.00, 1, 9, 109),
(434, 60.00, 35.00, 1, 18, 109),
(435, 75.00, 45.00, 1, 19, 109),
(436, 60.00, 35.00, 1, 20, 109),
(437, 50.00, 20.00, 1, 21, 109),
(438, 60.00, 35.00, 1, 22, 109),
(439, 75.00, 47.00, 1, 39, 109),
(440, 80.00, 50.00, 1, 40, 109),
(441, 80.00, 47.00, 1, 41, 109),
(442, 70.00, 45.00, 1, 42, 109),
(443, 85.00, 55.00, 1, 43, 109),
(444, 85.00, 55.00, 1, 44, 109),
(445, 50.00, 35.00, 20, 1, 110),
(446, 50.00, 35.00, 2, 1, 111),
(447, 60.00, 40.00, 1, 2, 111),
(448, 60.00, 40.00, 3, 3, 111),
(449, 60.00, 40.00, 1, 4, 111),
(450, 60.00, 45.00, 2, 5, 111),
(451, 60.00, 45.00, 1, 6, 111),
(452, 60.00, 35.00, 2, 18, 111),
(453, 75.00, 45.00, 1, 19, 111),
(454, 60.00, 35.00, 1, 20, 111),
(455, 50.00, 20.00, 1, 21, 111),
(456, 75.00, 47.00, 1, 39, 111),
(457, 80.00, 50.00, 1, 40, 111),
(458, 80.00, 47.00, 1, 41, 111),
(459, 70.00, 45.00, 1, 42, 111),
(460, 85.00, 55.00, 1, 43, 111),
(461, 80.00, 50.00, 3, 40, 112),
(462, 80.00, 47.00, 2, 41, 112),
(463, 50.00, 35.00, 2, 1, 113),
(464, 80.00, 50.00, 2, 40, 113),
(465, 80.00, 47.00, 2, 41, 113),
(466, 85.00, 55.00, 1, 44, 113),
(467, 70.00, 45.00, 1, 9, 114),
(468, 50.00, 20.00, 1, 21, 114),
(469, 50.00, 35.00, 1, 1, 115),
(470, 75.00, 47.00, 1, 39, 115),
(471, 80.00, 50.00, 1, 40, 115),
(472, 80.00, 47.00, 1, 41, 115),
(473, 85.00, 55.00, 1, 44, 115),
(474, 50.00, 35.00, 1, 1, 116),
(475, 60.00, 40.00, 1, 2, 116),
(476, 60.00, 45.00, 1, 6, 116),
(477, 75.00, 45.00, 1, 19, 116),
(478, 60.00, 35.00, 1, 20, 116),
(479, 50.00, 20.00, 1, 21, 116),
(480, 85.00, 55.00, 1, 43, 116),
(481, 60.00, 40.00, 1, 4, 117),
(482, 75.00, 45.00, 1, 19, 117),
(483, 60.00, 35.00, 1, 22, 117),
(484, 80.00, 50.00, 1, 40, 118),
(485, 85.00, 55.00, 1, 44, 118),
(486, 60.00, 37.00, 1, 31, 119),
(487, 75.00, 48.00, 1, 16, 120),
(488, 60.00, 40.00, 1, 38, 120),
(489, 50.00, 35.00, 1, 1, 121),
(490, 75.00, 47.00, 1, 39, 121),
(491, 80.00, 50.00, 1, 40, 121),
(492, 80.00, 47.00, 1, 41, 121),
(493, 85.00, 55.00, 1, 44, 121);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menulist`
--
ALTER TABLE `menulist`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `fk_type_id` (`type_id`);

--
-- Indexes for table `menutype`
--
ALTER TABLE `menutype`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`rc_id`);

--
-- Indexes for table `receiptdetail`
--
ALTER TABLE `receiptdetail`
  ADD PRIMARY KEY (`rcd_id`),
  ADD KEY `fk_rc_id` (`rc_id`),
  ADD KEY `fk_menu_id` (`menu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menulist`
--
ALTER TABLE `menulist`
  MODIFY `menu_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `menutype`
--
ALTER TABLE `menutype`
  MODIFY `type_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `rc_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `receiptdetail`
--
ALTER TABLE `receiptdetail`
  MODIFY `rcd_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `menulist`
--
ALTER TABLE `menulist`
  ADD CONSTRAINT `fk_type_id` FOREIGN KEY (`type_id`) REFERENCES `menutype` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `receiptdetail`
--
ALTER TABLE `receiptdetail`
  ADD CONSTRAINT `fk_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menulist` (`menu_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rc_id` FOREIGN KEY (`rc_id`) REFERENCES `receipt` (`rc_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
