-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2022 at 04:59 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `meeting_id` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `title`, `meeting_id`, `description`) VALUES
(1, 'Presidential Election', 'M001', 'Vote for the next president.'),
(2, 'Vice Presidential Election', 'M002', 'Vote for the next vice president.');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `meeting_id` VARCHAR(255) NOT NULL,
    `photo` VARCHAR(255) NOT NULL,
    `age` INT NOT NULL,
    `position` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `meeting_id`, `photo`, `age`, `position`, `description`) VALUES
(1, 'Apple', 'M001', 'apple.jpg', 45, 'President', 'Experienced leader with a strong vision.'),
(2, 'MSG', 'M002', 'msg.jpg', 38, 'Vice President', 'Dedicated and hardworking, ready to take on the role.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `otp` VARCHAR(6) NOT NULL,
    `status` ENUM('pending', 'active') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`, `otp`, `status`) VALUES 
('testuser', 'testuser@example.com', 'hashed_password_here', '123456', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `voter_id` VARCHAR(255) NOT NULL UNIQUE,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `voter_id`, `username`, `email`, `password`) VALUES
(1, 'V12345', 'Alice Johnson', 'alice@example.com', 'hashed_password_here'),
(2, 'V12346', 'Bob Brown', 'bob@example.com', 'hashed_password_here');

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_id` VARCHAR(255) NOT NULL UNIQUE,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `username`, `email`, `password`) VALUES
(1, 'A12345', 'Orange', 'orange@example.com', 'hashed_password_here'),
(2, 'A12346', 'Grape', 'grape@example.com', 'hashed_password_here');

-- --------------------------------------------------------

--
-- Table structure for table ``
--

CREATE TABLE `superadmin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `superadmin_id` VARCHAR(255) NOT NULL UNIQUE,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voters`
--

INSERT INTO `superadmin` (`id`, `superadmin_id`, `username`, `email`, `password`) VALUES
(1, 'Super-1500', '', 'admin123@example.com', 'hashed_password_here'),

-- --------------------------------------------------------

--
-- Table structure for table `votes_cast`
--

CREATE TABLE `votes_cast` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vote_id` INT NOT NULL,
    `candidate_id` INT NOT NULL,
    `voter_id` VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vote_id) REFERENCES votes(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    FOREIGN KEY (voter_id) REFERENCES voters(voter_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `votes_cast`
--

INSERT INTO `votes_cast` (`id`, `vote_id`, `candidate_id`, `voter_id`) VALUES
(1, 1, 1, 'V12345'),
(2, 2, 2, 'V12346');

-- --------------------------------------------------------

--
-- Table structure for table `timers`
--

CREATE TABLE `timers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vote_id` INT NOT NULL,
    `meeting_id` VARCHAR(255) NOT NULL,
    `start_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_time` DATETIME GENERATED ALWAYS AS (`start_time` + INTERVAL 1 DAY) STORED,
    FOREIGN KEY (`vote_id`) REFERENCES `votes`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timers`
--

INSERT INTO `timers` (`id`, `vote_id`, `meeting_id`) VALUES
(1, 1, 'M001'),
(2, 2, 'M002');

-- --------------------------------------------------------

--
-- Table structure for table `vote_results`
--

CREATE TABLE `vote_results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `vote_id` INT,
    `meeting_id` VARCHAR(255) NOT NULL,
    `candidate_name` VARCHAR(255),
    `position` VARCHAR(255),
    `voter_id` VARCHAR(255),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vote_id) REFERENCES votes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `vote_results`
--

INSERT INTO `vote_results` (`id`, `vote_id`, `meeting_id`, `candidate_name`, `position`, `voter_id`) VALUES
(1, 1, 'M001', 'Apple', 'President', 'V12345'),
(2, 2, 'M002', 'MSG', 'Vice President', 'V12346');

COMMIT;
