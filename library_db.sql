-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2026 at 08:24 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$VlEsUdEZwUTvyEJRkLTc5unpqZ48XXum5ViXj/2FoDFWkdRo2E4b2');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `status` enum('available','borrowed','reserved','maintenance') DEFAULT 'available',
  `borrowed_by` varchar(100) DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `year`, `status`, `borrowed_by`, `due_date`) VALUES
(6, 'The Alchemist', 'Paulo Coelho', 'Fiction', '1978', 'available', NULL, NULL),
(7, '1984', 'George Orwell', 'Science Fiction', '1922', 'borrowed', '', '2026-09-23'),
(8, 'Sapiens', 'Yuval Noah Harari', 'Non-Fiction', '2011', 'available', '', NULL),
(9, 'To Kill a Mockingbird', 'Harper Lee', 'Fiction', '1944', 'reserved', '', NULL),
(10, 'Wonder', 'R.J. Palacio', 'Fiction', '2012', 'borrowed', '', '2026-09-18'),
(11, 'The Kite Runner', 'Khaled Hosseini', 'Fiction', '2003', 'available', '', NULL),
(12, 'The Little Prince', 'Antoine de Saint-Exupery', 'Fiction', '1943', 'reserved', NULL, NULL),
(13, 'Dune', 'Frank Herbert', 'Science Fiction', '1965', 'borrowed', '', '2026-10-01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
