-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2024 at 02:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `health-management-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `profile`, `full_name`, `email`, `user_name`, `password`, `role`, `reset_token_expires_at`, `reset_token_hash`) VALUES
(1, NULL, 'Admin HMS', 'supp0rt.healthsys@outlook.com', 'admin', '$2y$10$gW3GbxTzRMHOKe6aBnWCKOwQoaE.wkxgtg8uoaTbdWvaMIRFnqImK', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `allergies`
--

CREATE TABLE `allergies` (
  `allergy_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `allergen_type` varchar(255) DEFAULT NULL,
  `reaction_severity` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int NOT NULL,
  `course` varchar(150) DEFAULT NULL,
  `acronym` varchar(150) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course`, `acronym`, `date_created`) VALUES
(2, 'Bachelor of Science in Information Technology', 'BSIT', '2024-04-26 15:36:09'),
(3, 'Bachelor of Science in Marine Biology', 'BSMB', '2024-04-26 15:37:03'),
(4, 'Bachelor of Science in Agriculture', 'BSA', '2024-04-26 15:38:43'),
(5, 'Bachelor of Science in Fisheries', 'BSF', '2024-04-26 15:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `health_conditions`
--

CREATE TABLE `health_conditions` (
  `condition_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `treatment_plan` varchar(255) DEFAULT NULL,
  `current_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `immunization_records`
--

CREATE TABLE `immunization_records` (
  `immunization_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `vaccination_type` varchar(255) DEFAULT NULL,
  `date_administered` date DEFAULT NULL,
  `next_due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `history_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `date_last_visit` date DEFAULT NULL,
  `medical_conditions` varchar(255) DEFAULT NULL,
  `surgeries` varchar(255) DEFAULT NULL,
  `medications` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int NOT NULL,
  `profile_stud` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `middle_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `contact_information` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allergies`
--
ALTER TABLE `allergies`
  ADD PRIMARY KEY (`allergy_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `health_conditions`
--
ALTER TABLE `health_conditions`
  ADD PRIMARY KEY (`condition_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `immunization_records`
--
ALTER TABLE `immunization_records`
  ADD PRIMARY KEY (`immunization_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allergies`
--
ALTER TABLE `allergies`
  MODIFY `allergy_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `health_conditions`
--
ALTER TABLE `health_conditions`
  MODIFY `condition_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `immunization_records`
--
ALTER TABLE `immunization_records`
  MODIFY `immunization_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allergies`
--
ALTER TABLE `allergies`
  ADD CONSTRAINT `allergies_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `health_conditions`
--
ALTER TABLE `health_conditions`
  ADD CONSTRAINT `health_conditions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `immunization_records`
--
ALTER TABLE `immunization_records`
  ADD CONSTRAINT `immunization_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
