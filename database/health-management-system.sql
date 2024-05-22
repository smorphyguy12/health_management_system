-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 22, 2024 at 07:32 PM
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
(1, '../uploads/giphy.gif', 'Admin HMS', 'supp0rt.queuingsys@outlook.com', 'admin', '$2y$10$YsFPDflEbd.jbPl2flAEtuOQBsSq2a/lOngE3uMcOHkRbJz7CIpx2', 'admin', '2024-05-22 19:53:39', 'e5dfc804d5f4bccea95c06d77435d80a72f85a893d08a7d635cc561c2315b219'),
(4, '../uploads/IMG_20220802_222359.jpg', 'Mark Steven B. Peligro', 'markpeligro1234@gmail.com', 'Mark Steven', '$2y$10$JkXid.0sCgZeFfT9JnZ60eUUX343/IT.I7QJLRKLxOpv1zl92EHA.', 'staff', NULL, NULL);

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
-- Table structure for table `emergency_contact_information`
--

CREATE TABLE `emergency_contact_information` (
  `emergency_id` int NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `parent_guardian` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `emergency_contact_information`
--

INSERT INTO `emergency_contact_information` (`emergency_id`, `student_id`, `parent_guardian`, `relationship`, `phone_number`, `address`) VALUES
(9, '123456-1', 'ss', 'ada', '934834', 'Paku, Bontoc, So. Leyte');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `student_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `profile_stud` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `course` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_information` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `profile_stud`, `full_name`, `date_of_birth`, `gender`, `course`, `contact_information`) VALUES
(1, '2210169-2', '386445499_1020770055880690_8571224337334924637_n.jpg', 'Mark Steven B. Peligro', '2004-03-23', 'Male', 'Bachelor of Science in Information Technology', '09069225742'),
(3, '2210220-2', NULL, 'Jerrel Angel Padalapat', '2004-03-23', 'Female', 'Bachelor of Science in Information Technology', '099378283'),
(4, '123456-1', '421820647_2072185943151183_3662517506173831545_n.jpg', 'jawdaj', '2004-03-23', 'Male', 'Bachelor of Science in Information Technology', '09069225742'),
(5, 'test', 'Padalapat.png', 'test', '2004-03-23', 'Male', 'Bachelor of Science in Information Technology', '0903283'),
(6, 'adaw', '421820647_2072185943151183_3662517506173831545_n.jpg', 'wadaw', '2004-03-23', 'Male', 'Bachelor of Science in Information Technology', '099494');

-- --------------------------------------------------------

--
-- Table structure for table `student_health_information`
--

CREATE TABLE `student_health_information` (
  `student_health_id` int NOT NULL,
  `student_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `medications` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `medical_conditions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `immunization_record` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `height` int DEFAULT NULL,
  `weight` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `blood_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_health_information`
--

INSERT INTO `student_health_information` (`student_health_id`, `student_id`, `allergies`, `medications`, `medical_conditions`, `immunization_record`, `height`, `weight`, `blood_type`) VALUES
(31, '123456-1', 'dwad1xawd', 'wawdwa', 'wada', 'wada2', 12, '53', 'dwad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_contact_information`
--
ALTER TABLE `emergency_contact_information`
  ADD PRIMARY KEY (`emergency_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_health_information`
--
ALTER TABLE `student_health_information`
  ADD PRIMARY KEY (`student_health_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `emergency_contact_information`
--
ALTER TABLE `emergency_contact_information`
  MODIFY `emergency_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_health_information`
--
ALTER TABLE `student_health_information`
  MODIFY `student_health_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
