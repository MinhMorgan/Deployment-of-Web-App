-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 10:42 AM
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
-- Database: `midterm`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountID` int(11) NOT NULL,
  `StudentID` varchar(20) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `StudentEmail` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountID`, `StudentID`, `StudentName`, `StudentEmail`, `PasswordHash`, `CreatedAt`) VALUES
(1, '524k0024', 'lol', 'vuh726701@gmail.com', '$2y$10$wLOG7L4rMHA4rfFAZVQ5F.kdpjHt/8xrgReJPhpxvX6v9PEXbunyW', '2026-11-07 14:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `RegistrationID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `RegistrationDate` datetime DEFAULT current_timestamp(),
  `Status` varchar(20) NOT NULL DEFAULT 'Registered'
) ;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`RegistrationID`, `AccountID`, `SubjectID`, `RegistrationDate`, `Status`) VALUES
(1, 1, 1, '2026-11-07 15:23:46', 'Registered');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(150) NOT NULL,
  `Credits` int(11) NOT NULL,
  `MaxStudents` int(11) NOT NULL,
  `StartWeek` int(11) NOT NULL,
  `EndWeek` int(11) NOT NULL
) ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`SubjectID`, `SubjectName`, `Credits`, `MaxStudents`, `StartWeek`, `EndWeek`) VALUES
(1, 'Web Programming', 3, 40, 1, 15),
(2, 'Database Management', 3, 40, 1, 15),
(3, 'Software Engineering', 3, 35, 1, 15),
(4, 'Data Structures', 4, 40, 1, 15),
(5, 'Artificial Intelligence', 3, 30, 1, 15),
(6, 'Web Programming', 3, 40, 1, 15),
(7, 'Database Management', 3, 40, 1, 15),
(8, 'Software Engineering', 3, 35, 1, 15),
(9, 'Data Structures', 4, 40, 1, 15),
(10, 'Artificial Intelligence', 3, 30, 1, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`),
  ADD UNIQUE KEY `StudentEmail` (`StudentEmail`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`RegistrationID`),
  ADD UNIQUE KEY `AccountID` (`AccountID`,`SubjectID`),
  ADD KEY `SubjectID` (`SubjectID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`SubjectID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `AccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `RegistrationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `accounts` (`AccountID`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`SubjectID`) REFERENCES `subjects` (`SubjectID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
