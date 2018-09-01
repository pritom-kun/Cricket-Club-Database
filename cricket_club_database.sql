-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2018 at 06:31 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cricket_club_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `clubID` int(10) UNSIGNED NOT NULL,
  `club_name` varchar(30) NOT NULL,
  `president` varchar(30) DEFAULT NULL,
  `date_established` date DEFAULT NULL,
  `club_locationID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `playerID` int(10) NOT NULL,
  `clubID` int(10) NOT NULL,
  `contract_start_date` date NOT NULL,
  `contract_end_date` date NOT NULL,
  `paymentID` int(20) UNSIGNED NOT NULL,
  `witness1` varchar(50) NOT NULL,
  `witness2` varchar(50) NOT NULL,
  `designation` varchar(30) NOT NULL,
  `authorized_person` varchar(100) NOT NULL,
  `contract_amount` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `playerID` int(10) NOT NULL,
  `degree` varchar(20) NOT NULL,
  `institution` varchar(30) NOT NULL,
  `department` varchar(30) NOT NULL,
  `result` varchar(10) DEFAULT NULL,
  `year` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events_organised`
--

CREATE TABLE `events_organised` (
  `eventID` int(10) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `eventName` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `locationID` int(10) NOT NULL,
  `house` varchar(5) DEFAULT NULL,
  `street` varchar(20) DEFAULT NULL,
  `postCode` varchar(10) DEFAULT NULL,
  `thana` varchar(20) NOT NULL,
  `district` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `matchID` int(15) NOT NULL,
  `date_of_match` date DEFAULT NULL,
  `team_batting_first` varchar(30) DEFAULT NULL,
  `team_bowling_first` varchar(30) DEFAULT NULL,
  `man_of_the_match` varchar(30) DEFAULT NULL,
  `umpire` varchar(30) DEFAULT NULL,
  `venueID` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `match_performance`
--

CREATE TABLE `match_performance` (
  `playerID` int(10) NOT NULL,
  `matchID` int(10) NOT NULL,
  `total_runs` int(7) NOT NULL,
  `total_wickets` int(7) NOT NULL,
  `outstanding_performance` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `membership_details`
--

CREATE TABLE `membership_details` (
  `membershipID` int(10) NOT NULL,
  `Membership_name` varchar(30) NOT NULL,
  `membership_type` varchar(20) DEFAULT NULL,
  `Regi_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `playerID` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedule`
--

CREATE TABLE `payment_schedule` (
  `paymentID` int(20) NOT NULL,
  `due_date` date NOT NULL,
  `actual_payment_date` date NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_serial` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `personal_best`
--

CREATE TABLE `personal_best` (
  `playerID` int(10) NOT NULL,
  `club_name` varchar(30) NOT NULL,
  `club_against` varchar(30) NOT NULL,
  `runs` int(7) NOT NULL,
  `wickets` int(5) NOT NULL,
  `matchID` int(15) DEFAULT NULL,
  `eventID` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `playerID` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `father_name` varchar(20) DEFAULT NULL,
  `mother_name` varchar(20) DEFAULT NULL,
  `present_locationID` int(10) NOT NULL,
  `permanent_locationID` int(10) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `player_history`
--

CREATE TABLE `player_history` (
  `club_name` varchar(30) NOT NULL,
  `transferred_to` varchar(30) DEFAULT NULL,
  `transferred_from` varchar(30) DEFAULT NULL,
  `total_runs` int(7) NOT NULL,
  `total_wickets` int(5) NOT NULL,
  `team_leader` char(1) NOT NULL,
  `playerID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `clubID` int(10) NOT NULL,
  `formation_date` date DEFAULT NULL,
  `eventID` int(10) NOT NULL,
  `team_leaderID` int(10) NOT NULL,
  `coachID` int(10) NOT NULL,
  `coach_name` varchar(50) NOT NULL,
  `teamID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `team_playerlist`
--

CREATE TABLE `team_playerlist` (
  `teamID` int(10) NOT NULL,
  `playerID` int(10) NOT NULL,
  `player_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`clubID`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD KEY `playerID` (`playerID`);

--
-- Indexes for table `events_organised`
--
ALTER TABLE `events_organised`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`locationID`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`matchID`);

--
-- Indexes for table `match_performance`
--
ALTER TABLE `match_performance`
  ADD KEY `matchID` (`matchID`);

--
-- Indexes for table `membership_details`
--
ALTER TABLE `membership_details`
  ADD PRIMARY KEY (`membershipID`);

--
-- Indexes for table `personal_best`
--
ALTER TABLE `personal_best`
  ADD KEY `playerID` (`playerID`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`playerID`);

--
-- Indexes for table `player_history`
--
ALTER TABLE `player_history`
  ADD KEY `playerID` (`playerID`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`teamID`);

--
-- Indexes for table `team_playerlist`
--
ALTER TABLE `team_playerlist`
  ADD KEY `teamID` (`teamID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `clubID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `paymentID` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `playerID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `teamID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
