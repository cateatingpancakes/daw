-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 03:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daw_proiect`
--

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `MOVIE_ID` int(11) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `RUNTIME` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`MOVIE_ID`, `NAME`, `RUNTIME`) VALUES
(1, 'Dune: Part Two', 166),
(2, 'Oppenheimer', 180),
(3, 'The Shawshank Redemption', 114),
(4, 'Teambuilding', 93),
(5, 'The Godfather', 175);

-- --------------------------------------------------------

--
-- Table structure for table `projection`
--

CREATE TABLE `projection` (
  `PROJ_ID` int(11) NOT NULL,
  `MOVIE_ID` int(11) NOT NULL,
  `ROOM_ID` int(11) NOT NULL,
  `DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `ROOM_ID` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `N_ROWS` int(11) NOT NULL,
  `N_COLS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`ROOM_ID`, `NAME`, `N_ROWS`, `N_COLS`) VALUES
(1, 'Sala 1', 7, 9),
(2, 'Sala 2', 6, 10),
(3, 'Sala 3', 5, 8);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `SALE_ID` int(11) NOT NULL,
  `PROJ_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `TICKET_ID` int(11) NOT NULL,
  `SEAT` int(11) NOT NULL,
  `CHECK_CODE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `SESSION_ID` int(11) NOT NULL,
  `COOKIE` varchar(255) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `EXPIRY_DATE` datetime DEFAULT (current_timestamp() + interval 24 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stat`
--

CREATE TABLE `stat` (
  `STAT_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `IP` varchar(45) NOT NULL,
  `TIME` timestamp NOT NULL DEFAULT current_timestamp(),
  `PAGE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_type`
--

CREATE TABLE `ticket_type` (
  `TICKET_ID` int(11) NOT NULL,
  `DESC` varchar(255) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_type`
--

INSERT INTO `ticket_type` (`TICKET_ID`, `DESC`, `PRICE`) VALUES
(1, 'Adult/standard', 31.99),
(2, 'Elevi și studenți', 23.99),
(3, 'Pensionari', 16.99);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `USERNAME` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `REALNAME` varchar(255) NOT NULL,
  `PERM_CODE` int(3) NOT NULL DEFAULT 0,
  `CONF_CODE` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `USERNAME`, `PASSWORD`, `EMAIL`, `REALNAME`, `PERM_CODE`, `CONF_CODE`) VALUES
(1, 'mspiridon', '$2y$10$YovB4Eot3iZA1DYiWYJQJ.7.1EYzCWVnZCUGUU9ELvZ5ObX5rnAh6', 'mihneaspiridon@gmail.com', 'Spiridon Mihnea-Andrei', 1, NULL),
(2, 'mmara', '$2y$10$DYnRiTOuRTa5j2qEqJw8POYCCRadmbWCLSnV1ar1r/lu1TzSxKO2y', 'mara@dummy.com', 'Matei Mara-Sandra', 3, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`MOVIE_ID`);

--
-- Indexes for table `projection`
--
ALTER TABLE `projection`
  ADD PRIMARY KEY (`PROJ_ID`),
  ADD KEY `MOVIE_ID` (`MOVIE_ID`),
  ADD KEY `ROOM_ID` (`ROOM_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`ROOM_ID`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`SALE_ID`),
  ADD KEY `PROJ_ID` (`PROJ_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `TICKET_ID` (`TICKET_ID`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`SESSION_ID`),
  ADD UNIQUE KEY `COOKIE` (`COOKIE`),
  ADD KEY `USER_ID_SESSION` (`USER_ID`);

--
-- Indexes for table `stat`
--
ALTER TABLE `stat`
  ADD PRIMARY KEY (`STAT_ID`),
  ADD KEY `USER_ID_STAT` (`USER_ID`);

--
-- Indexes for table `ticket_type`
--
ALTER TABLE `ticket_type`
  ADD PRIMARY KEY (`TICKET_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `MOVIE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `projection`
--
ALTER TABLE `projection`
  MODIFY `PROJ_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `ROOM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `SALE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `SESSION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `stat`
--
ALTER TABLE `stat`
  MODIFY `STAT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_type`
--
ALTER TABLE `ticket_type`
  MODIFY `TICKET_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projection`
--
ALTER TABLE `projection`
  ADD CONSTRAINT `projection_ibfk_1` FOREIGN KEY (`MOVIE_ID`) REFERENCES `movie` (`MOVIE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `projection_ibfk_2` FOREIGN KEY (`ROOM_ID`) REFERENCES `room` (`ROOM_ID`) ON DELETE CASCADE;

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`PROJ_ID`) REFERENCES `projection` (`PROJ_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_ibfk_3` FOREIGN KEY (`TICKET_ID`) REFERENCES `ticket_type` (`TICKET_ID`);

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `stat`
--
ALTER TABLE `stat`
  ADD CONSTRAINT `stat_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
