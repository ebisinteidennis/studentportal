-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2025 at 05:17 AM
-- Server version: 10.6.21-MariaDB-cll-lve-log
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `byscpdmi_studentportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `program` varchar(50) DEFAULT 'National Diploma',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `program`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'System Administrator', 'National Diploma', '2025-05-24 14:55:16'),
(2, 'nd_admin', '0192023a7bbd73250516f069df18b500', 'National Diploma Admin', 'National Diploma', '2025-05-28 11:36:44'),
(3, 'sbm_admin', '0192023a7bbd73250516f069df18b500', 'Basic Midwifery Admin', 'School of Basic Midwifery', '2025-05-28 11:36:44'),
(4, 'sgn_admin', '0192023a7bbd73250516f069df18b500', 'General Nursing Admin', 'School of General Nursing', '2025-05-28 11:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_title` varchar(100) NOT NULL,
  `credit_unit` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `level` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_title`, `credit_unit`, `semester`, `level`) VALUES
(1, 'NUR101', 'Introduction to Nursing', 3, 'First', '100'),
(2, 'NUR102', 'Anatomy and Physiology', 4, 'First', '100'),
(3, 'NUR103', 'Basic Nursing Skills', 3, 'Second', '100'),
(4, 'NUR201', 'Medical Surgical Nursing I', 4, 'First', '200'),
(5, 'NUR202', 'Pharmacology', 3, 'Second', '200'),
(6, 'NUR203', 'Community Health Nursing', 3, 'First', '200'),
(7, 'NUR301', 'Advanced Medical Surgical Nursing', 4, 'First', '300'),
(8, 'NUR302', 'Pediatric Nursing', 3, 'Second', '300'),
(9, 'NUR303', 'Psychiatric Nursing', 3, 'First', '300'),
(10, 'NUR401', 'Nursing Management', 3, 'First', '400');

-- --------------------------------------------------------

--
-- Table structure for table `course_registrations`
--

CREATE TABLE `course_registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `session` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'registered',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `session` varchar(20) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `grade` varchar(2) DEFAULT NULL,
  `grade_point` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `course_id`, `semester`, `session`, `score`, `grade`, `grade_point`, `created_at`) VALUES
(1, 1, 1, 'First', '2024/2025', 12.00, 'F', 0.00, '2025-05-24 14:55:16'),
(2, 1, 2, 'First', '2024/2025', 78.00, 'A', 4.00, '2025-05-24 14:55:16'),
(3, 1, 3, 'Second', '2024/2025', 65.00, 'B', 3.00, '2025-05-24 14:55:16'),
(4, 1, 4, 'First', '2024/2025', 72.00, 'A', 4.00, '2025-05-24 14:55:16'),
(5, 4, 6, 'First', '2023/2024', 56.00, 'C', 2.00, '2025-05-25 06:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `matric_number` varchar(20) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `program` varchar(50) DEFAULT 'National Diploma',
  `state_of_origin` varchar(50) DEFAULT NULL,
  `local_govt_area` varchar(50) DEFAULT NULL,
  `jamb_reg_number` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_address` text DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT 'student123',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `matric_number`, `last_name`, `first_name`, `middle_name`, `marital_status`, `date_of_birth`, `department`, `program`, `state_of_origin`, `local_govt_area`, `jamb_reg_number`, `phone`, `email`, `contact_address`, `passport`, `password`, `created_at`) VALUES
(1, 'BYSCONS/2024/001', 'Doe', 'Jane', NULL, NULL, NULL, 'Nursing', 'National Diploma', 'Bayelsa', 'Yenagoa', NULL, '08012345678', 'jane.doe@byscons.edu.ng', NULL, NULL, 'student123', '2025-05-24 14:55:16'),
(2, 'BYSCONS/2024/002', 'Smith', 'John', NULL, NULL, NULL, 'Nursing', 'School of Basic Midwifery', 'Bayelsa', 'Brass', NULL, '08087654321', 'john.smith@byscons.edu.ng', NULL, NULL, 'student123', '2025-05-24 14:55:16'),
(3, 'BYSCONS/2024/003', 'Johnson', 'Mary', NULL, NULL, NULL, 'Nursing', 'School of General Nursing', 'Bayelsa', 'Sagbama', NULL, '08098765432', 'mary.johnson@byscons.edu.ng', NULL, NULL, 'student123', '2025-05-24 14:55:16'),
(4, 'BYSCONS/2024/004', 'Williams', 'David', NULL, NULL, NULL, 'Nursing', 'National Diploma', 'Bayelsa', 'Kolokuma/Opokuma', NULL, '08076543210', 'david.williams@byscons.edu.ng', NULL, NULL, 'student123', '2025-05-24 14:55:16'),
(5, 'BYSCONS/ND/2025/003', 'you', 'me', 'dd', NULL, NULL, 'Nursing', 'National Diploma', NULL, NULL, NULL, '08162997985', 'igiranbolowei@gmail.com', NULL, NULL, 'student123', '2025-05-28 12:03:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matric_number` (`matric_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_registrations`
--
ALTER TABLE `course_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
