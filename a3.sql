-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2022 at 06:08 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a3`
--
CREATE DATABASE IF NOT EXISTS `a3` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `a3`;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `ID` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Brand` varchar(20) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Regular` decimal(6,2) NOT NULL,
  `Extended` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`ID`, `Category`, `Brand`, `Description`, `Status`, `Regular`, `Extended`) VALUES
('456product', 'router', 'singtel', 'old', 'Available', '10.00', '20.00'),
('product123', 'laptop', 'asus', 'new', 'Available', '5.00', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `UserID` varchar(20) NOT NULL,
  `ProdID` varchar(20) NOT NULL,
  `Startdate` date NOT NULL,
  `Enddate` date NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Cost` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`UserID`, `ProdID`, `Startdate`, `Enddate`, `Status`, `Cost`) VALUES
('user1', '456product', '2022-02-24', '2022-02-25', 'Returned', '10.00'),
('user1', 'product123', '2022-02-24', '2022-02-26', 'Returned', '10.00'),
('user2', '456product', '2022-02-24', '2022-02-25', 'Returned', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `ID` varchar(20) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Surname` varchar(10) NOT NULL,
  `Phone` varchar(8) NOT NULL,
  `Email` varchar(25) NOT NULL,
  `Type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`ID`, `Password`, `Name`, `Surname`, `Phone`, `Email`, `Type`) VALUES
('user1', 'pw', 'ding xue', 'hoe', '97546435', 'fff@ggg.com', 0),
('user2', 'pw', 'yao', 'hong', '88755664', 'abc@gmail.com', 0),
('user5', 'pw', 'John', 'Smith', '99999999', 'das@gmail.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`UserID`,`ProdID`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
