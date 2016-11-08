SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `workout` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `workout_id` int(11) NOT NULL,
  `rounds` tinyint(4) NOT NULL,
  `reps` float NOT NULL,
  `descr` varchar(255) COLLATE utf8_estonian_ci NOT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

CREATE TABLE IF NOT EXISTS `workouts` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_estonian_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_estonian_ci NOT NULL,
  `descr` text COLLATE utf8_estonian_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_estonian_ci NOT NULL,
  `category` char(2) COLLATE utf8_estonian_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `unit` varchar(10) COLLATE utf8_estonian_ci NOT NULL,
  `color` varchar(16) COLLATE utf8_estonian_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

ALTER TABLE `workout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `workout_id` (`workout_id`);

ALTER TABLE `workouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `category` (`category`);
